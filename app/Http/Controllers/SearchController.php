<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    
 /**
     * Display search results.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));

        $products = Product::query()
            ->with([
                'category',
                'collections',
            ])
            ->where('is_active', true)

            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    // Product fields
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")

                        // Category
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', "%{$search}%");
                        })

                        // Collection
                        ->orWhereHas('collections', function ($collectionQuery) use ($search) {
                            $collectionQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })

            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Site/Search', [
            'search' => $search,
            'products' => $products,
        ]);
    }
}
