<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Collection;
use Inertia\Inertia;


class HomeController extends Controller
{
    
public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Quick Shop Categories
        |--------------------------------------------------------------------------
        */

        $quickShopCategories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get([
                'id',
                'name',
                'slug',
                'group',
                'image',
            ]);


        /*
        |--------------------------------------------------------------------------
        | New Arrivals
        |--------------------------------------------------------------------------
        */

        $newArrivals = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_new_arrival', true)
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Featured Products
        |--------------------------------------------------------------------------
        */

        $featuredProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Denim
        |--------------------------------------------------------------------------
        */

        // $denimProducts = Product::query()
        //     ->with('category')
        //     ->where('is_active', true)
        //     ->whereHas('category', function ($query) {
        //         $query->where('slug', 'denim');
        //     })
        //     ->latest()
        //     ->take(8)
        //     ->get();


        /*
        |--------------------------------------------------------------------------
        | Blouses / Tops
        |--------------------------------------------------------------------------
        */

        // $blouseProducts = Product::query()
        //     ->with('category')
        //     ->where('is_active', true)
        //     ->whereHas('category', function ($query) {
        //         $query->whereIn('slug', [
        //             'tops',
        //             'blouses',
        //         ]);
        //     })
        //     ->latest()
        //     ->take(8)
        //     ->get();


        /*
        |--------------------------------------------------------------------------
        | Bottoms
        |--------------------------------------------------------------------------
        */

        // $bottomsProducts = Product::query()
        //     ->with('category')
        //     ->where('is_active', true)
        //     ->whereHas('category', function ($query) {
        //         $query->whereIn('slug', [
        //             'bottoms',
        //             'skirts',
        //         ]);
        //     })
        //     ->latest()
        //     ->take(8)
        //     ->get();


        return Inertia::render('Welcome', [
            'quickShopCategories' => $quickShopCategories,

            'newArrivals' => $newArrivals,

            'featuredProducts' => $featuredProducts,

            // 'denimProducts' => $denimProducts,

            // 'blouseProducts' => $blouseProducts,

            // 'bottomsProducts' => $bottomsProducts,
        ]);
    }
}
