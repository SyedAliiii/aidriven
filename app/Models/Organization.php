<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'db_host',
        'db_database',
        'db_username',
        'db_password',
        'db_port',
        'status',
    ];

    protected $hidden = [
        'db_password',
    ];

    protected $casts = [
        'db_port' => 'integer',
        'status' => 'string',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Decrypts the stored encrypted DB password.
     *
     * Note: `db_password` is expected to already be encrypted via `Crypt::encryptString`.
     */
    public function decryptedDbPassword(): string
    {
        if (blank($this->db_password)) {
            // Local/dev databases may intentionally use an empty password.
            return '';
        }

        return Crypt::decryptString($this->db_password);
    }
}
