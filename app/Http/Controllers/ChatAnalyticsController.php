<?php

namespace App\Http\Controllers;

use App\Exports\DynamicReportExport;
use App\Models\AnalyticsQuery;
use App\Models\AnalyticsSession;
use App\Models\Organization;
use App\Services\AIService;
use App\Services\DatabaseConnectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
        $sessions = [];

        if ($user) {
            $sessions = AnalyticsSession::query()
                ->with('organization')
                ->where('user_id', $user->id)
                ->orderByDesc('id')
                ->limit(40)
                ->get();
        }

        return view('analytics.chat', [
            'sessions' => $sessions,
        ]);
    }

    /**
     * Returns the messages for a given session as JSON.
     */
    public function showSession(AnalyticsSession $session): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $session->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $queries = $session->queries()->orderBy('id')->get();

        $messages = [];
        foreach ($queries as $query) {
            // User message
            $messages[] = [
                'role' => 'user',
                'text' => $query->question,
            ];

            // AI message
            if ($query->sql) {
                $messages[] = [
                    'role'      => 'assistant',
                    'text'      => $query->ai_response ?: ($query->row_count > 0 ? "Found {$query->row_count} result(s)." : 'No results found.'),
                    'sql'       => $query->sql,
                    'row_count' => $query->row_count,
                    'columns'   => $query->result_columns ?? [],
                    'rows'      => [],   // we don't re-execute; JS will show count summary
                    'ai_response' => $query->ai_response,
                ];
            } else {
                // Conversational reply
                $messages[] = [
                    'role' => 'assistant',
                    'text' => $query->ai_response ?: '(No content saved)',
                    'is_conversation' => true,
                ];
            }
        }

        return response()->json([
            'session_id' => $session->id,
            'title' => $session->title,
            'organization_id' => $session->organization_id,
            'messages' => $messages,
        ]);
    }

    public function destroySession(AnalyticsSession $session): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $session->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $session->delete();

        return response()->json([
            'message' => 'Chat history deleted.',
        ]);
    }

    /**
     * Receives text question, generates SQL with AI, executes it, and returns results.
     */
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question'        => ['required', 'string', 'max:2000'],
            'organization_id' => ['nullable', 'integer', 'exists:organizations,id'],
            'session_id'      => ['nullable', 'integer', 'exists:analytics_sessions,id'],
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

        // Resolve or create session
        $session = null;
        if (!empty($validated['session_id'])) {
            $session = AnalyticsSession::where('id', $validated['session_id'])
                ->where('user_id', $user->id)
                ->first();
        }

        if (!$session) {
            $session = AnalyticsSession::create([
                'user_id'         => $user->id,
                'organization_id' => $organization->id,
                'title'           => Str::limit($validated['question'], 60),
            ]);
        }

        try {
            $history = $session->queries()
                ->orderByDesc('id')
                ->limit(8)
                ->get()
                ->reverse()
                ->map(static fn (AnalyticsQuery $query) => [
                    'question' => $query->question,
                    'sql' => $query->sql,
                    'reply' => $query->ai_response,
                ])
                ->values()
                ->all();

            $generatedResult = $this->aiService->generateQuery(
                userPrompt: $validated['question'],
                orgId: (int) $organization->id,
                chatHistory: $history,
            );

            // Clean markdown fences if any
            $cleanedResult = trim($generatedResult);
            $cleanedResult = preg_replace('/^```(?:sql)?\s*/i', '', $cleanedResult) ?? $cleanedResult;
            $cleanedResult = preg_replace('/```$/', '', $cleanedResult) ?? $cleanedResult;
            $cleanedResult = trim($cleanedResult);

            // Check if it's a conversational response
            $isConversation = false;
            if (str_starts_with($cleanedResult, 'CONVERSATION:')) {
                $isConversation = true;
                $message = trim(substr($cleanedResult, strlen('CONVERSATION:')));
            } elseif (!$this->aiService->startsWithSelect($cleanedResult)) {
                $isConversation = true;
                $message = $cleanedResult;
            }

            if ($isConversation) {
                AnalyticsQuery::create([
                    'user_id'         => $user->id,
                    'organization_id' => $organization->id,
                    'session_id'      => $session->id,
                    'question'        => $validated['question'],
                    'sql'             => null,
                    'ai_response'     => $message,
                    'row_count'       => 0,
                ]);

                return response()->json([
                    'message'      => $message,
                    'raw_sql'      => null,
                    'columns'      => [],
                    'rows'         => [],
                    'is_conversation' => true,
                    'session_id'   => $session->id,
                    'is_new_session' => $session->wasRecentlyCreated,
                    'session_title' => $session->title,
                    'session_created_at' => $session->created_at->format('d M, H:i'),
                    'organization_id' => $organization->id,
                    'organization_name' => $organization->name,
                ]);
            }

            $safeSql = $this->aiService->sanitizeGeneratedQuery(
                sql: $cleanedResult,
                addDefaultLimit: !$this->shouldReturnFullRecordSet($validated['question']),
            );
            if ($safeSql === 'ERROR: Unauthorized Action') {
                return response()->json([
                    'message' => 'Only SELECT queries are allowed for safety.',
                    'raw_sql' => null,
                    'columns' => [],
                    'rows'    => [],
                ], 422);
            }
        } catch (Throwable $e) {
            Log::error('Analytics AI query generation failed', [
                'organization_id' => $organization->id,
                'error'           => $e->getMessage(),
            ]);

            $errorMessage = $e->getMessage();
            $isHighDemand = $this->isGeminiHighDemandError($errorMessage);
            $message = match (true) {
                $isHighDemand =>
                    'This model is currently experiencing high demand. Please try again later.',
                str_contains($errorMessage, 'GEMINI_API_KEY') =>
                    'Gemini API key is missing. Please set GEMINI_API_KEY in .env.',
                str_contains($errorMessage, 'password is NULL or empty') =>
                    'Selected organization DB password is empty. Please update it from Organizations module.',
                str_contains($errorMessage, 'cURL error 60') =>
                    'SSL verification failed. Set GEMINI_VERIFY_SSL=false or OPENAI_VERIFY_SSL=false in your .env.',
                default =>
                    'Failed to generate a safe SQL query. Please refine your question.',
            };

            return response()->json([
                'message'             => $message,
                'raw_sql'             => null,
                'columns'             => [],
                'rows'                => [],
                'retry_after_seconds' => $isHighDemand ? $this->extractRetryAfterSeconds($errorMessage) : null,
            ], $isHighDemand ? 429 : 500);
        }

        try {
            $this->databaseConnectionService->connectForOrganization($organization);
            $results = DB::connection('dynamic_snd')->select($safeSql);

            $rows    = array_map(static fn ($row) => (array) $row, $results);
            $columns = empty($rows) ? [] : array_keys($rows[0]);

            $aiResponse = $this->buildAssistantResultReply(
                question: $validated['question'],
                rowCount: count($rows),
                columns: $columns,
            );

            AnalyticsQuery::create([
                'user_id'         => $user->id,
                'organization_id' => $organization->id,
                'session_id'      => $session->id,
                'question'        => $validated['question'],
                'sql'             => $safeSql,
                'ai_response'     => $aiResponse,
                'row_count'       => count($rows),
                'result_columns'  => $columns,
            ]);

            return response()->json([
                'message'            => empty($rows) ? 'No results found.' : 'Success',
                'ai_response'        => $aiResponse,
                'raw_sql'            => $safeSql,
                'columns'            => $columns,
                'rows'               => $rows,
                'session_id'         => $session->id,
                'is_new_session'     => $session->wasRecentlyCreated,
                'session_title'      => $session->title,
                'session_created_at' => $session->created_at->format('d M, H:i'),
                'organization_id'    => $organization->id,
                'organization_name'  => $organization->name,
            ]);
        } catch (Throwable $e) {
            Log::error('Analytics ask query execution failed', [
                'organization_id' => $organization->id,
                'sql'             => $safeSql,
                'error'           => $e->getMessage(),
            ]);

            $errMsg = $e->getMessage();
            $userMessage = match (true) {
                str_contains($errMsg, '1054') || str_contains($errMsg, 'Unknown column') =>
                    'The AI used a column name that doesn\'t exist in your database. '
                    . 'Please rephrase your question with more specific details, or check the Advanced panel for the generated SQL.',
                str_contains($errMsg, '1146') || str_contains($errMsg, "doesn't exist") =>
                    'The AI referenced a table that doesn\'t exist. Please rephrase your question.',
                str_contains($errMsg, '1064') || str_contains($errMsg, 'syntax error') =>
                    'The AI generated a SQL syntax error. Please try rephrasing your question differently.',
                str_contains($errMsg, 'Access denied') =>
                    'Database access denied. Please check the organization\'s database credentials.',
                str_contains($errMsg, 'Connection refused') || str_contains($errMsg, 'SQLSTATE[HY000]') =>
                    'Could not connect to the organization\'s database. Please verify the connection settings.',
                default =>
                    'Failed to execute the generated query. Please try rephrasing your question.',
            };

            return response()->json([
                'message' => $userMessage,
                'raw_sql' => $safeSql,
                'columns' => [],
                'rows'    => [],
            ], 422);
        }
    }

    /**
     * Re-executes the generated SQL and exports it to Excel.
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'sql'             => ['required', 'string', 'max:20000'],
        ]);

        $organization = Organization::findOrFail($validated['organization_id']);

        $safeSql = $this->aiService->sanitizeGeneratedQuery(
            sql: $validated['sql'],
            addDefaultLimit: false,
        );
        if ($safeSql === 'ERROR: Unauthorized Action') {
            return response()->json([
                'message' => 'Only SELECT queries can be exported.',
            ], 422);
        }

        try {
            $this->databaseConnectionService->connectForOrganization($organization);
            $results  = DB::connection('dynamic_snd')->select($safeSql);
            $rows     = array_map(static fn ($row) => (array) $row, $results);
            $columns  = empty($rows) ? [] : array_keys($rows[0]);

            $export   = new DynamicReportExport(columns: $columns, rows: $rows);
            $filename = sprintf('analytics_org_%d_%s.xlsx', $organization->id, now()->format('Ymd_His'));

            return Excel::download($export, $filename);
        } catch (Throwable $e) {
            Log::error('Analytics export failed', [
                'organization_id' => $organization->id,
                'sql'             => $safeSql,
                'error'           => $e->getMessage(),
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

    private function buildAssistantResultReply(string $question, int $rowCount, array $columns): string
    {
        if ($rowCount === 0) {
            return 'I checked the available data for your question, but no matching records were found.';
        }

        $columnSummary = empty($columns)
            ? ''
            : ' The table includes: ' . implode(', ', array_slice($columns, 0, 8)) . (count($columns) > 8 ? ', and more' : '') . '.';

        return "I found {$rowCount} matching result" . ($rowCount === 1 ? '' : 's') . " for: \"{$question}\"." . $columnSummary;
    }

    private function shouldReturnFullRecordSet(string $question): bool
    {
        $normalized = mb_strtolower($question);

        return str_contains($normalized, 'full record')
            || str_contains($normalized, 'all record')
            || str_contains($normalized, 'all records')
            || str_contains($normalized, 'not only 100')
            || str_contains($normalized, 'without limit')
            || str_contains($normalized, 'no limit')
            || str_contains($normalized, 'show full')
            || str_contains($normalized, 'sab dikhao')
            || str_contains($normalized, 'sara dikhao')
            || str_contains($normalized, 'saara dikhao')
            || str_contains($normalized, 'complete data')
            || str_contains($normalized, 'complete record');
    }
}
