<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function show($id)
    {
        $category = Category::with('children')->findOrFail($id);
        
        $products = $category->getAllProducts();
        
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            $products->forPage(request()->get('page', 1), 12),
            $products->count(),
            12,
            request()->get('page', 1),
            ['path' => request()->url()]
        );

        return view('categories.show', compact('category', 'products'));
    }
}