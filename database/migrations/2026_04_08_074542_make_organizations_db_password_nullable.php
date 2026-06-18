<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('organizations')) {
            return;
        }

        // Avoid doctrine/dbal by using a raw ALTER.
        DB::statement('ALTER TABLE `organizations` MODIFY `db_password` TEXT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('organizations')) {
            return;
        }

        DB::statement('ALTER TABLE `organizations` MODIFY `db_password` TEXT NOT NULL');
    }
};
