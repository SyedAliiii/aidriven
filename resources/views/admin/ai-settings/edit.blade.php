<x-app-layout>
<div class="py-10 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">AI Settings</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure which AI model generates SQL for all users.</p>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800/40 dark:bg-green-900/20 dark:text-green-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Errors --}}
    @if ($errors->any())
        <div class="mb-5 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800/40 dark:bg-red-900/20 dark:text-red-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <ul class="space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.ai-settings.update') }}">
        @csrf
        @method('PATCH')

        {{-- Active Provider --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm mb-4">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Provider</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-3">

                {{-- Gemini Card --}}
                <label class="relative flex items-start gap-4 p-4 rounded-lg border cursor-pointer transition
                    {{ old('provider', $settings->provider) === 'gemini'
                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-500'
                        : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <input type="radio" name="provider" value="gemini"
                        @checked(old('provider', $settings->provider) === 'gemini')
                        class="mt-0.5 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Gemini</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Google DeepMind</p>
                    </div>
                    <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-medium
                        {{ old('provider', $settings->provider) === 'gemini'
                            ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300'
                            : 'bg-gray-100 dark:bg-gray-800 text-gray-400' }}">
                        {{ old('provider', $settings->provider) === 'gemini' ? 'Active' : 'Inactive' }}
                    </span>
                </label>

                {{-- OpenAI Card --}}
                <label class="relative flex items-start gap-4 p-4 rounded-lg border cursor-pointer transition
                    {{ old('provider', $settings->provider) === 'openai'
                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-500'
                        : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <input type="radio" name="provider" value="openai"
                        @checked(old('provider', $settings->provider) === 'openai')
                        class="mt-0.5 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">OpenAI</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">GPT models</p>
                    </div>
                    <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-medium
                        {{ old('provider', $settings->provider) === 'openai'
                            ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300'
                            : 'bg-gray-100 dark:bg-gray-800 text-gray-400' }}">
                        {{ old('provider', $settings->provider) === 'openai' ? 'Active' : 'Inactive' }}
                    </span>
                </label>

            </div>
        </div>

        {{-- Model Configuration --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Model Configuration</h2>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Gemini Model --}}
                <div class="flex flex-col gap-1.5">
                    <label for="gemini_model" class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Gemini Model</label>
                    <input id="gemini_model" name="gemini_model" type="text"
                        value="{{ old('gemini_model', $settings->gemini_model) }}"
                        placeholder="gemini-2.5-flash"
                        class="h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Uses <code class="font-mono bg-gray-100 dark:bg-gray-800 px-1 rounded">GEMINI_API_KEY</code> from <code class="font-mono bg-gray-100 dark:bg-gray-800 px-1 rounded">.env</code>
                    </p>
                </div>

                {{-- OpenAI Model --}}
                <div class="flex flex-col gap-1.5">
                    <label for="openai_model" class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">OpenAI Model</label>
                    <input id="openai_model" name="openai_model" type="text"
                        value="{{ old('openai_model', $settings->openai_model) }}"
                        placeholder="gpt-4.1-mini"
                        class="h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        Uses <code class="font-mono bg-gray-100 dark:bg-gray-800 px-1 rounded">OPENAI_API_KEY</code> from <code class="font-mono bg-gray-100 dark:bg-gray-800 px-1 rounded">.env</code>
                    </p>
                </div>

            </div>
        </div>

        {{-- Save --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293z" />
                </svg>
                Save Settings
            </button>
        </div>
    </form>

</div>
</x-app-layout>