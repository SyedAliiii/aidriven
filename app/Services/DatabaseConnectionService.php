<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DatabaseConnectionService
{
    /**
     * Creates/refreshes a runtime connection named `dynamic_snd`.
     */
    public function connectForOrganization(Organization $organization): void
    {
        $base = config('database.connections.mysql', []);

        Config::set('database.connections.dynamic_snd', array_filter([
            'driver' => 'mysql',
            'host' => $organization->db_host,
            'port' => $organization->db_port ?? 3306,
            'database' => $organization->db_database,
            'username' => $organization->db_username,
            'password' => $organization->decryptedDbPassword(),
            'charset' => $base['charset'] ?? 'utf8mb4',
            'collation' => $base['collation'] ?? 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => $base['strict'] ?? true,
            'engine' => $base['engine'] ?? null,
            'options' => $base['options'] ?? [],
        ]));

        // Refresh the connection definition and any previously cached PDO instance.
        DB::purge('dynamic_snd');
        DB::connection('dynamic_snd')->getPdo();
    }

    /**
     * Tests whether dynamic_snd connection works for the organization.
     *
     * @return array{ok: bool, message: string}
     */
    public function testConnectionForOrganization(Organization $organization): array
    {
        try {
            $this->connectForOrganization($organization);

            // Execute a lightweight ping query.
            DB::connection('dynamic_snd')->select('SELECT 1 AS ping');

            return [
                'ok' => true,
                'message' => 'Database connection successful.',
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}

