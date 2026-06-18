<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Idempotent dev seed: create if missing, otherwise reuse.
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        $role = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $user->assignRole($role);

        // Create a default dev organization so the dashboard isn't empty.
        // (Uses the same DB as the app, so schema introspection can work immediately.)
        $organization = Organization::updateOrCreate(
            ['name' => 'Default Org (dev)'],
            [
                'db_host' => env('DB_HOST', '127.0.0.1'),
                'db_database' => env('DB_DATABASE', 'aidriven'),
                'db_username' => env('DB_USERNAME', 'root'),
                'db_password' => Crypt::encryptString((string) env('DB_PASSWORD', '')),
                'db_port' => (int) env('DB_PORT', 3306),
                'status' => 'active',
            ]
        );

        $user->organization_id = $organization->id;
        $user->save();

        // Add an example organization-scoped user (non-super-admin).
        // This makes it easy to verify tenant scoping + the analytics badge.
        User::updateOrCreate(
            ['email' => 'org-user@example.com'],
            [
                'name' => 'Org User',
                'password' => Hash::make('password'),
                'organization_id' => $organization->id,
            ]
        );
    }
}
