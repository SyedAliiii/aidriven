<x-app-layout>
<div class="py-10 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">AI Guide</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Teach the AI your business language — terms, column names, and metric formulas — before it generates SQL.</p>
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

    <form method="POST" action="{{ route('admin.ai-guide.update') }}" class="space-y-4">
        @csrf
        @method('PATCH')

        {{-- Global Instruction --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2.5">
                <div class="h-6 w-6 rounded-md bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Global Instruction</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Business flow, rules, and context for the AI</p>
                </div>
            </div>
            <div class="p-5">
                <textarea id="global_instruction" name="global_instruction" rows="5"
                    placeholder="Explain business flow, priority rules, and how the AI should interpret your data..."
                    class="block w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none leading-relaxed">{{ old('global_instruction', $guide->global_instruction) }}</textarea>
            </div>
        </div>

        {{-- Term Mappings --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="h-6 w-6 rounded-md bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-violet-600 dark:text-violet-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Term Mappings</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Map plain English terms to SQL conditions</p>
                    </div>
                </div>
                <code class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 px-2 py-0.5 rounded font-mono">JSON</code>
            </div>
            <div class="p-5">
                <p class="mb-2 text-xs text-gray-400 dark:text-gray-500 font-mono bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-700 rounded-md px-3 py-2">
                    [{"term": "executed sale", "meaning": "status = 'executed'"}]
                </p>
                <textarea id="term_mappings_json" name="term_mappings_json" rows="6"
                    class="block w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-xs text-gray-900 dark:text-gray-100 font-mono px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none leading-relaxed">{{ old('term_mappings_json', json_encode($guide->term_mappings ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
            </div>
        </div>

        {{-- Column Aliases --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="h-6 w-6 rounded-md bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-sky-600 dark:text-sky-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" />
                            <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" />
                            <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Column Aliases</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Friendly names to actual table columns</p>
                    </div>
                </div>
                <code class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 px-2 py-0.5 rounded font-mono">JSON</code>
            </div>
            <div class="p-5">
                <p class="mb-2 text-xs text-gray-400 dark:text-gray-500 font-mono bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-700 rounded-md px-3 py-2">
                    [{"alias": "sale date", "column": "sales.sale_date"}]
                </p>
                <textarea id="column_aliases_json" name="column_aliases_json" rows="6"
                    class="block w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-xs text-gray-900 dark:text-gray-100 font-mono px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none leading-relaxed">{{ old('column_aliases_json', json_encode($guide->column_aliases ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
            </div>
        </div>

        {{-- Metric Formulas --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="h-6 w-6 rounded-md bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600 dark:text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v6a1 1 0 102 0V8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Metric Formulas</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500">KPI definitions and aggregation logic</p>
                    </div>
                </div>
                <code class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 px-2 py-0.5 rounded font-mono">JSON</code>
            </div>
            <div class="p-5">
                <p class="mb-2 text-xs text-gray-400 dark:text-gray-500 font-mono bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-700 rounded-md px-3 py-2">
                    [{"metric": "total executed sale", "formula": "SUM(CASE WHEN status='executed' THEN amount ELSE 0 END)"}]
                </p>
                <textarea id="metric_formulas_json" name="metric_formulas_json" rows="7"
                    class="block w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-xs text-gray-900 dark:text-gray-100 font-mono px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none leading-relaxed">{{ old('metric_formulas_json', json_encode($guide->metric_formulas ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
            </div>
        </div>

        {{-- Save --}}
        <div class="flex justify-end pt-1">
            <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293z" />
                </svg>
                Save AI Guide
            </button>
        </div>
    </form>

</div>
</x-app-layout>