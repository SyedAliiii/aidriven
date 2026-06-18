<?php

namespace App\Http\Controllers;

use App\Models\AIGuide;
use Illuminate\Http\Request;

class AIGuideController extends Controller
{
    private function authorizeSuperAdmin(): void
    {
        $user = auth()->user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('super-admin')) {
            abort(403);
        }
    }

    public function edit()
    {
        $this->authorizeSuperAdmin();

        return view('admin.ai-guide.edit', [
            'guide' => AIGuide::current(),
        ]);
    }

    public function update(Request $request)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'global_instruction' => ['nullable', 'string'],
            'term_mappings_json' => ['nullable', 'string'],
            'column_aliases_json' => ['nullable', 'string'],
            'metric_formulas_json' => ['nullable', 'string'],
        ]);

        $guide = AIGuide::current();
        $guide->global_instruction = $data['global_instruction'] ?? null;
        $guide->term_mappings = $this->decodeJsonArray($data['term_mappings_json'] ?? '[]');
        $guide->column_aliases = $this->decodeJsonArray($data['column_aliases_json'] ?? '[]');
        $guide->metric_formulas = $this->decodeJsonArray($data['metric_formulas_json'] ?? '[]');
        $guide->save();

        return redirect()
            ->route('admin.ai-guide.edit')
            ->with('success', 'AI Guide updated successfully.');
    }

    private function decodeJsonArray(string $value): array
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            return [];
        }

        $decoded = json_decode($trimmed, true);
        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_filter($decoded, static fn ($item) => is_array($item)));
    }
}

