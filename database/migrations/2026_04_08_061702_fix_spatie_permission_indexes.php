<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        $permissionIndex = 'permissions_name_guard_name_unique';
        $rolesIndex = 'roles_name_guard_name_unique';

        // Shorten the columns to avoid MySQL 1071 key-length errors on the composite unique index.
        DB::statement("ALTER TABLE `permissions` MODIFY `name` VARCHAR(125) NOT NULL");
        DB::statement("ALTER TABLE `permissions` MODIFY `guard_name` VARCHAR(125) NOT NULL");

        // Drop the old unique index if it exists, then recreate with prefix lengths.
        $indexExists = DB::selectOne("
            SELECT COUNT(*) AS c
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'permissions'
              AND index_name = ?
        ", [$permissionIndex]);

        if ($indexExists && (int) $indexExists->c > 0) {
            DB::statement("ALTER TABLE `permissions` DROP INDEX `{$permissionIndex}`");
        }

        DB::statement("
            ALTER TABLE `permissions`
            ADD UNIQUE `{$permissionIndex}` (`name`(125), `guard_name`(125))
        ");

        if (!Schema::hasTable('roles')) {
            return;
        }

        DB::statement("ALTER TABLE `roles` MODIFY `name` VARCHAR(125) NOT NULL");
        DB::statement("ALTER TABLE `roles` MODIFY `guard_name` VARCHAR(125) NOT NULL");

        $rolesIndexExists = DB::selectOne("
            SELECT COUNT(*) AS c
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'roles'
              AND index_name = ?
        ", [$rolesIndex]);

        if ($rolesIndexExists && (int) $rolesIndexExists->c > 0) {
            DB::statement("ALTER TABLE `roles` DROP INDEX `{$rolesIndex}`");
        }

        DB::statement("
            ALTER TABLE `roles`
            ADD UNIQUE `{$rolesIndex}` (`name`(125), `guard_name`(125))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is intended as a forward-only repair for MySQL key-length issues.
    }
};
