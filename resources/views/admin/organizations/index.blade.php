<x-app-layout>
<style>
/* ── Mobile card view for table rows ── */
@media (max-width: 639px) {
    .org-table-wrap { display: none !important; }
    .org-cards { display: flex !important; }
}
@media (min-width: 640px) {
    .org-table-wrap { display: block !important; }
    .org-cards { display: none !important; }
}
.org-cards {
    flex-direction: column;
    gap: 0;
    display: none;
}
.org-card-row {
    padding: 14px 16px;
    border-bottom: 1px solid;
}
.org-card-row:last-child { border-bottom: none; }
</style>

    <div class="py-8 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page header --}}
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex items-start gap-3">
                    <div class="hidden sm:flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 17.25v-.228a4.5 4.5 0 0 0-.12-1.03l-2.268-9.64a3.375 3.375 0 0 0-3.285-2.602H7.923a3.375 3.375 0 0 0-3.285 2.602l-2.268 9.64a4.5 4.5 0 0 0-.12 1.03v.228m19.5 0a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3m19.5 0v.75A2.25 2.25 0 0 1 19.5 21h-15a2.25 2.25 0 0 1-2.25-2.25v-.75m19.5 0h-19.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Admin</p>
                        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-gray-100">Organizations</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                            Manage the organizations connected to DataQuery AI and their database credentials.
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.organizations.create') }}"
                    class="inline-flex items-center gap-1.5 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add organization
                </a>
            </div>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mt-5 flex items-start gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900/60 dark:bg-green-900/30 dark:text-green-200">
                    <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mt-5 flex items-start gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-900/30 dark:text-red-200">
                    <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Search + status filter --}}
            <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <form method="GET" class="relative w-full sm:max-w-xs">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search organizations..."
                        class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
                </form>

                <div class="flex items-center gap-2 flex-wrap">
                    @php($currentStatus = request('status', 'all'))
                    @foreach(['all' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                        <a href="{{ request()->fullUrlWithQuery(['status' => $value === 'all' ? null : $value]) }}"
                            class="rounded-full px-3 py-1 text-xs font-medium transition
                                {{ $currentStatus === $value
                                    ? 'bg-indigo-600 text-white'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Table / empty state --}}
            <div class="mt-4 overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                @if($organizations->isEmpty())
                    <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 17.25v-.228a4.5 4.5 0 0 0-.12-1.03l-2.268-9.64a3.375 3.375 0 0 0-3.285-2.602H7.923a3.375 3.375 0 0 0-3.285 2.602l-2.268 9.64a4.5 4.5 0 0 0-.12 1.03v.228m19.5 0a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3m19.5 0v.75A2.25 2.25 0 0 1 19.5 21h-15a2.25 2.25 0 0 1-2.25-2.25v-.75m19.5 0h-19.5" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-gray-100">No organizations yet</h3>
                        <p class="mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
                            Add an organization to connect its database and start querying it with AI.
                        </p>
                        <a href="{{ route('admin.organizations.create') }}"
                            class="mt-5 inline-flex items-center gap-1.5 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add organization
                        </a>
                    </div>
                @else

                    {{-- ══ DESKTOP TABLE (sm+) ══ --}}
                    <div class="org-table-wrap overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                            <thead class="bg-gray-50 dark:bg-gray-800/60">
                                <tr>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Organization</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Database connection</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                                    <th class="px-4 py-3 text-right text-[11px] font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @foreach($organizations as $org)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                                    {{ strtoupper(substr($org->name, 0, 2)) }}
                                                </div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $org->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $org->db_database }}</div>
                                            <div class="font-mono text-xs text-gray-500 dark:text-gray-400">{{ $org->db_host }}:{{ $org->db_port }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($org->status === 'active')
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-200">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="inline-flex items-center gap-1">
                                                <a href="{{ route('admin.organizations.edit', $org) }}" title="Edit"
                                                    class="rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-100 transition">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                                    </svg>
                                                </a>
                                                <form method="POST" action="{{ route('admin.organizations.test-connection', $org) }}">
                                                    @csrf
                                                    <button type="submit" title="Test connection"
                                                        class="rounded-md p-2 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-500/10 transition">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5 14.25 2.25 12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.organizations.destroy', $org) }}"
                                                    onsubmit="return confirm('Delete this organization?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Delete"
                                                        class="rounded-md p-2 text-red-500 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10 transition">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- ══ MOBILE CARDS (< sm) ══ --}}
                    <div class="org-cards">
                        @foreach($organizations as $org)
                            <div class="org-card-row border-gray-100 dark:border-gray-800">

                                {{-- Top row: avatar + name + status --}}
                                <div class="flex items-center justify-between gap-3 mb-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                            {{ strtoupper(substr($org->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $org->name }}</span>
                                    </div>
                                    @if($org->status === 'active')
                                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactive
                                        </span>
                                    @endif
                                </div>

                                {{-- DB info --}}
                                <div class="mb-3 rounded-lg bg-gray-50 dark:bg-gray-800/60 px-3 py-2">
                                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Database</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $org->db_database }}</p>
                                    <p class="font-mono text-xs text-gray-500 dark:text-gray-400">{{ $org->db_host }}:{{ $org->db_port }}</p>
                                </div>

                                {{-- Actions row --}}
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.organizations.edit', $org) }}"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-md border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                        </svg>
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.organizations.test-connection', $org) }}" class="flex-1">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-1.5 rounded-md border border-indigo-200 dark:border-indigo-800 px-3 py-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5 14.25 2.25 12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                                            </svg>
                                            Test
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.organizations.destroy', $org) }}"
                                        onsubmit="return confirm('Delete this organization?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center rounded-md border border-red-200 dark:border-red-900/50 p-1.5 text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>

                @endif
            </div>

            {{-- Pagination --}}
            @if(!$organizations->isEmpty() && method_exists($organizations, 'total'))
                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-gray-500 dark:text-gray-400">
                    <span>Showing {{ $organizations->firstItem() }}–{{ $organizations->lastItem() }} of {{ $organizations->total() }}</span>
                    <div>{{ $organizations->links() }}</div>
                </div>
            @else
                <div class="mt-4">{{ $organizations->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>