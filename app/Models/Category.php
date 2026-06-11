<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // no timestamps, categories don't change
    public $timestamps = false;

    protected $fillable = ['name', 'slug'];

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}
