<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

/**
 * @method bool can(string $ability, mixed $arguments = [])
 * @method bool hasRole(string|array $roles)
 * @method bool assignRole(string|array $roles)
 * @method bool removeRole(string|array $roles)
 */
class User extends Authenticatable
{
    use HasRoles;
    protected $guard_name = 'web';

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'user_id');
    }
}
