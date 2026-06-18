<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsQuery;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        $isSuperAdmin = method_exists($user, 'hasRole') && $user->hasRole('super-admin');
        $organization = !$isSuperAdmin ? $user->organization : null;

        $days = $this->lastNDates(14);

        if ($isSuperAdmin) {
            $kpis = [
                'total_users' => User::query()->count(),
                'total_organizations' => Organization::query()->count(),
                'active_organizations' => Organization::query()->where('status', 'active')->count(),
                'total_queries' => AnalyticsQuery::query()->count(),
            ];

            $trendRaw = AnalyticsQuery::query()
                ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(13)->startOfDay())
                ->groupBy('day')
                ->pluck('total', 'day')
                ->toArray();

            $trendChart = [
                'labels' => array_map(static fn ($d) => Carbon::parse($d)->format('d M'), $days),
                'values' => array_map(static fn ($d) => (int) ($trendRaw[$d] ?? 0), $days),
            ];

            $byOrg = AnalyticsQuery::query()
                ->leftJoin('organizations', 'organizations.id', '=', 'analytics_queries.organization_id')
                ->selectRaw('COALESCE(organizations.name, "Unknown") as name, COUNT(*) as total')
                ->groupBy('name')
                ->orderByDesc('total')
                ->limit(8)
                ->get();

            $breakdownChart = [
                'labels' => $byOrg->pluck('name')->values()->all(),
                'values' => $byOrg->pluck('total')->map(static fn ($v) => (int) $v)->values()->all(),
            ];

            $recentQueries = AnalyticsQuery::query()
                ->with(['user:id,name', 'organization:id,name'])
                ->latest('id')
                ->limit(10)
                ->get();
        } else {
            $orgId = $user->organization_id;

            $orgQuery = AnalyticsQuery::query()->where('organization_id', $orgId);
            $myQuery = AnalyticsQuery::query()->where('user_id', $user->id);

            $kpis = [
                'my_queries' => $myQuery->count(),
                'org_queries' => $orgQuery->count(),
                'avg_rows' => (int) round((float) ($orgQuery->avg('row_count') ?? 0)),
                'org_users' => User::query()->where('organization_id', $orgId)->count(),
            ];

            $trendRaw = AnalyticsQuery::query()
                ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
                ->where('organization_id', $orgId)
                ->where('created_at', '>=', now()->subDays(13)->startOfDay())
                ->groupBy('day')
                ->pluck('total', 'day')
                ->toArray();

            $trendChart = [
                'labels' => array_map(static fn ($d) => Carbon::parse($d)->format('d M'), $days),
                'values' => array_map(static fn ($d) => (int) ($trendRaw[$d] ?? 0), $days),
            ];

            $byUser = AnalyticsQuery::query()
                ->leftJoin('users', 'users.id', '=', 'analytics_queries.user_id')
                ->where('analytics_queries.organization_id', $orgId)
                ->selectRaw('COALESCE(users.name, "Unknown") as name, COUNT(*) as total')
                ->groupBy('name')
                ->orderByDesc('total')
                ->limit(8)
                ->get();

            $breakdownChart = [
                'labels' => $byUser->pluck('name')->values()->all(),
                'values' => $byUser->pluck('total')->map(static fn ($v) => (int) $v)->values()->all(),
            ];

            $recentQueries = AnalyticsQuery::query()
                ->with(['user:id,name', 'organization:id,name'])
                ->where('organization_id', $orgId)
                ->latest('id')
                ->limit(10)
                ->get();
        }

        return view('dashboard', [
            'isSuperAdmin' => $isSuperAdmin,
            'organization' => $organization,
            'kpis' => $kpis,
            'trendChart' => $trendChart,
            'breakdownChart' => $breakdownChart,
            'recentQueries' => $recentQueries,
        ]);
    }

    private function lastNDates(int $n): array
    {
        $dates = [];
        for ($i = $n - 1; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->toDateString();
        }

        return $dates;
    }
}
