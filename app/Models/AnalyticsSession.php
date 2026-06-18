<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsSession extends Model
{
    protected $fillable = [
        'user_id',
        'organization_id',
        'title',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function queries()
    {
        return $this->hasMany(AnalyticsQuery::class, 'session_id');
    }
}
