<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Category;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::with(['category', 'images', 'user'])->latest()->paginate(12);
        $categories = Category::all();
        return view('listings.index', compact('listings', 'categories'));
    }

    public function show(Listing $listing)
    {
        $listing->load(['category', 'images', 'user']);
        return view('listings.show', compact('listing'));
    }

    public function create()
    {
        // blocked users aren't allowed to post, 403 = Forbidden
        if (auth()->user()->role === 'blocked') abort(403);
        $categories = Category::all();
        return view('listings.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // double-check here too because store() can be hit directly via POST
        if (auth()->user()->role === 'blocked') abort(403);

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'images.*' => 'nullable|image|max:5120',
        ]);

        // using the relationship so user_id is set automatically
        $listing = auth()->user()->listings()->create($request->only(
            'title', 'description', 'price', 'location', 'category_id'
        ));

        if ($request->hasFile('images')) {
            $dir = storage_path('app/public/listings');
            foreach ($request->file('images') as $img) {
                if ($img && $img->isValid()) {
                    $ext = $img->getClientOriginalExtension() ?: 'jpg';
                    $filename = time() . '_' . uniqid() . '.' . $ext;
                    // move() works better than storeAs() on Windows under artisan serve
                    $img->move($dir, $filename);
                    $listing->images()->create(['file_path' => 'listings/' . $filename]);
                }
            }
        }

        return redirect()->route('listings.show', $listing);
    }

    public function edit(Listing $listing)
    {
        // only the owner can edit their listing
        if (auth()->id() !== $listing->user_id) abort(403);
        $categories = Category::all();
        return view('listings.edit', compact('listing', 'categories'));
    }

    public function update(Request $request, Listing $listing)
    {
        if (auth()->id() !== $listing->user_id) abort(403); // same check as edit()

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $listing->update($request->only(
            'title', 'description', 'price', 'location', 'category_id'
        ));

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

    public function destroy(Listing $listing)
    {
        if (auth()->id() !== $listing->user_id && !auth()->user()->isAdmin()) abort(403);
        $listing->delete();
        // admin should stay on admin page, not get sent to user dashboard
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.index')
            : redirect()->route('dashboard');
    }

    public function search(Request $request)
    {
        $query = Listing::with(['category', 'images']);

        if ($request->keyword) {
            // closure so the OR doesn't mess with the other filters
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                  ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $listings = $query->latest()->paginate(12);
        $categories = Category::all();
        return view('listings.index', compact('listings', 'categories'));
    }
}
