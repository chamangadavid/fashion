<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Setting;


class CheckoutController extends Controller
{
   

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

    /*
    |--------------------------------------------------------------------------
    | PAYMENT SETTINGS
    |--------------------------------------------------------------------------
    */

    $paymentSettings = [

        'cash_on_delivery' => $this->getSetting(
            'cash_on_delivery',
            true
        ),

        'mobile_money' => $this->getSetting(
            'mobile_money',
            true
        ),

        'card' => $this->getSetting(
            'card',
            false
        ),

        'mobile_money_provider' => $this->getSetting(
            'mobile_money_provider',
            ''
        ),

        'mobile_money_number' => $this->getSetting(
            'mobile_money_number',
            ''
        ),

        'card_provider' => $this->getSetting(
            'card_provider',
            ''
        ),

        'currency' => $this->getSetting(
            'currency',
            'ZMW'
        ),

        'payment_instructions' => $this->getSetting(
            'payment_instructions',
            ''
        ),
    ];

    return Inertia::render('Site/Checkout/Index', [

        'cart' => array_values($cart),

        'subtotal' => $subtotal,

        'totalItems' => $totalItems,

        /*
        |--------------------------------------------------------------------------
        | SEND PAYMENT SETTINGS TO CHECKOUT
        |--------------------------------------------------------------------------
        */

        'paymentSettings' => $paymentSettings,
    ]);
}

 /*
    |--------------------------------------------------------------------------
    | CHECKOUT PAGE
    |--------------------------------------------------------------------------
    */

    // public function index(Request $request)
    // {
    //     $cart = session()->get('cart', []);

    //     if (empty($cart)) {
    //         return redirect()
    //             ->route('cart.index')
    //             ->with('error', 'Your shopping bag is empty.');
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | CALCULATE CART TOTALS
    //     |--------------------------------------------------------------------------
    //     */

    //     $subtotal = collect($cart)->sum(function ($item) {
    //         return (float) $item['price'] * (int) $item['quantity'];
    //     });

    //     $totalItems = collect($cart)->sum('quantity');


    //     /*
    //     |--------------------------------------------------------------------------
    //     | GET PAYMENT SETTINGS
    //     |--------------------------------------------------------------------------
    //     */

    //     $paymentSettings = [

    //         'cash_on_delivery' => $this->getSetting(
    //             'cash_on_delivery',
    //             true
    //         ),

    //         'mobile_money' => $this->getSetting(
    //             'mobile_money',
    //             true
    //         ),

    //         'card' => $this->getSetting(
    //             'card',
    //             false
    //         ),

    //         'mobile_money_provider' => $this->getSetting(
    //             'mobile_money_provider',
    //             ''
    //         ),

    //         'mobile_money_number' => $this->getSetting(
    //             'mobile_money_number',
    //             ''
    //         ),

    //         'card_provider' => $this->getSetting(
    //             'card_provider',
    //             ''
    //         ),

    //         'currency' => $this->getSetting(
    //             'currency',
    //             'ZMW'
    //         ),

    //         'payment_instructions' => $this->getSetting(
    //             'payment_instructions',
    //             ''
    //         ),

    //     ];


    //     /*
    //     |--------------------------------------------------------------------------
    //     | RETURN CHECKOUT PAGE
    //     |--------------------------------------------------------------------------
    //     */

    //     return Inertia::render(
    //         'Site/Checkout/Index',
    //         [
    //             'cart' => array_values($cart),

    //             'subtotal' => $subtotal,

    //             'totalItems' => $totalItems,

    //             'paymentSettings' => $paymentSettings,
    //         ]
    //     );
    // }


     /*
    |--------------------------------------------------------------------------
    | STORE CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | GET ENABLED PAYMENT METHODS
        |--------------------------------------------------------------------------
        */

        $enabledPaymentMethods = $this->enabledPaymentMethods();


        /*
        |--------------------------------------------------------------------------
        | VALIDATE CUSTOMER INFORMATION
        |--------------------------------------------------------------------------
        */

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

            /*
            |--------------------------------------------------------------------------
            | PAYMENT METHOD
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | This is validated against the methods currently enabled
            | in the admin settings.
            |
            */

            'payment_method' => [
                'required',
                'string',
                'in:' . implode(',', $enabledPaymentMethods),
            ],

            /*
            |--------------------------------------------------------------------------
            | MOBILE MONEY
            |--------------------------------------------------------------------------
            */

            'mobile_money_number' => [
                'nullable',
                'required_if:payment_method,mobile_money',
                'string',
                'max:30',
            ],

            /*
            |--------------------------------------------------------------------------
            | CARD
            |--------------------------------------------------------------------------
            */

            'cardholder_name' => [
                'nullable',
                'required_if:payment_method,card',
                'string',
                'max:255',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | CHECK IF ANY PAYMENT METHOD IS ENABLED
        |--------------------------------------------------------------------------
        */

        if (empty($enabledPaymentMethods)) {

            return back()
                ->withErrors([
                    'payment_method' =>
                        'No payment methods are currently available. Please contact the store.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | ORDER CREATION COMES NEXT
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Checkout information validated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ENABLED PAYMENT METHODS
    |--------------------------------------------------------------------------
    */

    private function enabledPaymentMethods(): array
    {
        $methods = [];


        if ($this->getSetting(
            'cash_on_delivery',
            true
        )) {
            $methods[] = 'cash_on_delivery';
        }


        if ($this->getSetting(
            'mobile_money',
            true
        )) {
            $methods[] = 'mobile_money';
        }


        if ($this->getSetting(
            'card',
            false
        )) {
            $methods[] = 'card';
        }


        return $methods;
    }


    /*
    |--------------------------------------------------------------------------
    | GET SETTING
    |--------------------------------------------------------------------------
    */

    private function getSetting(
        string $key,
        $default = null
    ) {
        $setting = Setting::where(
            'key',
            $key
        )->first();


        if (!$setting) {
            return $default;
        }


        /*
        |--------------------------------------------------------------------------
        | BOOLEAN SETTINGS
        |--------------------------------------------------------------------------
        */

        $booleanSettings = [

            'cash_on_delivery',

            'mobile_money',

            'card',

        ];


        if (in_array(
            $key,
            $booleanSettings
        )) {

            return filter_var(
                $setting->value,
                FILTER_VALIDATE_BOOLEAN
            );
        }


        return $setting->value;
    }


    // public function index(Request $request)
    // {
    //     $cart = session()->get('cart', []);

    //     if (empty($cart)) {
    //         return redirect()
    //             ->route('cart.index')
    //             ->with('error', 'Your shopping bag is empty.');
    //     }

    //     $subtotal = collect($cart)->sum(function ($item) {
    //         return (float) $item['price'] * (int) $item['quantity'];
    //     });

    //     $totalItems = collect($cart)->sum('quantity');

    //     return Inertia::render('Site/Checkout/Index', [
    //         'cart' => array_values($cart),
    //         'subtotal' => $subtotal,
    //         'totalItems' => $totalItems,
    //     ]);
    // }


    // public function store(Request $request)
    // {

    // // dd($request->all());
    //     $validated = $request->validate([

    //         'email' => [
    //             'required',
    //             'email',
    //             'max:255',
    //         ],

    //         'first_name' => [
    //             'required',
    //             'string',
    //             'max:100',
    //         ],

    //         'last_name' => [
    //             'required',
    //             'string',
    //             'max:100',
    //         ],

    //         'address' => [
    //             'required',
    //             'string',
    //             'max:255',
    //         ],

    //         'city' => [
    //             'required',
    //             'string',
    //             'max:100',
    //         ],

    //         'country' => [
    //             'required',
    //             'string',
    //             'max:100',
    //         ],

    //         'phone' => [
    //             'required',
    //             'string',
    //             'max:30',
    //         ],

    //         'payment_method' => [
    //             'required',
    //             'in:cash_on_delivery,mobile_money,card',
    //         ],

    //         'mobile_money_number' => [
    //             'nullable',
    //             'required_if:payment_method,mobile_money',
    //             'string',
    //             'max:30',
    //         ],

    //         'cardholder_name' => [
    //             'nullable',
    //             'required_if:payment_method,card',
    //             'string',
    //             'max:255',
    //         ],

    //     ]);


    //     // Order creation comes next.

    //     return back()->with(
    //         'success',
    //         'Checkout information validated successfully.'
    //     );
    // }

    

}
