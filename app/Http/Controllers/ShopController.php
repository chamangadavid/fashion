<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Inertia;

class ShopController extends Controller
{
    
    public function category(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $category->products()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->with('images')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Site/Shop/ShopDetails', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
