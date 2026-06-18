<?php

namespace App\Services;

use App\Models\AISettings;
use App\Models\AIGuide;
use App\Models\Organization;
use Gemini;
use Gemini\Data\Content;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\DB;

class AIService
{
    public function __construct(
        private readonly DatabaseConnectionService $databaseConnectionService,
    ) {
    }

    /**
     * Generates a SQL query for the given user prompt and organization.
     *
     * @return string Either raw SELECT SQL or `ERROR: Unauthorized Action`
     */
    public function generateQuery(string $userPrompt, int $orgId): string
    {
        $organization = Organization::findOrFail($orgId);
        $this->databaseConnectionService->connectForOrganization($organization);

        $fullSchema = $this->introspectSchemaMetadata();
        $schemaMetadata = $this->buildRelevantSchemaContext(
            userPrompt: $userPrompt,
            schemaByTable: $fullSchema,
        );

        $settings = AISettings::current();
        $guideContext = $this->buildGuideContext(AIGuide::current());

        return match ($settings->provider) {
            'openai' => $this->generateWithOpenAI($userPrompt, $schemaMetadata, $guideContext, $settings),
            default => $this->generateWithGemini($userPrompt, $schemaMetadata, $guideContext, $settings),
        };
    }

    private function generateWithGemini(string $userPrompt, string $schemaMetadata, string $guideContext, AISettings $settings): string
    {
        $apiKey = config('gemini.api_key');
        if (blank($apiKey)) {
            throw new \RuntimeException('GEMINI_API_KEY is not configured.');
        }

        $model = $settings->gemini_model ?: config('gemini.model', 'gemini-2.5-flash');
        $timeout = (int) config('gemini.timeout', 60);
        $verifySsl = (bool) config('gemini.verify_ssl', true);
        $caBundle = config('gemini.ca_bundle');

        $systemInstruction = "You are a MySQL expert for business analytics.\n"
            . "You can understand user questions in any language (including Urdu, Roman Urdu, Hindi, and English).\n"
            . "Map business terms like executed sale, non executed, absent TSO, today/yesterday, month-to-date.\n"
            . "First identify relevant tables/columns from the provided schema context.\n"
            . "Then write a valid SQL query strictly according to available column names.\n"
            . "Return ONLY the raw SQL string.\n"
            . "No markdown, no '```sql', no explanations.\n"
            . "Use LIMIT 100 by default.\n"
            . "If the query contains DROP, DELETE, or UPDATE, return 'ERROR: Unauthorized Action'.\n\n"
            . "IMPORTANT: If the user is just saying a casual greeting (like hi, hello, salam, etc.) or asking a question that is conversational/general and does not require database querying or reporting, do NOT write a SQL query. Instead, respond with a friendly conversational response prefixed with 'CONVERSATION: ' (e.g. 'CONVERSATION: Hello! How can I help you today?').\n\n"
            . "Business guide (highest priority):\n"
            . $guideContext;

        $userMessage = "Schema (MySQL):\n{$schemaMetadata}\n\n"
            . "User question:\n{$userPrompt}\n\n"
            . "Remember: return ONLY the raw SQL string (or a CONVERSATION: response if casual).";

        $httpOptions = ['timeout' => $timeout];
        if ($caBundle) {
            $httpOptions['verify'] = $caBundle;
        } else {
            $httpOptions['verify'] = $verifySsl;
        }

        $client = Gemini::factory()
            ->withApiKey($apiKey)
            ->withHttpClient(new GuzzleClient($httpOptions))
            ->make();

        try {
            $response = $client
                ->generativeModel(model: $model)
                ->withSystemInstruction(Content::parse($systemInstruction))
                ->generateContent($userMessage);
        } catch (\Throwable) {
            // Fallback for client/system-instruction compatibility variations.
            $response = $client
                ->generativeModel(model: $model)
                ->generateContent($systemInstruction . "\n\n" . $userMessage);
        }

        $generated = $response->text();

        return trim($generated);
    }

