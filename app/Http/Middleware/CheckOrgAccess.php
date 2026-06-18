<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrgAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Authentication required.'], 403);
            }
            abort(403);
        }

        // Super Admins can manage/query all organizations.
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        $requestedOrgId = $request->input('organization_id');
        $userOrgId = $user->organization_id;

        if ($userOrgId === null || $requestedOrgId === null || (int) $requestedOrgId !== (int) $userOrgId) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You are not allowed to access this organization.'], 403);
            }
            abort(403);
        }

        // Basic SELECT-only validation for export requests.
        if ($request->has('sql')) {
            $sql = (string) $request->input('sql');
            if (!$this->isSelectOnlyQuery($sql)) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Only SELECT queries are allowed.'], 422);
                }
                abort(422, 'Only SELECT queries are allowed.');
            }
        }

        return $next($request);
    }

    private function isSelectOnlyQuery(string $sql): bool
    {
        $sql = trim($sql);
        if ($sql === '') {
            return false;
        }

        // Strip leading comments (up to a few blocks/lines).
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

        if (!preg_match('/^SELECT\b/i', $normalized)) {
            return false;
        }

        // Reject common DML/DDL keywords defensively.
        if (preg_match('/\b(DROP|DELETE|UPDATE|TRUNCATE|INSERT|ALTER|CREATE|REPLACE|RENAME|GRANT|REVOKE|BEGIN|COMMIT|ROLLBACK)\b/i', $normalized)) {
            return false;
        }

        // Basic multi-statement guard.
        if (substr_count($normalized, ';') > 1) {
            return false;
        }

        return true;
    }
}

