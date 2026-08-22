<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CheckoutController extends Controller
{
    
 /**
     * Display checkout page.
     */
    // public function index(Request $request)
    // {
    //     return Inertia::render('Site/Checkout/Index');
    // }

        /**
     * Show checkout page
     */
    // public function index(Request $request)
    // {
    //     $cart = session()->get('cart', []);

    //     return Inertia::render('Site/Checkout/Index', [
    //         'cart' => array_values($cart),

    //         'subtotal' => collect($cart)->sum(function ($item) {
    //             return $item['price'] * $item['quantity'];
    //         }),

    //         'totalItems' => collect($cart)->sum('quantity'),
    //     ]);
    // }




    public function index(Request $request)
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()
            ->route('cart.index')
            ->with('error', 'Your shopping bag is empty.');
    }

    $subtotal = collect($cart)->sum(function ($item) {
        return (float) $item['price'] * (int) $item['quantity'];
    });

    $totalItems = collect($cart)->sum('quantity');

    return Inertia::render('Site/Checkout/Index', [
        'cart' => array_values($cart),
        'subtotal' => $subtotal,
        'totalItems' => $totalItems,
    ]);
}

    /**
     * Place order.
     */
    // public function store(Request $request)
    // {
    //     // We will implement order creation next.

    //     return redirect()
    //         ->route('checkout.index')
    //         ->with('success', 'Checkout submitted successfully.');
    // }

    public function store(Request $request)
{

// dd($request->all());
    $validated = $request->validate([

        'email' => [
            'required',
            'email',
            'max:255',
        ],

        'first_name' => [
            'required',
            'string',
            'max:100',
        ],

        'last_name' => [
            'required',
            'string',
            'max:100',
        ],

        'address' => [
            'required',
            'string',
            'max:255',
        ],

        'city' => [
            'required',
            'string',
            'max:100',
        ],

        'country' => [
            'required',
            'string',
            'max:100',
        ],

        'phone' => [
            'required',
            'string',
            'max:30',
        ],

        'payment_method' => [
            'required',
            'in:cash_on_delivery,mobile_money,card',
        ],

        'mobile_money_number' => [
            'nullable',
            'required_if:payment_method,mobile_money',
            'string',
            'max:30',
        ],

        'cardholder_name' => [
            'nullable',
            'required_if:payment_method,card',
            'string',
            'max:255',
        ],

    ]);


    // Order creation comes next.

    return back()->with(
        'success',
        'Checkout information validated successfully.'
    );
}

}
