<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    AI Guide (Business Knowledge)
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Define business terms, column aliases, and metric formulas so AI understands your domain language before generating SQL.
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

            <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
                <form method="POST" action="{{ route('admin.ai-guide.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="global_instruction" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Global Instruction
                        </label>
                        <textarea id="global_instruction" name="global_instruction" rows="5"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm"
                                  placeholder="Explain business flow, priority rules, and how AI should interpret data.">{{ old('global_instruction', $guide->global_instruction) }}</textarea>
                    </div>

                    <div>
                        <label for="term_mappings_json" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Term Mappings (JSON)
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Example: [{"term":"executed sale","meaning":"status = 'executed'"}]
                        </p>
                        <textarea id="term_mappings_json" name="term_mappings_json" rows="6"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-xs font-mono">{{ old('term_mappings_json', json_encode($guide->term_mappings ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                    </div>

                    <div>
                        <label for="column_aliases_json" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Column Aliases (JSON)
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Example: [{"alias":"sale date","column":"sales.sale_date"}]
                        </p>
                        <textarea id="column_aliases_json" name="column_aliases_json" rows="6"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-xs font-mono">{{ old('column_aliases_json', json_encode($guide->column_aliases ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                    </div>

                    <div>
                        <label for="metric_formulas_json" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Metric Formulas (JSON)
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Example: [{"metric":"total executed sale","formula":"SUM(CASE WHEN status='executed' THEN amount ELSE 0 END)"}]
                        </p>
                        <textarea id="metric_formulas_json" name="metric_formulas_json" rows="7"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-xs font-mono">{{ old('metric_formulas_json', json_encode($guide->metric_formulas ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 hover:opacity-90 transition text-sm">
                            Save AI Guide
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

