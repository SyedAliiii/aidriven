<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $isSuperAdmin ? 'Super Admin Dashboard' : 'Organization Dashboard' }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ $isSuperAdmin ? 'Monitor platform usage, users, organizations, and analytics activity.' : 'Monitor your organization analytics usage and recent activity.' }}
                    </p>
                </div>
                <a href="{{ route('dashboard.analytics') }}"
                   class="inline-flex items-center px-4 py-2 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm hover:opacity-90 transition">
                    Open Analytics
                </a>
            </div>

            @if(!$isSuperAdmin && $organization)
                <div class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-900 px-3 py-1 text-xs text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                    {{ $organization->name }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                @if($isSuperAdmin)
                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Total Users</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $kpis['total_users'] }}</div>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Organizations</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $kpis['total_organizations'] }}</div>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Active Organizations</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $kpis['active_organizations'] }}</div>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Total Queries</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $kpis['total_queries'] }}</div>
                    </div>
                @else
                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400">My Queries</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $kpis['my_queries'] }}</div>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Org Queries</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $kpis['org_queries'] }}</div>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Average Result Rows</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $kpis['avg_rows'] }}</div>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Organization Users</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $kpis['org_users'] }}</div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
                <div class="xl:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        Queries Trend (Last 14 Days)
                    </h2>
                    <div class="mt-3 h-72">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $isSuperAdmin ? 'Queries by Organization' : 'Queries by User (Org)' }}
                    </h2>
                    <div class="mt-3 h-72">
                        <canvas id="breakdownChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        Recent Query Activity
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Question</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Organization</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rows</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($recentQueries as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ \Illuminate\Support\Str::limit($item->question, 90) }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $item->user?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $item->organization?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $item->row_count }}</td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ optional($item->created_at)->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No activity yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(75, 85, 99, 0.35)' : 'rgba(209, 213, 219, 0.8)';
            const textColor = isDark ? '#d1d5db' : '#4b5563';

            const trend = @json($trendChart);
            const breakdown = @json($breakdownChart);

            const trendCtx = document.getElementById('trendChart');
            if (trendCtx) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: trend.labels || [],
                        datasets: [{
                            label: 'Queries',
                            data: trend.values || [],
                            borderColor: '#111827',
                            backgroundColor: 'rgba(17, 24, 39, 0.15)',
                            fill: true,
                            tension: 0.35
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: { legend: { labels: { color: textColor } } },
                        scales: {
                            x: { ticks: { color: textColor }, grid: { color: gridColor } },
                            y: { ticks: { color: textColor }, grid: { color: gridColor }, beginAtZero: true }
                        }
                    }
                });
            }

            const breakdownCtx = document.getElementById('breakdownChart');
            if (breakdownCtx) {
                new Chart(breakdownCtx, {
                    type: 'doughnut',
                    data: {
                        labels: breakdown.labels || [],
                        datasets: [{
                            data: breakdown.values || [],
                            backgroundColor: ['#111827', '#374151', '#4b5563', '#6b7280', '#9ca3af', '#d1d5db', '#3b82f6', '#10b981'],
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: { legend: { labels: { color: textColor }, position: 'bottom' } }
                    }
                });
            }
        })();
    </script>
</x-app-layout>
