<?php

namespace App\Http\Controllers;

use App\Models\AISettings;
use Illuminate\Http\Request;

class AISettingsController extends Controller
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

        $settings = AISettings::current();

        return view('admin.ai-settings.edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'provider' => ['required', 'in:gemini,openai'],
            'gemini_model' => ['nullable', 'string', 'max:255'],
            'openai_model' => ['nullable', 'string', 'max:255'],
        ]);

        $settings = AISettings::current();
        $settings->fill($data);
        $settings->save();

        return redirect()
            ->route('admin.ai-settings.edit')
            ->with('success', 'AI settings updated successfully.');
    }
}

