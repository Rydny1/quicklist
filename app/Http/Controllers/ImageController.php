<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

// Only job here is removing an image from a listing (used on the edit page)
class ImageController extends Controller
{
    public function destroy(Image $image)
    {
        // make sure the person owns the listing this image belongs to
        if (auth()->id() !== $image->listing->user_id) abort(403);
        Storage::disk('public')->delete($image->file_path); // delete the actual file
        $image->delete(); // then the db row
        return back();
    }
}