<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\OrderStatusHistory;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $query = Order::with('items')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                ->orWhere('customer_email', 'like', "%{$search}%")
                ->orWhere('shipping_first_name', 'like', "%{$search}%")
                ->orWhere('shipping_last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->paginate(10)->withQueryString();

        return Inertia::render('MyFashions/Orders/Index', [
            'orders' => $orders,
            'filters' => [
                'search' => $request->search ?? '',
                'status' => $request->status ?? '',
                'payment_method' => $request->payment_method ?? '',
            ],
        ]);
    }

    public function pending(Request $request)
    {
        $query = Order::with('items')
            ->where('status', 'pending')
            ->latest();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('shipping_first_name', 'like', "%{$search}%")
                    ->orWhere('shipping_last_name', 'like', "%{$search}%");

            });
        }

        $orders = $query
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('MyFashions/Orders/Pending', [

            'orders' => $orders,

            'filters' => [
                'search' => $request->search ?? '',
                'payment_method' => $request->payment_method ?? '',
            ],

        ]);
    }

    public function processing(Request $request)
    {
        $query = Order::with('items')
            ->where('status', 'processing')
            ->latest();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('shipping_first_name', 'like', "%{$search}%")
                    ->orWhere('shipping_last_name', 'like', "%{$search}%");

            });
        }

        $orders = $query
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('MyFashions/Orders/Processing', [

            'orders' => $orders,

            'filters' => [
                'search' => $request->search ?? '',
                'payment_method' => $request->payment_method ?? '',
            ],

        ]);
    }

    public function completed(Request $request)
    {
        $query = Order::with('items')
            ->where('status', 'completed')
            ->latest();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('shipping_first_name', 'like', "%{$search}%")
                    ->orWhere('shipping_last_name', 'like', "%{$search}%");

            });
        }

        $orders = $query
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('MyFashions/Orders/Completed', [

            'orders' => $orders,

            'filters' => [
                'search' => $request->search ?? '',
            ],

        ]);
    }

    public function cancelled(Request $request)
    {
        $query = Order::with('items')
            ->where('status', 'cancelled')
            ->latest();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('shipping_first_name', 'like', "%{$search}%")
                    ->orWhere('shipping_last_name', 'like', "%{$search}%");

            });
        }

        $orders = $query
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('MyFashions/Orders/Cancelled', [

            'orders' => $orders,

            'filters' => [
                'search' => $request->search ?? '',
                'payment_method' => $request->payment_method ?? '',
            ],

        ]);
    }

    public function show(Order $order)
    {
        $order->load([
            'items.product',
            'statusHistory.user',
        ]);

        return Inertia::render('MyFashions/Orders/ViewOrder', [
            'order' => $order,
        ]);
    }

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE CHECKOUT
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            // Contact
            'customer_email' => [
                'required',
                'email',
                'max:255',
            ],

            // Customer
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

            // Shipping Address
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

            'customer_phone' => [
                'required',
                'string',
                'max:30',
            ],

            // Payment
            'payment_method' => [
                'required',
                'in:cash_on_delivery,mobile_money,card',
            ],

            // Mobile Money
            'mobile_money_number' => [
                'nullable',
                'required_if:payment_method,mobile_money',
                'string',
                'max:30',
            ],

            // Card
            'cardholder_name' => [
                'nullable',
                'required_if:payment_method,card',
                'string',
                'max:255',
            ],

            'card_number' => [
                'nullable',
                'required_if:payment_method,card',
                'string',
            ],

            'card_expiry' => [
                'nullable',
                'required_if:payment_method,card',
                'string',
                'max:5',
            ],

            'card_cvv' => [
                'nullable',
                'required_if:payment_method,card',
                'string',
                'max:4',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | GET CART
        |--------------------------------------------------------------------------
        */

        $cart = session()->get('cart', []);

        if (empty($cart)) {

            return back()->withErrors([
                'cart' => 'Your shopping bag is empty.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE ORDER
        |--------------------------------------------------------------------------
        */

        $order = DB::transaction(function () use (
            $validated,
            $cart
        ) {

            /*
            |--------------------------------------------------------------------------
            | CALCULATE SUBTOTAL
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            /*
            |--------------------------------------------------------------------------
            | PRODUCTS
            |--------------------------------------------------------------------------
            */

            $products = [];


            /*
            |--------------------------------------------------------------------------
            | CHECK PRODUCTS + STOCK
            |--------------------------------------------------------------------------
            */

            foreach ($cart as $productId => $item) {

                $product = Product::lockForUpdate()
                    ->find($productId);


                /*
                |--------------------------------------------------------------------------
                | PRODUCT EXISTS
                |--------------------------------------------------------------------------
                */

                if (!$product) {

                    throw new \Exception(
                        'One of the products in your bag no longer exists.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | PRODUCT ACTIVE
                |--------------------------------------------------------------------------
                */

                if (!$product->is_active) {

                    throw new \Exception(
                        "{$product->name} is no longer available."
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | QUANTITY
                |--------------------------------------------------------------------------
                */

                $quantity = (int) $item['quantity'];


                if ($quantity <= 0) {

                    throw new \Exception(
                        "Invalid quantity for {$product->name}."
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | STOCK
                |--------------------------------------------------------------------------
                */

                if ($product->stock_quantity < $quantity) {

                    throw new \Exception(
                        "Insufficient stock for {$product->name}."
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | PRODUCT PRICE
                |--------------------------------------------------------------------------
                */

                $unitPrice = $product->sale_price !== null
                    ? (float) $product->sale_price
                    : (float) $product->price;


                /*
                |--------------------------------------------------------------------------
                | LINE TOTAL
                |--------------------------------------------------------------------------
                */

                $lineTotal = $unitPrice * $quantity;


                /*
                |--------------------------------------------------------------------------
                | SUBTOTAL
                |--------------------------------------------------------------------------
                */

                $subtotal += $lineTotal;


                /*
                |--------------------------------------------------------------------------
                | STORE PRODUCT INFORMATION
                |--------------------------------------------------------------------------
                */

                $products[] = [

                    'product' => $product,

                    'quantity' => $quantity,

                    'unit_price' => $unitPrice,

                    'line_total' => $lineTotal,

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | SHIPPING SETTINGS
            |--------------------------------------------------------------------------
            |
            | These values come from the Shipping Settings page.
            |
            */

            $shippingEnabled = $this->getSetting(
                'shipping_enabled',
                true
            );


            /*
            |--------------------------------------------------------------------------
            | SHIPPING TYPE
            |--------------------------------------------------------------------------
            |
            | Possible examples:
            |
            | flat_rate
            | free
            | city
            | weight
            |
            */

            $shippingType = $this->getSetting(
                'shipping_type',
                'flat_rate'
            );


            /*
            |--------------------------------------------------------------------------
            | DEFAULT SHIPPING AMOUNT
            |--------------------------------------------------------------------------
            */

            $shippingAmount = 0;


            /*
            |--------------------------------------------------------------------------
            | SHIPPING CALCULATION
            |--------------------------------------------------------------------------
            */

            if ($shippingEnabled) {

                /*
                |--------------------------------------------------------------------------
                | FREE SHIPPING
                |--------------------------------------------------------------------------
                */

                if ($shippingType === 'free') {

                    $shippingAmount = 0;
                }


                /*
                |--------------------------------------------------------------------------
                | FLAT RATE SHIPPING
                |--------------------------------------------------------------------------
                */

                elseif ($shippingType === 'flat_rate') {

                    $shippingAmount = (float) $this->getSetting(
                        'shipping_flat_rate',
                        0
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CITY BASED SHIPPING
                |--------------------------------------------------------------------------
                */

                elseif ($shippingType === 'city') {

                    /*
                    |--------------------------------------------------------------------------
                    | Get shipping city rates
                    |--------------------------------------------------------------------------
                    |
                    | Expected format:
                    |
                    | [
                    |     'Lusaka' => 50,
                    |     'Kitwe' => 70,
                    |     'Ndola' => 70,
                    | ]
                    |
                    */

                    $cityRates = $this->getSetting(
                        'shipping_city_rates',
                        []
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Make sure city rates are an array
                    |--------------------------------------------------------------------------
                    */

                    if (is_string($cityRates)) {

                        $decoded = json_decode(
                            $cityRates,
                            true
                        );

                        $cityRates = is_array($decoded)
                            ? $decoded
                            : [];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Find customer city
                    |--------------------------------------------------------------------------
                    */

                    $customerCity = trim(
                        strtolower(
                            $validated['city']
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Find matching city
                    |--------------------------------------------------------------------------
                    */

                    foreach ($cityRates as $city => $rate) {

                        if (
                            strtolower(trim($city))
                            === $customerCity
                        ) {

                            $shippingAmount = (float) $rate;

                            break;
                        }
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | COUNTRY BASED SHIPPING
                |--------------------------------------------------------------------------
                */

                elseif ($shippingType === 'country') {

                    /*
                    |--------------------------------------------------------------------------
                    | Expected format:
                    |
                    | [
                    |     'Zambia' => 50,
                    |     'Zimbabwe' => 150,
                    |     'Malawi' => 150,
                    | ]
                    |--------------------------------------------------------------------------
                    */

                    $countryRates = $this->getSetting(
                        'shipping_country_rates',
                        []
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Decode JSON if necessary
                    |--------------------------------------------------------------------------
                    */

                    if (is_string($countryRates)) {

                        $decoded = json_decode(
                            $countryRates,
                            true
                        );

                        $countryRates = is_array($decoded)
                            ? $decoded
                            : [];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Find customer country
                    |--------------------------------------------------------------------------
                    */

                    $customerCountry = trim(
                        strtolower(
                            $validated['country']
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Find matching country
                    |--------------------------------------------------------------------------
                    */

                    foreach ($countryRates as $country => $rate) {

                        if (
                            strtolower(trim($country))
                            === $customerCountry
                        ) {

                            $shippingAmount = (float) $rate;

                            break;
                        }
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | FREE SHIPPING THRESHOLD
            |--------------------------------------------------------------------------
            */

            $freeShippingEnabled = $this->getSetting(
                'free_shipping_enabled',
                false
            );


            $freeShippingThreshold = (float) $this->getSetting(
                'free_shipping_threshold',
                0
            );


            if (
                $shippingEnabled &&
                $freeShippingEnabled &&
                $freeShippingThreshold > 0 &&
                $subtotal >= $freeShippingThreshold
            ) {

                $shippingAmount = 0;
            }


            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            $totalAmount =
                $subtotal +
                $shippingAmount;


            /*
            |--------------------------------------------------------------------------
            | ORDER NUMBER
            |--------------------------------------------------------------------------
            */

            $orderNumber =
                'ORD-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    Str::random(6)
                );


            /*
            |--------------------------------------------------------------------------
            | PAYMENT STATUS
            |--------------------------------------------------------------------------
            */

            $paymentStatus = 'pending';


            /*
            |--------------------------------------------------------------------------
            | ORDER STATUS
            |--------------------------------------------------------------------------
            */

            $orderStatus = 'pending';


            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER
            |--------------------------------------------------------------------------
            */

            $order = Order::create([

                'order_number' => $orderNumber,

                'customer_email' =>
                    $validated['customer_email'],

                'customer_phone' =>
                    $validated['customer_phone'],


                /*
                |--------------------------------------------------------------------------
                | SHIPPING CUSTOMER
                |--------------------------------------------------------------------------
                */

                'shipping_first_name' =>
                    $validated['first_name'],

                'shipping_last_name' =>
                    $validated['last_name'],

                'shipping_address' =>
                    $validated['address'],

                'shipping_city' =>
                    $validated['city'],

                'shipping_country' =>
                    $validated['country'],


                /*
                |--------------------------------------------------------------------------
                | ORDER AMOUNTS
                |--------------------------------------------------------------------------
                */

                'subtotal' =>
                    $subtotal,

                'shipping_amount' =>
                    $shippingAmount,

                'total_amount' =>
                    $totalAmount,


                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                'payment_method' =>
                    $validated['payment_method'],

                'payment_status' =>
                    $paymentStatus,


                /*
                |--------------------------------------------------------------------------
                | ORDER STATUS
                |--------------------------------------------------------------------------
                */

                'status' =>
                    $orderStatus,
            ]);


            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER HISTORY
            |--------------------------------------------------------------------------
            */

            OrderStatusHistory::create([

                'order_id' =>
                    $order->id,

                'old_status' =>
                    null,

                'new_status' =>
                    'pending',

                'changed_by' =>
                    Auth::id(),

                'note' =>
                    'Order placed.',
            ]);


            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER ITEMS + DEDUCT STOCK
            |--------------------------------------------------------------------------
            */

            foreach ($products as $item) {

                $product =
                    $item['product'];


                /*
                |--------------------------------------------------------------------------
                | CREATE ORDER ITEM
                |--------------------------------------------------------------------------
                */

                OrderItem::create([

                    'order_id' =>
                        $order->id,

                    'product_id' =>
                        $product->id,

                    'product_name' =>
                        $product->name,

                    'sku' =>
                        $product->sku,

                    'quantity' =>
                        $item['quantity'],

                    'unit_price' =>
                        $item['unit_price'],

                    'total_price' =>
                        $item['line_total'],
                ]);


                /*
                |--------------------------------------------------------------------------
                | DEDUCT STOCK
                |--------------------------------------------------------------------------
                */

                $product->decrement(
                    'stock_quantity',
                    $item['quantity']
                );
            }


            /*
            |--------------------------------------------------------------------------
            | RETURN ORDER
            |--------------------------------------------------------------------------
            */

            return $order;
        });


        /*
        |--------------------------------------------------------------------------
        | CLEAR CART
        |--------------------------------------------------------------------------
        */

        session()->forget('cart');


        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO CONFIRMATION
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'checkout.confirmation',
            $order
        );
    }

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

                'shipping_enabled',

                'free_shipping_enabled',

            ];


            if (in_array($key, $booleanSettings)) {

                return filter_var(
                    $setting->value,
                    FILTER_VALIDATE_BOOLEAN
                );

            }


            return $setting->value;
    }

    public function confirmation(Order $order)
    {
        $order->load([
            'items.product',
        ]);

        return Inertia::render('Site/Checkout/Confirmation', [
            'order' => $order,
        ]);
    }

    public function myConfirmation(Order $order)
    {
        $order->load([
            'items.product',
            'statusHistory.user',
        ]);

        return Inertia::render(
            'MyFashions/Clients/Checkout/Confirmation',
            [
                'order' => $order,
            ]
        );
    }

    public function updateStatus(Request $request, Order $order) 
    {
        $validated = $request->validate([

            'status' => [
                'required',
                'in:pending,processing,completed,cancelled',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);


        $newStatus = $validated['status'];

        $oldStatus = $order->status;


        /*
        |--------------------------------------------------------------------------
        | NO CHANGE
        |--------------------------------------------------------------------------
        */

        if ($oldStatus === $newStatus) {

            return back()->with(
                'info',
                'Order status is already ' . $newStatus . '.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ALLOWED TRANSITIONS
        |--------------------------------------------------------------------------
        */

        $allowedTransitions = [

            'pending' => [
                'processing',
                'cancelled',
            ],

            'processing' => [
                'completed',
                'cancelled',
            ],

            'completed' => [],

            'cancelled' => [],

        ];


        if (
            !in_array(
                $newStatus,
                $allowedTransitions[$oldStatus] ?? [],
                true
            )
        ) {

            return back()->withErrors([

                'status' =>
                    "Cannot change order from {$oldStatus} to {$newStatus}.",

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $order,
            $oldStatus,
            $newStatus,
            $validated
        ) {

            /*
            |--------------------------------------------------------------------------
            | CANCEL + RESTORE STOCK
            |--------------------------------------------------------------------------
            */

            if ($newStatus === 'cancelled') {

                $order->load('items');

                foreach ($order->items as $item) {

                    $product = Product::lockForUpdate()
                        ->find($item->product_id);


                    if (!$product) {

                        throw new \Exception(

                            "Product {$item->product_name} no longer exists."

                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | RESTORE STOCK
                    |--------------------------------------------------------------------------
                    */

                    $product->increment(
                        'stock_quantity',
                        $item->quantity
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE ORDER
            |--------------------------------------------------------------------------
            */

            $order->update([

                'status' => $newStatus,

            ]);


            /*
            |--------------------------------------------------------------------------
            | CREATE STATUS HISTORY
            |--------------------------------------------------------------------------
            */

            OrderStatusHistory::create([

                'order_id' => $order->id,

                'old_status' => $oldStatus,

                'new_status' => $newStatus,

                'changed_by' => Auth::id(),

                'note' =>
                    $validated['note']
                    ?? null,

            ]);

        });


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return back()->with(

            'success',

            $newStatus === 'cancelled'

                ? 'Order cancelled and stock restored successfully.'

                : 'Order status updated successfully.'

        );
    }

    public function myStore(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE CHECKOUT
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            // Contact
            'customer_email' => [
                'required',
                'email',
                'max:255',
            ],

            // Customer
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

            // Shipping Address
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

            'customer_phone' => [
                'required',
                'string',
                'max:30',
            ],

            // Payment
            'payment_method' => [
                'required',
                'in:cash_on_delivery,mobile_money,card',
            ],

            // Mobile Money
            'mobile_money_number' => [
                'nullable',
                'required_if:payment_method,mobile_money',
                'string',
                'max:30',
            ],

            // Card
            'cardholder_name' => [
                'nullable',
                'required_if:payment_method,card',
                'string',
                'max:255',
            ],

            'card_number' => [
                'nullable',
                'required_if:payment_method,card',
                'string',
            ],

            'card_expiry' => [
                'nullable',
                'required_if:payment_method,card',
                'string',
                'max:5',
            ],

            'card_cvv' => [
                'nullable',
                'required_if:payment_method,card',
                'string',
                'max:4',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | GET CART
        |--------------------------------------------------------------------------
        */

        $cart = session()->get('cart', []);

        if (empty($cart)) {

            return back()->withErrors([
                'cart' => 'Your shopping bag is empty.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE ORDER
        |--------------------------------------------------------------------------
        */

        $order = DB::transaction(function () use (
            $validated,
            $cart
        ) {

            /*
            |--------------------------------------------------------------------------
            | CALCULATE SUBTOTAL
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            /*
            |--------------------------------------------------------------------------
            | PRODUCTS
            |--------------------------------------------------------------------------
            */

            $products = [];


            /*
            |--------------------------------------------------------------------------
            | CHECK PRODUCTS + STOCK
            |--------------------------------------------------------------------------
            */

            foreach ($cart as $productId => $item) {

                $product = Product::lockForUpdate()
                    ->find($productId);


                /*
                |--------------------------------------------------------------------------
                | PRODUCT EXISTS
                |--------------------------------------------------------------------------
                */

                if (!$product) {

                    throw new \Exception(
                        'One of the products in your bag no longer exists.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | PRODUCT ACTIVE
                |--------------------------------------------------------------------------
                */

                if (!$product->is_active) {

                    throw new \Exception(
                        "{$product->name} is no longer available."
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | QUANTITY
                |--------------------------------------------------------------------------
                */

                $quantity = (int) $item['quantity'];


                if ($quantity <= 0) {

                    throw new \Exception(
                        "Invalid quantity for {$product->name}."
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | STOCK
                |--------------------------------------------------------------------------
                */

                if ($product->stock_quantity < $quantity) {

                    throw new \Exception(
                        "Insufficient stock for {$product->name}."
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | PRODUCT PRICE
                |--------------------------------------------------------------------------
                */

                $unitPrice = $product->sale_price !== null
                    ? (float) $product->sale_price
                    : (float) $product->price;


                /*
                |--------------------------------------------------------------------------
                | LINE TOTAL
                |--------------------------------------------------------------------------
                */

                $lineTotal = $unitPrice * $quantity;


                /*
                |--------------------------------------------------------------------------
                | SUBTOTAL
                |--------------------------------------------------------------------------
                */

                $subtotal += $lineTotal;


                /*
                |--------------------------------------------------------------------------
                | STORE PRODUCT INFORMATION
                |--------------------------------------------------------------------------
                */

                $products[] = [

                    'product' => $product,

                    'quantity' => $quantity,

                    'unit_price' => $unitPrice,

                    'line_total' => $lineTotal,

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | SHIPPING SETTINGS
            |--------------------------------------------------------------------------
            |
            | These values come from the Shipping Settings page.
            |
            */

            $shippingEnabled = $this->getSetting(
                'shipping_enabled',
                true
            );


            /*
            |--------------------------------------------------------------------------
            | SHIPPING TYPE
            |--------------------------------------------------------------------------
            |
            | Possible examples:
            |
            | flat_rate
            | free
            | city
            | weight
            |
            */

            $shippingType = $this->getSetting(
                'shipping_type',
                'flat_rate'
            );


            /*
            |--------------------------------------------------------------------------
            | DEFAULT SHIPPING AMOUNT
            |--------------------------------------------------------------------------
            */

            $shippingAmount = 0;


            /*
            |--------------------------------------------------------------------------
            | SHIPPING CALCULATION
            |--------------------------------------------------------------------------
            */

            if ($shippingEnabled) {

                /*
                |--------------------------------------------------------------------------
                | FREE SHIPPING
                |--------------------------------------------------------------------------
                */

                if ($shippingType === 'free') {

                    $shippingAmount = 0;
                }


                /*
                |--------------------------------------------------------------------------
                | FLAT RATE SHIPPING
                |--------------------------------------------------------------------------
                */

                elseif ($shippingType === 'flat_rate') {

                    $shippingAmount = (float) $this->getSetting(
                        'shipping_flat_rate',
                        0
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CITY BASED SHIPPING
                |--------------------------------------------------------------------------
                */

                elseif ($shippingType === 'city') {

                    /*
                    |--------------------------------------------------------------------------
                    | Get shipping city rates
                    |--------------------------------------------------------------------------
                    |
                    | Expected format:
                    |
                    | [
                    |     'Lusaka' => 50,
                    |     'Kitwe' => 70,
                    |     'Ndola' => 70,
                    | ]
                    |
                    */

                    $cityRates = $this->getSetting(
                        'shipping_city_rates',
                        []
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Make sure city rates are an array
                    |--------------------------------------------------------------------------
                    */

                    if (is_string($cityRates)) {

                        $decoded = json_decode(
                            $cityRates,
                            true
                        );

                        $cityRates = is_array($decoded)
                            ? $decoded
                            : [];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Find customer city
                    |--------------------------------------------------------------------------
                    */

                    $customerCity = trim(
                        strtolower(
                            $validated['city']
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Find matching city
                    |--------------------------------------------------------------------------
                    */

                    foreach ($cityRates as $city => $rate) {

                        if (
                            strtolower(trim($city))
                            === $customerCity
                        ) {

                            $shippingAmount = (float) $rate;

                            break;
                        }
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | COUNTRY BASED SHIPPING
                |--------------------------------------------------------------------------
                */

                elseif ($shippingType === 'country') {

                    /*
                    |--------------------------------------------------------------------------
                    | Expected format:
                    |
                    | [
                    |     'Zambia' => 50,
                    |     'Zimbabwe' => 150,
                    |     'Malawi' => 150,
                    | ]
                    |--------------------------------------------------------------------------
                    */

                    $countryRates = $this->getSetting(
                        'shipping_country_rates',
                        []
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Decode JSON if necessary
                    |--------------------------------------------------------------------------
                    */

                    if (is_string($countryRates)) {

                        $decoded = json_decode(
                            $countryRates,
                            true
                        );

                        $countryRates = is_array($decoded)
                            ? $decoded
                            : [];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Find customer country
                    |--------------------------------------------------------------------------
                    */

                    $customerCountry = trim(
                        strtolower(
                            $validated['country']
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Find matching country
                    |--------------------------------------------------------------------------
                    */

                    foreach ($countryRates as $country => $rate) {

                        if (
                            strtolower(trim($country))
                            === $customerCountry
                        ) {

                            $shippingAmount = (float) $rate;

                            break;
                        }
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | FREE SHIPPING THRESHOLD
            |--------------------------------------------------------------------------
            */

            $freeShippingEnabled = $this->getSetting(
                'free_shipping_enabled',
                false
            );


            $freeShippingThreshold = (float) $this->getSetting(
                'free_shipping_threshold',
                0
            );


            if (
                $shippingEnabled &&
                $freeShippingEnabled &&
                $freeShippingThreshold > 0 &&
                $subtotal >= $freeShippingThreshold
            ) {

                $shippingAmount = 0;
            }


            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            $totalAmount =
                $subtotal +
                $shippingAmount;


            /*
            |--------------------------------------------------------------------------
            | ORDER NUMBER
            |--------------------------------------------------------------------------
            */

            $orderNumber =
                'ORD-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    Str::random(6)
                );


            /*
            |--------------------------------------------------------------------------
            | PAYMENT STATUS
            |--------------------------------------------------------------------------
            */

            $paymentStatus = 'pending';


            /*
            |--------------------------------------------------------------------------
            | ORDER STATUS
            |--------------------------------------------------------------------------
            */

            $orderStatus = 'pending';


            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER
            |--------------------------------------------------------------------------
            */

            $order = Order::create([

                'order_number' => $orderNumber,

                'customer_email' =>
                    $validated['customer_email'],

                'customer_phone' =>
                    $validated['customer_phone'],


                /*
                |--------------------------------------------------------------------------
                | SHIPPING CUSTOMER
                |--------------------------------------------------------------------------
                */

                'shipping_first_name' =>
                    $validated['first_name'],

                'shipping_last_name' =>
                    $validated['last_name'],

                'shipping_address' =>
                    $validated['address'],

                'shipping_city' =>
                    $validated['city'],

                'shipping_country' =>
                    $validated['country'],


                /*
                |--------------------------------------------------------------------------
                | ORDER AMOUNTS
                |--------------------------------------------------------------------------
                */

                'subtotal' =>
                    $subtotal,

                'shipping_amount' =>
                    $shippingAmount,

                'total_amount' =>
                    $totalAmount,


                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                'payment_method' =>
                    $validated['payment_method'],

                'payment_status' =>
                    $paymentStatus,


                /*
                |--------------------------------------------------------------------------
                | ORDER STATUS
                |--------------------------------------------------------------------------
                */

                'status' =>
                    $orderStatus,
            ]);


            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER HISTORY
            |--------------------------------------------------------------------------
            */

            OrderStatusHistory::create([

                'order_id' =>
                    $order->id,

                'old_status' =>
                    null,

                'new_status' =>
                    'pending',

                'changed_by' =>
                    Auth::id(),

                'note' =>
                    'Order placed.',
            ]);


            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER ITEMS + DEDUCT STOCK
            |--------------------------------------------------------------------------
            */

            foreach ($products as $item) {

                $product =
                    $item['product'];


                /*
                |--------------------------------------------------------------------------
                | CREATE ORDER ITEM
                |--------------------------------------------------------------------------
                */

                OrderItem::create([

                    'order_id' =>
                        $order->id,

                    'product_id' =>
                        $product->id,

                    'product_name' =>
                        $product->name,

                    'sku' =>
                        $product->sku,

                    'quantity' =>
                        $item['quantity'],

                    'unit_price' =>
                        $item['unit_price'],

                    'total_price' =>
                        $item['line_total'],
                ]);


                /*
                |--------------------------------------------------------------------------
                | DEDUCT STOCK
                |--------------------------------------------------------------------------
                */

                $product->decrement(
                    'stock_quantity',
                    $item['quantity']
                );
            }


            /*
            |--------------------------------------------------------------------------
            | RETURN ORDER
            |--------------------------------------------------------------------------
            */

            return $order;
        });


        /*
        |--------------------------------------------------------------------------
        | CLEAR CART
        |--------------------------------------------------------------------------
        */

        session()->forget('cart');


        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO CONFIRMATION
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'checkout.my-confirmation',
            $order
        );
    }

    public function myPayments(Request $request)
    {
        $user = Auth::user();

        $query = Order::query()
            ->where('customer_email', $user->email)
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('payment_status', 'like', "%{$search}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        $payments = $query
            ->paginate(10)
            ->withQueryString();

        return Inertia::render(
            'MyFashions/Clients/Payments/Index',
            [
                'payments' => $payments,

                'filters' => [
                    'search' => $request->search ?? '',
                    'payment_status' =>
                        $request->payment_status ?? '',
                ],
            ]
        );
    }



}
