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
        'question',
        'sql',
        'row_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
