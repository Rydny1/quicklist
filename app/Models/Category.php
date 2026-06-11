<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // categories are seeded once and never change, so no created_at/updated_at needed
    public $timestamps = false;

    protected $fillable = ['name', 'slug'];

    // one category has many listings
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}