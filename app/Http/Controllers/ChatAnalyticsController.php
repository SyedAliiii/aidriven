<?php

namespace App\Http\Controllers;

use App\Exports\DynamicReportExport;
use App\Models\AnalyticsQuery;
use App\Models\Organization;
use App\Services\AIService;
use App\Services\DatabaseConnectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ChatAnalyticsController extends Controller
{
    public function __construct(
        private readonly AIService $aiService,
        private readonly DatabaseConnectionService $databaseConnectionService,
    ) {
    }

    public function index(): \Illuminate\View\View
    {
        $user = auth()->user();
        $history = [];

        if ($user) {
            $history = AnalyticsQuery::query()
                ->with('organization')
                ->where('user_id', $user->id)
                ->orderByDesc('id')
                ->limit(30)
                ->get();
        }

        return view('analytics.chat', [
            'history' => $history,
        ]);
    }

    /**
     * Receives text question, generates SQL with Gemini, executes it, and returns results.
     */
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:2000'],
            'organization_id' => ['nullable', 'integer', 'exists:organizations,id'],
        ]);

        $user = auth()->user();
        if (!$user) {
            throw ValidationException::withMessages([
                'auth' => 'You must be logged in.',
            ]);
        }

        $organizationId = $validated['organization_id']
            ?? $user->organization_id
            ?? null;

        if (!$organizationId) {
            return response()->json([
                'message' => 'No organization is assigned to your account.',
            ], 403);
        }

        $organization = Organization::findOrFail($organizationId);

        try {
            $generatedResult = $this->aiService->generateQuery(
                userPrompt: $validated['question'],
                orgId: (int) $organization->id,
            );

            // Clean the generated result from markdown blocks if any
            $cleanedResult = trim($generatedResult);
            $cleanedResult = preg_replace('/^```(?:sql)?\s*/i', '', $cleanedResult) ?? $cleanedResult;
            $cleanedResult = preg_replace('/```$/', '', $cleanedResult) ?? $cleanedResult;
            $cleanedResult = trim($cleanedResult);

            // Check if it's a conversation or greeting
            $isConversation = false;
            if (str_starts_with($cleanedResult, 'CONVERSATION:')) {
                $isConversation = true;
                $message = trim(substr($cleanedResult, strlen('CONVERSATION:')));
            } elseif (!$this->aiService->startsWithSelect($cleanedResult)) {
                // If it doesn't start with SELECT, it's not a query, so treat it as conversation
                $isConversation = true;
                $message = $cleanedResult;
            }

            if ($isConversation) {
                // Create history record for conversation
                $historyItem = AnalyticsQuery::create([
                    'user_id' => $user->id,
                    'organization_id' => $organization->id,
                    'question' => $validated['question'],
                    'sql' => null,
                    'row_count' => 0,
                ]);

                return response()->json([
                    'message' => $message,
                    'raw_sql' => null,
                    'columns' => [],
                    'rows' => [],
                    'is_conversation' => true,
                    'history_item' => [
                        'question' => $historyItem->question,
                        'created_at' => $historyItem->created_at->format('d M, H:i'),
                        'organization_name' => $organization->name,
                    ]
                ]);
            }

            $safeSql = $this->aiService->sanitizeGeneratedQuery($cleanedResult);
            if ($safeSql === 'ERROR: Unauthorized Action') {
                return response()->json([
                    'message' => 'Only SELECT queries are allowed for safety.',
                    'raw_sql' => null,
                    'columns' => [],
                    'rows' => [],
                ], 422);
            }
        } catch (Throwable $e) {
            Log::error('Analytics AI query generation failed', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
            ]);

            $errorMessage = $e->getMessage();
            $isGeminiHighDemand = $this->isGeminiHighDemandError($errorMessage);
            $message = match (true) {
                $isGeminiHighDemand =>
                    'This model is currently experiencing high demand. Spikes in demand are usually temporary. Please try again later.',
                str_contains($errorMessage, 'GEMINI_API_KEY') =>
                    'Gemini API key is missing. Please set GEMINI_API_KEY in .env.',
                str_contains($errorMessage, 'password is NULL or empty') =>
                    'Selected organization DB password is empty. Please update it from Organizations module.',
                str_contains($errorMessage, 'cURL error 60') =>
                    'SSL verification failed on this machine. Set GEMINI_VERIFY_SSL=false or OPENAI_VERIFY_SSL=false in your .env for local development, or configure the appropriate CA bundle.',
                default =>
                    'Failed to generate a safe SQL query. Please refine your question.',
            };

            return response()->json([
                'message' => $message,
                'raw_sql' => null,
                'columns' => [],
                'rows' => [],
                'retry_after_seconds' => $isGeminiHighDemand ? $this->extractRetryAfterSeconds($errorMessage) : null,
            ], $isGeminiHighDemand ? 429 : 500);
        }

        try {
            $this->databaseConnectionService->connectForOrganization($organization);
            $results = DB::connection('dynamic_snd')->select($safeSql);

            $rows = array_map(static fn ($row) => (array) $row, $results);
            $columns = empty($rows) ? [] : array_keys($rows[0]);

            $historyItem = AnalyticsQuery::create([
                'user_id' => $user->id,
                'organization_id' => $organization->id,
                'question' => $validated['question'],
                'sql' => $safeSql,
                'row_count' => count($rows),
            ]);

            return response()->json([
                'message' => empty($rows) ? 'No results found.' : 'Success',
                'raw_sql' => $safeSql,
                'columns' => $columns,
                'rows' => $rows,
                'history_item' => [
                    'question' => $historyItem->question,
                    'created_at' => $historyItem->created_at->format('d M, H:i'),
                    'organization_name' => $organization->name,
                ]
            ]);
        } catch (Throwable $e) {
            Log::error('Analytics ask query execution failed', [
                'organization_id' => $organization->id,
                'sql' => $safeSql,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to execute the generated query. Please refine your question.',
                'raw_sql' => $safeSql,
                'columns' => [],
                'rows' => [],
            ], 500);
        }
    }

    /**
     * Re-executes the generated SQL and exports it to Excel.
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'sql' => ['required', 'string', 'max:20000'],
        ]);

        $organization = Organization::findOrFail($validated['organization_id']);

        $safeSql = $this->aiService->sanitizeGeneratedQuery($validated['sql']);
        if ($safeSql === 'ERROR: Unauthorized Action') {
            return response()->json([
                'message' => 'Only SELECT queries can be exported.',
            ], 422);
        }

        try {
            $this->databaseConnectionService->connectForOrganization($organization);
            $results = DB::connection('dynamic_snd')->select($safeSql);
            $rows = array_map(static fn ($row) => (array) $row, $results);
            $columns = empty($rows) ? [] : array_keys($rows[0]);

            $export = new DynamicReportExport(columns: $columns, rows: $rows);
            $filename = sprintf('analytics_org_%d_%s.xlsx', $organization->id, now()->format('Ymd_His'));

            return Excel::download($export, $filename);
        } catch (Throwable $e) {
            Log::error('Analytics export failed', [
                'organization_id' => $organization->id,
                'sql' => $safeSql,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to export the report. Please try again.',
            ], 500);
        }
    }

    private function isGeminiHighDemandError(string $errorMessage): bool
    {
        $normalized = strtolower($errorMessage);

        return str_contains($normalized, 'high demand')
            || str_contains($normalized, 'resource exhausted')
            || str_contains($normalized, 'rate limit')
            || str_contains($normalized, 'quota')
            || str_contains($normalized, '429');
    }

    private function extractRetryAfterSeconds(string $errorMessage): ?int
    {
        if (preg_match('/retry in\s+([0-9]+(?:\.[0-9]+)?)s/i', $errorMessage, $matches) === 1) {
            return (int) ceil((float) $matches[1]);
        }

        if (preg_match('/([0-9]+)\s*seconds?/i', $errorMessage, $matches) === 1) {
            return (int) $matches[1];
        }

        return null;
    }
}

