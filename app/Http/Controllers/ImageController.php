<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function destroy(Image $image)
    {
        if (auth()->id() !== $image->listing->user_id) abort(403);
        Storage::disk('public')->delete($image->file_path);
        $image->delete();
        return back();
    }
}
