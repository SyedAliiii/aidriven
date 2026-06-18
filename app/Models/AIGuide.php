<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIGuide extends Model
{
    use HasFactory;

    protected $table = 'ai_guides';

    protected $fillable = [
        'global_instruction',
        'term_mappings',
        'column_aliases',
        'metric_formulas',
    ];

    protected $casts = [
        'term_mappings' => 'array',
        'column_aliases' => 'array',
        'metric_formulas' => 'array',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'global_instruction' => "Use business definitions strictly and prefer exact mapped columns/formulas.\n"
                    . "If term mappings conflict with schema, prioritize mapped definitions.",
                'term_mappings' => [
                    ['term' => 'executed sale', 'meaning' => "status = 'executed'"],
                    ['term' => 'non executed', 'meaning' => "status != 'executed'"],
                    ['term' => 'absent tso', 'meaning' => "attendance_status = 'absent'"],
                ],
                'column_aliases' => [
                    ['alias' => 'sale date', 'column' => 'sales.sale_date'],
                    ['alias' => 'tso name', 'column' => 'users.name'],
                ],
                'metric_formulas' => [
                    ['metric' => 'total executed sale', 'formula' => "SUM(CASE WHEN status='executed' THEN amount ELSE 0 END)"],
                ],
            ],
        );
    }
}
