<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['file_path', 'listing_id'];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
