<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{

    /**
     * Display products.
     */
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $query->where(
                'product_category_id',
                $request->category
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Status filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            if ($request->status === 'active') {

                $query->where('is_active', true);

            }

            if ($request->status === 'inactive') {

                $query->where('is_active', false);

            }

        }

        $products = $query
            ->paginate(15)
            ->withQueryString();

        return Inertia::render(
            'MyFashions/Products/Index',
            [
                'products' => $products,

                'categories' => ProductCategory::orderBy('name')
                    ->get(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return Inertia::render(
            'MyFashions/Products/Create',
            [
                'categories' => ProductCategory::where(
                    'is_active',
                    true
                )
                ->orderBy('name')
                ->get(),
            ]
        );
    }

     /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'product_category_id' => [
                'nullable',
                'exists:product_categories,id'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'sku' => [
                'required',
                'string',
                'max:255',
                'unique:products,sku'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'compare_price' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'cost_price' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'stock_quantity' => [
                'required',
                'integer',
                'min:0'
            ],

            'low_stock_threshold' => [
                'required',
                'integer',
                'min:0'
            ],

            'status' => [
                'required',
                'in:active,inactive'
            ],

            'featured' => [
                'nullable',
                'boolean'
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request
                ->file('image')
                ->store('products', 'public');
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE PRODUCT
        |--------------------------------------------------------------------------
        */

        Product::create([

            'product_category_id' => $validated['product_category_id'] ?? null,

            'name' => $validated['name'],

            'slug' => Str::slug($validated['name']),

            'sku' => $validated['sku'],

            'description' => $validated['description'] ?? null,

            'price' => $validated['price'],

            'sale_price' => $validated['compare_price'] ?? null,

            'cost_price' => $validated['cost_price'] ?? null,

            'stock_quantity' => $validated['stock_quantity'],

            'low_stock_threshold' => $validated['low_stock_threshold'],

            'image' => $imagePath,

            'is_active' => $validated['status'] === 'active',

            'is_featured' => $request->boolean('featured'),

        ]);


        return redirect()
            ->route('fashion.products.index')
            ->with('success', 'Product created successfully.');
    }


     /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    public function show(Product $product)
    {
        $product->load('category');

        return Inertia::render(
            'MyFashions/Products/View',
            [
                'product' => $product,
            ]
        );
    }


     /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    
    public function edit(Product $product)
    {
        return Inertia::render('MyFashions/Products/Edit',
            [
                'product' => $product,
                'categories' => ProductCategory::where('is_active', true)
                ->orderBy('name')->get(),
            ]
        );
    }



     /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    
    public function update(Request $request, Product $product ) {

        $validated = $request->validate([

            'name' => ['required', 'string', 'max:255', ],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id,],
            'product_category_id' => ['nullable', 'exists:product_categories,id',],
            'description' => ['nullable', 'string',],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'sale_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'stock_quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'low_stock_threshold' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'boolean',
            ],

            'is_featured' => [
                'boolean',
            ],

            'image' => [
                'nullable',
                'image',
                'max:2048',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Replace image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            if (
                $product->image &&
                Storage::disk('public')->exists(
                    $product->image
                )
            ) {

                Storage::disk('public')
                    ->delete($product->image);

            }

            $validated['image'] =
                $request->file('image')
                    ->store(
                        'products',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $validated['slug'] = Str::slug(
            $validated['name']
        );

        $validated['is_active'] =
            $request->boolean('is_active');

        $validated['is_featured'] =
            $request->boolean('is_featured');

        $product->update($validated);

        return redirect()
            ->route(
                'fashion.products.index'
            )
            ->with(
                'success',
                'Product updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Product $product)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete image
        |--------------------------------------------------------------------------
        */

        if (
            $product->image &&
            Storage::disk('public')->exists(
                $product->image
            )
        ) {

            Storage::disk('public')
                ->delete($product->image);

        }

        $product->delete();

        return redirect()
            ->route(
                'fashion.products.index'
            )
            ->with(
                'success',
                'Product deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORIES
    |--------------------------------------------------------------------------
    */

    public function categories()
    {
        $categories = ProductCategory::withCount(
            'products'
        )
        ->latest()
        ->get();

        return Inertia::render(
            'MyFashions/Products/Categories',
            [
                'categories' => $categories,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INVENTORY
    |--------------------------------------------------------------------------
    */

    // public function inventory()
    // {
    //     $products = Product::with('category')
    //         ->orderBy('stock_quantity')
    //         ->get();

    //     return Inertia::render(
    //         'MyFashions/Products/Inventory',
    //         [
    //             'products' => $products,
    //         ]
    //     );
    // }

    public function inventory(Request $request)
{
    $query = Product::with('category')
        ->orderBy('name');

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORY FILTER
    |--------------------------------------------------------------------------
    */

    if ($request->filled('category_id')) {

        $query->where(
            'product_category_id',
            $request->category_id
        );

    }


    /*
    |--------------------------------------------------------------------------
    | STOCK STATUS FILTER
    |--------------------------------------------------------------------------
    */

    if ($request->filled('stock_status')) {

        switch ($request->stock_status) {

            case 'in_stock':

                $query->where(
                    'stock_quantity',
                    '>',
                    0
                );

                break;


            case 'low_stock':

                $query->whereColumn(
                    'stock_quantity',
                    '<=',
                    'low_stock_threshold'
                )
                ->where(
                    'stock_quantity',
                    '>',
                    0
                );

                break;


            case 'out_of_stock':

                $query->where(
                    'stock_quantity',
                    '<=',
                    0
                );

                break;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    $products = $query
        ->paginate(15)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | CATEGORIES
    |--------------------------------------------------------------------------
    */

    $categories = ProductCategory::where(
        'is_active',
        true
    )
    ->orderBy('name')
    ->get();


    /*
    |--------------------------------------------------------------------------
    | INVENTORY SUMMARY
    |--------------------------------------------------------------------------
    */

    $totalProducts = Product::count();

    $totalStock = Product::sum(
        'stock_quantity'
    );

    $lowStockProducts = Product::whereColumn(
        'stock_quantity',
        '<=',
        'low_stock_threshold'
    )
    ->where(
        'stock_quantity',
        '>',
        0
    )
    ->count();

    $outOfStockProducts = Product::where(
        'stock_quantity',
        '<=',
        0
    )->count();


    return Inertia::render(
        'MyFashions/Products/Inventory',
        [
            'products' => $products,

            'categories' => $categories,

            'filters' => [
                'search' => $request->search,
                'category_id' => $request->category_id,
                'stock_status' => $request->stock_status,
            ],

            'summary' => [
                'total_products' => $totalProducts,
                'total_stock' => $totalStock,
                'low_stock' => $lowStockProducts,
                'out_of_stock' => $outOfStockProducts,
            ],
        ]
    );
}

}
