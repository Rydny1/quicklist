<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Category;

// This controller handles everything about the ads themselves:
// listing them, viewing one, creating, editing, deleting and searching.
class ListingController extends Controller
{
    // homepage - show all listings
    public function index()
    {
        // eager load category/images/user so we don't hit the N+1 problem in the grid
        $listings = Listing::with(['category', 'images', 'user'])->latest()->paginate(12);
        $categories = Category::all(); // needed for the filter dropdown
        return view('listings.index', compact('listings', 'categories'));
    }

    // single listing page
    public function show(Listing $listing)
    {
        // route-model binding already found the listing, just load its relations
        $listing->load(['category', 'images', 'user']);
        return view('listings.show', compact('listing'));
    }

    // show the "post an ad" form (categories fill the select box)
    public function create()
    {
        $categories = Category::all();
        return view('listings.create', compact('categories'));
    }

    // save a brand new listing to the database
    public function store(Request $request)
    {
        // basic validation - title/description/category are mandatory, each image max 5MB
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'images.*' => 'nullable|image|max:5120',
        ]);

        // create the listing through the relationship so user_id gets set automatically
        $listing = auth()->user()->listings()->create($request->only(
            'title', 'description', 'price', 'location', 'category_id'
        ));

        // save uploaded images one by one
        if ($request->hasFile('images')) {
            $dir = storage_path('app/public/listings');
            foreach ($request->file('images') as $img) {
                if ($img && $img->isValid()) {
                    // build a unique filename so two uploads never clash
                    $ext = $img->getClientOriginalExtension() ?: 'jpg';
                    $filename = time() . '_' . uniqid() . '.' . $ext;
                    // move() uses getPathname()/move_uploaded_file() and creates the
                    // target dir itself, avoiding getRealPath() which is empty under
                    // `php artisan serve` on Windows.
                    $img->move($dir, $filename);
                    // store the relative path in the images table, not the file itself
                    $listing->images()->create(['file_path' => 'listings/' . $filename]);
                }
            }
        }

        // send the user straight to their freshly created ad
        return redirect()->route('listings.show', $listing);
    }

    // show the edit form, but only the owner is allowed in
    public function edit(Listing $listing)
    {
        if (auth()->id() !== $listing->user_id) abort(403); // 403 = not your listing
        $categories = Category::all();
        return view('listings.edit', compact('listing', 'categories'));
    }

    // save the edited listing
    public function update(Request $request, Listing $listing)
    {
        if (auth()->id() !== $listing->user_id) abort(403); // again, owner check

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $listing->update($request->only(
            'title', 'description', 'price', 'location', 'category_id'
        ));

        // add new images if uploaded
        if ($request->hasFile('images')) {
            $dir = storage_path('app/public/listings');
            foreach ($request->file('images') as $img) {
                if ($img && $img->isValid()) {
                    $ext = $img->getClientOriginalExtension() ?: 'jpg';
                    $filename = time() . '_' . uniqid() . '.' . $ext;
                    $img->move($dir, $filename);
                    $listing->images()->create(['file_path' => 'listings/' . $filename]);
                }
            }
        }

        return redirect()->route('listings.show', $listing);
    }

    // soft delete - this doesn't really remove the row, just sets deleted_at
    // so an admin can restore it later from the admin panel
    public function destroy(Listing $listing)
    {
        // owner OR admin can delete
        if (auth()->id() !== $listing->user_id && !auth()->user()->isAdmin()) abort(403);
        $listing->delete();
        return redirect()->route('dashboard');
    }

    // search + filter results (all filters are optional)
    public function search(Request $request)
    {
        $query = Listing::with(['category', 'images']);

        // keyword matches either the title or the description.
        // wrapped in its own closure so the OR doesn't leak into the filters below
        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                  ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // price range filters
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // reuse the same index view so results look identical to the homepage
        $listings = $query->latest()->paginate(12);
        $categories = Category::all();
        return view('listings.index', compact('listings', 'categories'));
    }
}