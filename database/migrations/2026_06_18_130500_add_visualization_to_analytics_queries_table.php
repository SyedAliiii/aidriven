<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analytics_queries', function (Blueprint $table) {
            $table->string('visualization_type', 40)->default('table')->after('result_columns');
            $table->json('visualization_data')->nullable()->after('visualization_type');
        });
    }

    public function down(): void
    {
        Schema::table('analytics_queries', function (Blueprint $table) {
            $table->dropColumn(['visualization_type', 'visualization_data']);
        });
    }
};
