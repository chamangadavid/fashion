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

        /*
        |--------------------------------------------------------------------------
        | SUBTOTAL
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | SHIPPING SETTINGS
        |--------------------------------------------------------------------------
        */

        $shippingSettings = [

            'enabled' => $this->getSetting(
                'shipping_enabled',
                true
            ),

            'method' => $this->getSetting(
                'shipping_method',
                'flat_rate'
            ),

            'flat_rate' => (float) $this->getSetting(
                'shipping_flat_rate',
                0
            ),

            'free_shipping_enabled' => $this->getSetting(
                'free_shipping_enabled',
                false
            ),

            'free_shipping_threshold' => (float) $this->getSetting(
                'free_shipping_threshold',
                0
            ),

            'local_shipping_enabled' => $this->getSetting(
                'local_shipping_enabled',
                true
            ),

            'local_shipping_rate' => (float) $this->getSetting(
                'local_shipping_rate',
                0
            ),

            'international_shipping_enabled' => $this->getSetting(
                'international_shipping_enabled',
                false
            ),

            'international_shipping_rate' => (float) $this->getSetting(
                'international_shipping_rate',
                0
            ),

        ];


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return Inertia::render('Site/Checkout/Index', [

            'cart' => array_values($cart),

            'subtotal' => $subtotal,

            'totalItems' => $totalItems,

            'paymentSettings' => $paymentSettings,

            'shippingSettings' => $shippingSettings,

        ]);
    }


    public function calculateShipping(Request $request)
    {
        $validated = $request->validate([
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | CART
        |--------------------------------------------------------------------------
        */

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Your shopping bag is empty.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | SUBTOTAL
        |--------------------------------------------------------------------------
        */

        $subtotal = collect($cart)->sum(function ($item) {

            return (float) $item['price']
                * (int) $item['quantity'];

        });


        /*
        |--------------------------------------------------------------------------
        | SHIPPING SETTINGS
        |--------------------------------------------------------------------------
        */

        $enabled = $this->getSetting(
            'shipping_enabled',
            true
        );

        $method = $this->getSetting(
            'shipping_method',
            'flat_rate'
        );

        $flatRate = (float) $this->getSetting(
            'shipping_flat_rate',
            0
        );

        $freeShippingEnabled = $this->getSetting(
            'free_shipping_enabled',
            false
        );

        $freeShippingThreshold = (float) $this->getSetting(
            'free_shipping_threshold',
            0
        );

        $localShippingEnabled = $this->getSetting(
            'local_shipping_enabled',
            true
        );

        $localShippingRate = (float) $this->getSetting(
            'local_shipping_rate',
            0
        );

        $internationalShippingEnabled = $this->getSetting(
            'international_shipping_enabled',
            false
        );

        $internationalShippingRate = (float) $this->getSetting(
            'international_shipping_rate',
            0
        );


        /*
        |--------------------------------------------------------------------------
        | DEFAULT SHIPPING
        |--------------------------------------------------------------------------
        */

        $shippingAmount = 0;

        $shippingLabel = 'Free Shipping';


        /*
        |--------------------------------------------------------------------------
        | SHIPPING DISABLED
        |--------------------------------------------------------------------------
        */

        if (!$enabled) {

            return response()->json([
                'success' => true,
                'shipping' => 0,
                'shipping_label' => 'Shipping Disabled',
                'total' => $subtotal,
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | FREE SHIPPING THRESHOLD
        |--------------------------------------------------------------------------
        */

        if (
            $freeShippingEnabled &&
            $freeShippingThreshold > 0 &&
            $subtotal >= $freeShippingThreshold
        ) {

            return response()->json([
                'success' => true,
                'shipping' => 0,
                'shipping_label' => 'Free Shipping',
                'total' => $subtotal,
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | FLAT RATE
        |--------------------------------------------------------------------------
        */

        if ($method === 'flat_rate') {

            $shippingAmount = $flatRate;

            $shippingLabel = $shippingAmount > 0
                ? 'Standard Shipping'
                : 'Free Shipping';

        }


        /*
        |--------------------------------------------------------------------------
        | LOCAL / INTERNATIONAL
        |--------------------------------------------------------------------------
        */

        if ($method === 'location') {

            $country = strtolower(
                trim($validated['country'])
            );

            if ($country === 'zambia') {

                if ($localShippingEnabled) {

                    $shippingAmount = $localShippingRate;

                    $shippingLabel = $shippingAmount > 0
                        ? 'Local Shipping'
                        : 'Free Shipping';

                }

            } else {

                if ($internationalShippingEnabled) {

                    $shippingAmount = $internationalShippingRate;

                    $shippingLabel = $shippingAmount > 0
                        ? 'International Shipping'
                        : 'International Shipping Unavailable';

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $total = $subtotal + $shippingAmount;


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'shipping' => round(
                $shippingAmount,
                2
            ),

            'shipping_label' => $shippingLabel,

            'subtotal' => round(
                $subtotal,
                2
            ),

            'total' => round(
                $total,
                2
            ),

        ]);
    }


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

    private function getSetting(string $key, $default = null)
    {
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


    

}
