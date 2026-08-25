<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    
 
    /**
     * Display shopping cart.
     */
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        $subtotal = collect($cart)->sum(function ($item) {

            // Use sale price when available,
            // otherwise use the regular price.
            $price = $item['sale_price'] ?? $item['price'];

            return $price * $item['quantity'];
        });

        $totalItems = collect($cart)->sum('quantity');

        return Inertia::render('Site/Cart/Index', [
            'cart' => array_values($cart),
            'subtotal' => $subtotal,
            'totalItems' => $totalItems,
        ]);
    }

    public function myCartIndex(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        $subtotal = collect($cart)->sum(function ($item) {

            // Use sale price when available,
            // otherwise use the regular price.
            $price = $item['sale_price'] ?? $item['price'];

            return $price * $item['quantity'];
        });

        $totalItems = collect($cart)->sum('quantity');

        return Inertia::render('MyFashions/Clients/Cart/Index', [
            'cart' => array_values($cart),
            'subtotal' => $subtotal,
            'totalItems' => $totalItems,
        ]);
    }



    

    /**
     * Add product to cart.
     */
    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);

        $quantity = $validated['quantity'] ?? 1;

        if (!$product->is_active) {
            return back()->with('error', 'This product is currently unavailable.');
        }

        if ($product->stock_quantity < $quantity) {
            return back()->with(
                'error',
                'There is not enough stock available.'
            );
        }

        $cart = $request->session()->get('cart', []);

        $productId = (string) $product->id;

     if (isset($cart[$productId])) {

            $newQuantity =
                $cart[$productId]['quantity'] + $quantity;

            if ($newQuantity > $product->stock_quantity) {
                return back()->with(
                    'error',
                    'You cannot add more than the available stock.'
                );
            }

            $cart[$productId]['quantity'] = $newQuantity;

            // Refresh product pricing
            $cart[$productId]['price'] = (float) $product->price;

            $cart[$productId]['sale_price'] = $product->sale_price !== null
                ? (float) $product->sale_price
                : null;

            $cart[$productId]['stock_quantity'] =
                $product->stock_quantity;

            } else {

            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'price' => (float) $product->price,
                'sale_price' => $product->sale_price
                    ? (float) $product->sale_price
                    : null,
                'image' => $product->image,
                'quantity' => $quantity,
                'stock_quantity' => $product->stock_quantity,
            ];
        }

        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            "{$product->name} has been added to your bag."
        );
    }


    /**
     * Update cart quantity.
     */
    public function update(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $cart = $request->session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return back()->with(
                'error',
                'Product is not in your bag.'
            );
        }

        $product = Product::find($productId);

        if (!$product) {
            unset($cart[$productId]);

            $request->session()->put('cart', $cart);

            return back()->with(
                'error',
                'Product no longer exists.'
            );
        }

        if ($validated['quantity'] > $product->stock_quantity) {
            return back()->with(
                'error',
                'Requested quantity exceeds available stock.'
            );
        }

        $cart[$productId]['quantity'] = $validated['quantity'];

        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            'Bag updated successfully.'
        );
    }


    /**
     * Remove product from cart.
     */
    public function remove(Request $request, $productId)
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            'Product removed from your bag.'
        );
    }


    /**
     * Clear shopping bag.
     */
    public function clear(Request $request)
    {
        $request->session()->forget('cart');

        return back()->with(
            'success',
            'Your shopping bag has been cleared.'
        );
    }
}
