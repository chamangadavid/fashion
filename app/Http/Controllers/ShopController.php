<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Category;
use Inertia\Inertia;

class ShopController extends Controller
{

 /**
     * Display products belonging to a category.
     */
    public function category(Request $request, $slug)
    {
        /*
        |--------------------------------------------------------------------------
        | FIND CATEGORY
        |--------------------------------------------------------------------------
        */

        $category = ProductCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        $products = $category->products()
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | RETURN PAGE
        |--------------------------------------------------------------------------
        */

        return Inertia::render('Site/Shop/Category', [
            'category' => $category,
            'products' => $products,
        ]);
    }
    
    
    // public function category(Request $request, $slug)
    // {
    //     $category = Category::where('slug', $slug)
    //         ->where('is_active', true)
    //         ->firstOrFail();

    //     $products = $category->products()
    //         ->where('is_active', true)
    //         ->where('stock', '>', 0)
    //         ->with('images')
    //         ->latest()
    //         ->paginate(12)
    //         ->withQueryString();

    //     return Inertia::render('Site/Shop/ShopDetails', [
    //         'category' => $category,
    //         'products' => $products,
    //     ]);
    // }
}
