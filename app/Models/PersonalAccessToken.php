<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;  
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PersonalAccessToken extends Model
{
    protected $connection = 'mongodb';   
    protected $collection = 'personal_access_tokens';

    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'abilities'   => 'array',
        'last_used_at'=> 'datetime',
        'expires_at'  => 'datetime',
    ];

    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }
}
