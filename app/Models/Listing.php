<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use SoftDeletes; // gives us deleted_at instead of really deleting rows

    // columns that are allowed to be mass-assigned (protects against extra form fields)
    protected $fillable = ['title', 'description', 'price', 'location', 'user_id', 'category_id'];

    // a listing belongs to the user who posted it
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // and to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // a listing can have many images
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}