<?php

namespace App\Http\Controllers;

use App\Models\Category;

// Shows all listings that belong to one category
class CategoryController extends Controller
{
    public function show(Category $category)
    {
        // grab only this category's listings, newest first
        $listings = $category->listings()->with(['user', 'images'])->latest()->paginate(12);
        // pass $categories too - the index view's filter dropdown loops over it
        $categories = Category::all();
        return view('listings.index', compact('listings', 'categories', 'category'));
    }
}