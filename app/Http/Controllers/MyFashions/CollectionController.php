<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CollectionController extends Controller
{
    

/**
     * =========================================================
     * COLLECTION INDEX
     * =========================================================
     */
    public function index(Request $request)
    {
        $query = Collection::query()
            ->withCount('products');

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVE FILTER
        |--------------------------------------------------------------------------
        */
        if ($request->filled('active')) {

            $query->where(
                'is_active',
                $request->active
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FEATURED FILTER
        |--------------------------------------------------------------------------
        */
        if ($request->filled('featured')) {

            $query->where(
                'is_featured',
                $request->featured
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SORT
        |--------------------------------------------------------------------------
        */
        $query->orderBy('sort_order')
            ->orderByDesc('created_at');

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */
        $collections = $query
            ->paginate(15)
            ->withQueryString();

        return Inertia::render(
            'MyFashions/Collections/Index',
            [
                'collections' => $collections,

                'filters' => [
                    'search' => $request->search,
                    'active' => $request->active,
                    'featured' => $request->featured,
                ],
            ]
        );
    }


    /**
     * =========================================================
     * CREATE
     * =========================================================
     */
    public function create()
    {
        return Inertia::render(
            'MyFashions/Collections/Create'
        );
    }


    /**
     * =========================================================
     * STORE
     * =========================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */
        $slug = Str::slug($validated['name']);

        $originalSlug = $slug;

        $counter = 1;

        while (
            Collection::where('slug', $slug)->exists()
        ) {

            $slug = $originalSlug . '-' . $counter;

            $counter++;
        }


        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */
        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request
                ->file('image')
                ->store(
                    'collections',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE COLLECTION
        |--------------------------------------------------------------------------
        */
        $collection = Collection::create([

            'name' => $validated['name'],

            'slug' => $slug,

            'description' =>
                $validated['description'] ?? null,

            'image' => $imagePath,

            'is_active' =>
                $request->boolean(
                    'is_active',
                    true
                ),

            'is_featured' =>
                $request->boolean(
                    'is_featured'
                ),

            'sort_order' =>
                $validated['sort_order'] ?? 0,

        ]);


        return redirect()
            ->route('fashion.collections.index')
            ->with(
                'success',
                'Collection created successfully.'
            );
    }


    /**
     * =========================================================
     * SHOW
     * =========================================================
     */
    public function show(Collection $collection)
    {
        $collection->load([
            'products.category',
        ]);

        return Inertia::render(
            'MyFashions/Collections/View',
            [
                'collection' => $collection,
            ]
        );
    }


    /**
     * =========================================================
     * EDIT
     * =========================================================
    */
    public function edit(Collection $collection)
    {
        return Inertia::render(
            'MyFashions/Collections/Edit',
            [
                'collection' => $collection,
            ]
        );
    }


    /**
     * =========================================================
     * UPDATE
     * =========================================================
     */
    public function update(Request $request, Collection $collection) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */
        $slug = Str::slug(
            $validated['name']
        );

        $originalSlug = $slug;

        $counter = 1;

        while (
            Collection::where('slug', $slug)
                ->where(
                    'id',
                    '!=',
                    $collection->id
                )
                ->exists()
        ) {

            $slug =
                $originalSlug .
                '-' .
                $counter;

            $counter++;
        }


        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */
        $imagePath =
            $collection->image;


        if ($request->hasFile('image')) {

            /*
            | Delete old image
            */
            if (
                $collection->image &&
                Storage::disk('public')
                    ->exists(
                        $collection->image
                    )
            ) {

                Storage::disk('public')
                    ->delete(
                        $collection->image
                    );
            }


            /*
            | Store new image
            */
            $imagePath = $request
                ->file('image')
                ->store(
                    'collections',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */
        $collection->update([

            'name' =>
                $validated['name'],

            'slug' =>
                $slug,

            'description' =>
                $validated['description'] ?? null,

            'image' =>
                $imagePath,

            'is_active' =>
                $request->boolean(
                    'is_active'
                ),

            'is_featured' =>
                $request->boolean(
                    'is_featured'
                ),

            'sort_order' =>
                $validated['sort_order'] ?? 0,

        ]);


        return redirect()
            ->route(
                'fashion.collections.index'
            )
            ->with(
                'success',
                'Collection updated successfully.'
            );
    }


    /**
     * =========================================================
     * DELETE
     * =========================================================
     */
    public function destroy(Collection $collection) {

        /*
        |--------------------------------------------------------------------------
        | DELETE IMAGE
        |--------------------------------------------------------------------------
        */
        if (
            $collection->image &&
            Storage::disk('public')
                ->exists(
                    $collection->image
                )
        ) {

            Storage::disk('public')
                ->delete(
                    $collection->image
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DETACH PRODUCTS
        |--------------------------------------------------------------------------
        */
        $collection->products()->detach();


        /*
        |--------------------------------------------------------------------------
        | DELETE COLLECTION
        |--------------------------------------------------------------------------
        */
        $collection->delete();


        return redirect()
            ->route(
                'fashion.collections.index'
            )
            ->with(
                'success',
                'Collection deleted successfully.'
            );
    }


    /**
     * =========================================================
     * FEATURED COLLECTIONS
     * =========================================================
     */
//     public function featured()
// {
//     $collections = Collection::withCount('products')
//         ->where('is_featured', true)
//         ->orderBy('sort_order')
//         ->orderBy('name')
//         ->get();

//     return Inertia::render(
//         'MyFashions/Collections/Featured',
//         [
//             'collections' => $collections,
//         ]
//     );
// }

    public function featured()
    {
        $collections = Collection::query()
            ->where('is_featured', true)
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render(
            'MyFashions/Collections/Featured',
            [
                'collections' => $collections,
            ]
        );
    }


    public function toggleFeatured(Collection $collection)
    {
        $collection->update([
            'is_featured' => ! $collection->is_featured,
        ]);

        return back()->with(
            'success',
            $collection->is_featured
                ? 'Collection added to featured collections.'
                : 'Collection removed from featured collections.'
        );
    }


    /**
     * =========================================================
     * PRODUCTS FOR COLLECTION ASSIGNMENT
     * =========================================================
     */

    // ---------------------------------------------------------
    // MANAGE PRODUCTS
    // ---------------------------------------------------------

    public function products(Collection $collection)
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        $assignedProductIds = $collection->products()
            ->pluck('products.id')
            ->toArray();

        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return Inertia::render(
            'MyFashions/Collections/Products',
            [
                'collection' => $collection,
                'products' => $products,
                'assignedProductIds' => $assignedProductIds,
                'categories' => $categories,
            ]
        );
    }



    // public function products(Collection $collection) 
    // {

    //     $products = Product::query()
    //         ->with('category')
    //         ->where('is_active', true)
    //         ->orderBy('name')
    //         ->get();

    //     $assignedProductIds =
    //         $collection
    //             ->products()
    //             ->pluck('products.id')
    //             ->toArray();

    //     return response()->json([

    //         'products' => $products,

    //         'assigned_product_ids' =>
    //             $assignedProductIds,

    //     ]);
    // }


    /**
     * =========================================================
     * ASSIGN PRODUCTS
     * =========================================================
     */

     // ---------------------------------------------------------
    // ASSIGN PRODUCTS
    // ---------------------------------------------------------

    public function assignProducts(
        Request $request,
        Collection $collection
    ) {
        $validated = $request->validate([
            'product_ids' => [
                'nullable',
                'array',
            ],

            'product_ids.*' => [
                'integer',
                'exists:products,id',
            ],
        ]);

        $collection->products()->sync(
            $validated['product_ids'] ?? []
        );

        return redirect()
            ->route(
                'show',
                $collection->id
            )
            ->with(
                'success',
                'Collection products updated successfully.'
            );
    }


public function publicIndex()
{
    $collections = Collection::query()
        ->where('is_active', true)
        ->with([
            'products' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('name');
            }
        ])
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get()
        ->map(function ($collection) {

            return [
                'id' => $collection->id,

                'category' => $collection->slug,

                'title' => $collection->name,

                'subtitle' => $collection->description,

                'image' => $collection->image
                    ? asset('storage/' . $collection->image)
                    : null,

                'href' => route(
                    'collections.show',
                    $collection->slug
                ),

                'featured' => (bool) $collection->is_featured,

                'items' => $collection->products
                    ->take(4)
                    ->map(function ($product) {

                        return [
                            'title' => $product->name,

                            'image' => $product->image
                                ? asset('storage/' . $product->image)
                                : null,

                            'href' => route(
                                'products.show',
                                $product->slug
                            ),
                        ];

                    })
                    ->values(),

            ];

        })
        ->values();

    return Inertia::render('Site/Collections/Index', [
            'collections' => $collections,
        ]
    );
}

    // public function publicIndex()
    // {
    //     $collections = Collection::query()
    //         ->where('is_active', true)
    //         ->with([
    //             'products' => function ($query) {
    //                 $query->where('is_active', true)
    //                     ->orderBy('name');
    //             }
    //         ])
    //         ->orderBy('sort_order')
    //         ->orderBy('name')
    //         ->get();

    //     return Inertia::render('Site/Collections/Index', [
    //         'collections' => $collections,
    //     ]);
    // }

public function publicShow(Collection $collection)
{
    $collection->load([
        'products' => function ($query) {
            $query->where('is_active', true)
                ->orderBy('name');
        }
    ]);

    return Inertia::render('Site/Collections/Show', [
        'collection' => [
            'id' => $collection->id,
            'name' => $collection->name,
            'slug' => $collection->slug,
            'description' => $collection->description,

            'image' => $collection->image
                ? asset('storage/' . $collection->image)
                : null,

            'is_featured' => (bool) $collection->is_featured,

            'products' => $collection->products->map(function ($product) {

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,

                    'sku' => $product->sku,

                    'description' => $product->description,

                    'price' => $product->price,

                    'sale_price' => $product->sale_price,

                    'image' => $product->image
                        ? asset('storage/' . $product->image)
                        : null,

                    'is_active' => (bool) $product->is_active,

                    'is_featured' => (bool) $product->is_featured,

                    'stock_quantity' => $product->stock_quantity,
                ];

            })->values(),
        ],
    ]);
}


}
