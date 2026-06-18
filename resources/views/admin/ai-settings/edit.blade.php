<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    AI Provider Settings
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    Choose which AI model (Gemini or GPT) is used to generate SQL for all users.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 dark:bg-green-900/40 dark:border-green-900/70 dark:text-green-100">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800 dark:bg-red-900/40 dark:border-red-900/70 dark:text-red-100">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg shadow-sm">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.ai-settings.update') }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Active Provider
                            </label>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                This controls which backend (Gemini or GPT) is used for all analytics queries.
                            </p>
                            <div class="mt-3 flex gap-4">
                                <label class="inline-flex items-center gap-2 text-sm text-gray-800 dark:text-gray-100">
                                    <input type="radio" name="provider" value="gemini"
                                           @checked(old('provider', $settings->provider) === 'gemini')
                                           class="border-gray-300 dark:border-gray-700 text-gray-900 focus:ring-gray-900 dark:bg-gray-900 dark:text-gray-100">
                                    <span>Gemini</span>
                                </label>
                                <label class="inline-flex items-center gap-2 text-sm text-gray-800 dark:text-gray-100">
                                    <input type="radio" name="provider" value="openai"
                                           @checked(old('provider', $settings->provider) === 'openai')
                                           class="border-gray-300 dark:border-gray-700 text-gray-900 focus:ring-gray-900 dark:bg-gray-900 dark:text-gray-100">
                                    <span>OpenAI (GPT)</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="gemini_model" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Gemini model
                                </label>
                                <input id="gemini_model" name="gemini_model" type="text"
                                       value="{{ old('gemini_model', $settings->gemini_model) }}"
                                       placeholder="e.g. gemini-2.5-flash"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    API key is read from <code>GEMINI_API_KEY</code> in your <code>.env</code>.
                                </p>
                            </div>

                            <div>
                                <label for="openai_model" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    OpenAI (GPT) model
                                </label>
                                <input id="openai_model" name="openai_model" type="text"
                                       value="{{ old('openai_model', $settings->openai_model) }}"
                                       placeholder="e.g. gpt-4.1-mini"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    API key is read from <code>OPENAI_API_KEY</code> in your <code>.env</code>.
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 hover:opacity-90 transition text-sm">
                                Save settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

