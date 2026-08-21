<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{
    
/**
     * Display categories.
     */
    public function index(Request $request)
    {
        $query = ProductCategory::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status') && $request->status !== 'all') {

            $query->where(
                'is_active',
                $request->status === 'active'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | SORTING
        |--------------------------------------------------------------------------
        */

        $categories = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total' => ProductCategory::count(),

            'active' => ProductCategory::where(
                'is_active',
                true
            )->count(),

            'inactive' => ProductCategory::where(
                'is_active',
                false
            )->count(),

            'featured' => ProductCategory::where(
                'is_featured',
                true
            )->count(),
        ];

        return Inertia::render(
            'MyFashions/Products/Categories',
            [
                'categories' => $categories,
                'stats' => $stats,

                'filters' => [
                    'search' => $request->search ?? '',
                    'status' => $request->status ?? 'all',
                ],
            ]
        );
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
                'unique:product_categories,name',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'max:2048',
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

        $validated['slug'] = Str::slug(
            $validated['name']
        );

        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $validated['image'] = $request
                ->file('image')
                ->store('product-categories', 'public');

        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULTS
        |--------------------------------------------------------------------------
        */

        $validated['is_active'] =
            $request->boolean('is_active', true);

        $validated['is_featured'] =
            $request->boolean('is_featured', false);

        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;

        ProductCategory::create($validated);

        return redirect()
            ->route('fashion.products.categories')
            ->with(
                'success',
                'Product category created successfully.'
            );
    }


    /**
     * Update category.
     */
    public function update(
        Request $request,
        ProductCategory $category
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
                'unique:product_categories,name,' .
                    $category->id,
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'max:2048',
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

        $validated['slug'] = Str::slug(
            $validated['name']
        );

        if ($request->hasFile('image')) {

            $validated['image'] = $request
                ->file('image')
                ->store('product-categories', 'public');

        }

        $validated['is_active'] =
            $request->boolean('is_active');

        $validated['is_featured'] =
            $request->boolean('is_featured');

        $validated['sort_order'] =
            $validated['sort_order'] ?? 0;

        $category->update($validated);

        return redirect()
            ->route('fashion.products.categories')
            ->with(
                'success',
                'Product category updated successfully.'
            );
    }


    /**
     * Delete category.
     */
    public function destroy(ProductCategory $category)
    {
        $category->delete();

        return redirect()
            ->route('fashion.products.categories')
            ->with(
                'success',
                'Product category deleted successfully.'
            );
    }


     /**
     * Toggle active status.
     */
    public function toggleStatus(ProductCategory $category)
    {
        $category->update([
            'is_active' => !$category->is_active,
        ]);

        return back()->with(
            'success','Category status updated successfully.'
        );
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(ProductCategory $category)
    {
        $category->update([
            'is_featured' => !$category->is_featured,
        ]);

        return back()->with(
            'success',
            'Category featured status updated successfully.'
        );
    }





}
