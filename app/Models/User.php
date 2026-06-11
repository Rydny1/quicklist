<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password']; // never include the password hash in JSON responses

    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Laravel auto-bcrypts it when saving
        ];
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
