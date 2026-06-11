<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $listings = $category->listings()->with(['user', 'images'])->latest()->paginate(12);
        $categories = Category::all();
        return view('listings.index', compact('listings', 'categories', 'category'));
    }
}
