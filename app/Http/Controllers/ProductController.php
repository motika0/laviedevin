<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('country')) {
            $query->where('country', $request->country);
        }


        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::all();
        $countries = Product::where('is_active', true)->distinct()->pluck('country');

        return view('products.index', compact('products', 'categories', 'countries'));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'reviews' => function($q) {
            $q->where('is_approved', true)->with('user');
        }])->findOrFail($id);

        $relatedProducts = $product->getRelatedProducts(4);

        return view('products.show', compact('product', 'relatedProducts'));
    }


    public function sale()
    {
        $products = Product::where('is_active', true)
            ->whereNotNull('old_price')
            ->where('old_price', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('products.sale', compact('products'));
    }


    public function new()
    {
        $products = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('products.new', compact('products'));
    }


    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($products);
    }


    public function filters()
    {
        $categories = Category::withCount(['products' => function($q) {
            $q->where('is_active', true);
        }])->get();

        $countries = Product::where('is_active', true)
            ->distinct()
            ->pluck('country');

        $priceRange = [
            'min' => Product::where('is_active', true)->min('price'),
            'max' => Product::where('is_active', true)->max('price'),
        ];

        return view('products.filters', compact('categories', 'countries', 'priceRange'));
    }
}