    private function generateWithOpenAI(string $userPrompt, string $schemaMetadata, string $guideContext, AISettings $settings): string
    {
        $apiKey = config('openai.api_key');
        if (blank($apiKey)) {
            throw new \RuntimeException('OPENAI_API_KEY is not configured.');
        }

        $baseUrl = rtrim(config('openai.base_url', 'https://api.openai.com/v1'), '/');
        $model = $settings->openai_model ?: config('openai.model', 'gpt-4.1-mini');
        $timeout = (int) config('openai.timeout', 60);
        $verifySsl = (bool) config('openai.verify_ssl', true);
        $caBundle = config('openai.ca_bundle');

        $systemInstruction = "You are a MySQL expert for business analytics.\n"
            . "You can understand user questions in any language (including Urdu, Roman Urdu, Hindi, and English).\n"
            . "Map business terms like executed sale, non executed, absent TSO, today/yesterday, month-to-date.\n"
            . "First identify relevant tables/columns from the provided schema context.\n"
            . "Then write a valid SQL query strictly according to available column names.\n"
            . "Return ONLY the raw SQL string.\n"
            . "No markdown, no '```sql', no explanations.\n"
            . "Use LIMIT 100 by default.\n"
            . "If the query contains DROP, DELETE, or UPDATE, return 'ERROR: Unauthorized Action'.\n\n"
            . "IMPORTANT: If the user is just saying a casual greeting (like hi, hello, salam, etc.) or asking a question that is conversational/general and does not require database querying or reporting, do NOT write a SQL query. Instead, respond with a friendly conversational response prefixed with 'CONVERSATION: ' (e.g. 'CONVERSATION: Hello! How can I help you today?').\n\n"
            . "Business guide (highest priority):\n"
            . $guideContext;

        $userMessage = "Schema (MySQL):\n{$schemaMetadata}\n\n"
            . "User question:\n{$userPrompt}\n\n"
            . "Remember: return ONLY the raw SQL string (or a CONVERSATION: response if casual).";

        $httpOptions = [
            'base_uri' => $baseUrl . '/',
            'timeout' => $timeout,
        ];
        if ($caBundle) {
            $httpOptions['verify'] = $caBundle;
        } else {
            $httpOptions['verify'] = $verifySsl;
        }

        $client = new GuzzleClient($httpOptions);

        $response = $client->post('chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemInstruction],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.1,
            ],
        ]);

        $data = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $generated = $data['choices'][0]['message']['content'] ?? '';

        return trim($generated);
    }

    /**
     * Builds a compact schema map by running SHOW TABLES and DESCRIBE per table.
     */
    private function introspectSchemaMetadata(): array
    {
        $tablesResult = DB::connection('dynamic_snd')->select('SHOW TABLES');

        $tableNames = [];
        foreach ($tablesResult as $row) {
            $values = array_values(get_object_vars($row));
            if (isset($values[0]) && is_string($values[0]) && $values[0] !== '') {
                $tableNames[] = $values[0];
            }
        }

        // Keep prompt size reasonable.
        $tableNames = array_slice($tableNames, 0, 30);

        $schemaByTable = [];

        foreach ($tableNames as $tableName) {
            $escaped = str_replace('`', '``', $tableName);
            $columns = DB::connection('dynamic_snd')->select("DESCRIBE `{$escaped}`");
            $columnLines = [];
            $columnNames = [];

            foreach ($columns as $column) {
                $field = $column->Field ?? '';
                $type = $column->Type ?? '';
                $null = ($column->Null ?? '') === 'NO' ? 'NOT NULL' : 'NULL';
                $key = $column->Key ?? '';
                $default = $column->Default ?? null;
                $extra = $column->Extra ?? '';

                $line = "{$field}: {$type}, {$null}";
                if ($key !== '') {
                    $line .= ", KEY={$key}";
                }
                if ($default !== null && $default !== '') {
                    $line .= ", DEFAULT={$default}";
                }
                if ($extra !== '') {
                    $line .= ", EXTRA={$extra}";
                }

                $columnLines[] = $line;
                if ($field !== '') {
                    $columnNames[] = mb_strtolower($field);
                }
            }

            $schemaByTable[$tableName] = [
                'name' => $tableName,
                'name_lc' => mb_strtolower($tableName),
                'columns' => $columnLines,
                'columns_lc' => $columnNames,
            ];
        }

        return $schemaByTable;
    }

    /**
     * Searches schema first, then returns a prompt-sized relevant schema context.
     */
    private function buildRelevantSchemaContext(string $userPrompt, array $schemaByTable): string
    {
        $terms = $this->extractSearchTerms($userPrompt);
        $ranked = [];

        foreach ($schemaByTable as $table) {
            $score = 0;

            foreach ($terms as $term) {
                if ($term === '') {
                    continue;
                }

                if (str_contains($table['name_lc'], $term)) {
                    $score += 5;
                }

                foreach ($table['columns_lc'] as $columnName) {
                    if ($columnName === $term) {
                        $score += 5;
                    } elseif (str_contains($columnName, $term) || str_contains($term, $columnName)) {
                        $score += 2;
                    }
                }
            }

            $ranked[] = [
                'score' => $score,
                'table' => $table,
            ];
        }

        usort($ranked, static fn ($a, $b) => $b['score'] <=> $a['score']);

        $top = array_values(array_filter($ranked, static fn ($row) => $row['score'] > 0));
        if (empty($top)) {
            // Fallback: no keyword hit, include first few tables so AI can still respond.
            $top = array_slice($ranked, 0, 8);
        } else {
            $top = array_slice($top, 0, 12);
        }

        $lines = [];
        foreach ($top as $entry) {
            $table = $entry['table'];
            $lines[] = "Table: {$table['name']}";
            foreach ($table['columns'] as $columnLine) {
                $lines[] = "  - {$columnLine}";
            }
        }

        return implode("\n", $lines);
    }

    private function extractSearchTerms(string $text): array
    {
        $normalized = mb_strtolower($text);
        $normalized = preg_replace('/[^\p{L}\p{N}_\s]+/u', ' ', $normalized) ?? $normalized;
        $parts = preg_split('/\s+/u', trim($normalized)) ?: [];

        $stopWords = [
            'the', 'a', 'an', 'is', 'are', 'in', 'of', 'to', 'for', 'and', 'or',
            'ki', 'ka', 'ke', 'ko', 'se', 'hai', 'hain', 'me', 'mn', 'aur', 'or',
            'today', 'yesterday', 'month', 'total', 'show', 'kitna', 'kitni', 'konsy',
        ];
        $stopLookup = array_fill_keys($stopWords, true);

        $terms = [];
        foreach ($parts as $part) {
            if (mb_strlen($part) < 2) {
                continue;
            }
            if (isset($stopLookup[$part])) {
                continue;
            }
            $terms[] = $part;
        }

        return array_values(array_unique($terms));
    }

    /**
     * Enforces SELECT-only and removes markdown/code fences.
     */
    public function sanitizeGeneratedQuery(string $sql): string
    {
        $sql = trim($sql);
        if ($sql === '') {
            return 'ERROR: Unauthorized Action';
        }

        // Remove markdown fences if Gemini returned them anyway.
        $sql = preg_replace('/^```(?:sql)?\s*/i', '', $sql) ?? $sql;
        $sql = preg_replace('/```$/', '', $sql) ?? $sql;
        $sql = trim($sql);

        // Reject any non-SELECT statement.
        if (!$this->startsWithSelect($sql)) {
            return 'ERROR: Unauthorized Action';
        }

        // Reject common mutation keywords defensively.
        if (preg_match('/\b(DROP|DELETE|UPDATE|TRUNCATE|ALTER|INSERT|CREATE|REPLACE|RENAME|GRANT|REVOKE|BEGIN|COMMIT|ROLLBACK)\b/i', $sql)) {
            return 'ERROR: Unauthorized Action';
        }

        // Ensure a single statement only (basic heuristic).
        $withoutTrailingSemi = rtrim($sql);
        if (str_ends_with($withoutTrailingSemi, ';')) {
            $withoutTrailingSemi = substr($withoutTrailingSemi, 0, -1);
        }
        if (substr_count($withoutTrailingSemi, ';') > 0) {
            return 'ERROR: Unauthorized Action';
        }

        // Default LIMIT 100 if model didn't include one.
        if (!preg_match('/\bLIMIT\b/i', $withoutTrailingSemi)) {
            $withoutTrailingSemi = rtrim($withoutTrailingSemi) . ' LIMIT 100';
        }

        return trim($withoutTrailingSemi) . ';';
    }

    public function startsWithSelect(string $sql): bool
    {
        // Strip leading whitespace and common comments.
        $normalized = ltrim($sql);

        for ($i = 0; $i < 5; $i++) {
            if (preg_match('/^(\/\*.*?\*\/\s*)/s', $normalized, $m) === 1) {
                $normalized = substr($normalized, strlen($m[1]));
                continue;
            }

            if (preg_match('/^(--[^\n]*\n\s*)/s', $normalized, $m) === 1) {
                $normalized = substr($normalized, strlen($m[1]));
                continue;
            }

            break;
        }

        return preg_match('/^SELECT\b/i', $normalized) === 1;
    }

    private function buildGuideContext(AIGuide $guide): string
    {
        $lines = [];
        $global = trim((string) ($guide->global_instruction ?? ''));
        if ($global !== '') {
            $lines[] = "Global instructions:";
            $lines[] = $global;
        }

        $lines[] = '';
        $lines[] = 'Term mappings:';
        foreach (($guide->term_mappings ?? []) as $item) {
            $term = trim((string) ($item['term'] ?? ''));
            $meaning = trim((string) ($item['meaning'] ?? ''));
            if ($term !== '' && $meaning !== '') {
                $lines[] = "- {$term} => {$meaning}";
            }
        }

        $lines[] = '';
        $lines[] = 'Column aliases:';
        foreach (($guide->column_aliases ?? []) as $item) {
            $alias = trim((string) ($item['alias'] ?? ''));
            $column = trim((string) ($item['column'] ?? ''));
            if ($alias !== '' && $column !== '') {
                $lines[] = "- {$alias} => {$column}";
            }
        }

        $lines[] = '';
        $lines[] = 'Metric formulas:';
        foreach (($guide->metric_formulas ?? []) as $item) {
            $metric = trim((string) ($item['metric'] ?? ''));
            $formula = trim((string) ($item['formula'] ?? ''));
            if ($metric !== '' && $formula !== '') {
                $lines[] = "- {$metric} => {$formula}";
            }
        }

        return trim(implode("\n", $lines));
    }
}

