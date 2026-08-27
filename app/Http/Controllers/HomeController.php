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
            ->take(6)
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
            ->take(6)
            ->get();

        return Inertia::render('Welcome', [
            'quickShopCategories' => $quickShopCategories,

             'productCategories' => ProductCategory::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get([
                    'id',
                    'name',
                    'slug',
                    'group',
                    'image',
                ]),


            'newArrivals' => $newArrivals,

            'featuredProducts' => $featuredProducts,

        ]);
    }
}
