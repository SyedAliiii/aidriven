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
        Schema::create('ai_guides', function (Blueprint $table) {
            $table->id();
            $table->text('global_instruction')->nullable();
            $table->json('term_mappings')->nullable();
            $table->json('column_aliases')->nullable();
            $table->json('metric_formulas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_guides');
    }
};
