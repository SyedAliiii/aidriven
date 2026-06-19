<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'session_id',
        'question',
        'sql',
        'ai_response',
        'row_count',
        'result_columns',
        'visualization_type',
        'visualization_data',
    ];

    protected $casts = [
        'result_columns' => 'array',
        'visualization_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function session()
    {
        return $this->belongsTo(AnalyticsSession::class, 'session_id');
    }
}
