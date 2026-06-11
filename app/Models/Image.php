<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    // we only store the path to the file, the image itself lives in storage
    protected $fillable = ['file_path', 'listing_id'];

    // each image belongs to one listing
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}