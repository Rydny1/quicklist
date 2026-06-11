<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password']; // never expose the password in arrays/json

    // 'hashed' cast means any password we set gets bcrypt-hashed automatically
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // a user can post many listings
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    // quick helper used all over the app to gate admin features
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}