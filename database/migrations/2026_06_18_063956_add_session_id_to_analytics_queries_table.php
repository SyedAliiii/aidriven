<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('analytics_queries', function (Blueprint $table) {
            $table->foreignId('session_id')->nullable()->after('organization_id')->constrained('analytics_sessions')->cascadeOnDelete();
        });

        // Migrate existing queries to have their own sessions
        $queries = \Illuminate\Support\Facades\DB::table('analytics_queries')->get();
        foreach ($queries as $query) {
            $sessionId = \Illuminate\Support\Facades\DB::table('analytics_sessions')->insertGetId([
                'user_id' => $query->user_id,
                'organization_id' => $query->organization_id,
                'title' => \Illuminate\Support\Str::limit($query->question, 60),
                'created_at' => $query->created_at,
                'updated_at' => $query->updated_at,
            ]);
            \Illuminate\Support\Facades\DB::table('analytics_queries')->where('id', $query->id)->update(['session_id' => $sessionId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics_queries', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn('session_id');
        });
    }
};
