<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\PersonalAccessToken;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ✅ Mongo-safe version of Sanctum's createToken()
    public function createMongoToken(string $name = 'auth_token', array $abilities = ['*'])
    {
        $plainTextToken = Str::random(40);
        $hashedToken = hash('sha256', $plainTextToken);

        $token = PersonalAccessToken::create([
            'tokenable_type' => static::class,
            'tokenable_id'   => $this->_id,  // MongoDB ObjectId
            'name'           => $name,
            'token'          => $hashedToken,
            'abilities'      => $abilities,
        ]);

        return [
            'plainTextToken' => $token->getKey().'|'.$plainTextToken,
            'token'          => $token,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
