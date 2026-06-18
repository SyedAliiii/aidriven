<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AISettings extends Model
{
    use HasFactory;

    protected $table = 'ai_settings';

    protected $fillable = [
        'provider',
        'gemini_model',
        'openai_model',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'provider' => 'gemini',
                'gemini_model' => config('gemini.model', 'gemini-2.5-flash'),
                'openai_model' => config('openai.model', 'gpt-4.1-mini'),
            ],
        );
    }
}
