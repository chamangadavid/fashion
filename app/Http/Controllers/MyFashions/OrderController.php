<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    
/**
     * All Orders
     */
    public function index()
    {
        return Inertia::render('MyFashions/Orders/Index');
    }


    /**
     * Pending Orders
     */
    public function pending()
    {
        return Inertia::render('MyFashions/Orders/Pending');
    }

    /**
     * Processing Orders
     */
    public function processing()
    {
        return Inertia::render('MyFashions/Orders/Processing');
    }


    /**
     * Completed Orders
     */
    public function completed()
    {
        return Inertia::render('MyFashions/Orders/Completed');
    }

     /**
     * Place order
     */
    public function store(Request $request)
    {
        //dd($request->all());
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

            // Address
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

            // Mobile money
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

            $subtotal = 0;

            /*
            |--------------------------------------------------------------------------
            | CHECK PRODUCTS + STOCK
            |--------------------------------------------------------------------------
            */

            $products = [];

            foreach ($cart as $productId => $item) {

                $product = Product::lockForUpdate()
                    ->find($productId);

                if (!$product) {
                    throw new \Exception(
                        'One of the products in your bag no longer exists.'
                    );
                }

                if (!$product->is_active) {
                    throw new \Exception(
                        "{$product->name} is no longer available."
                    );
                }

                $quantity = (int) $item['quantity'];

                if ($quantity <= 0) {
                    throw new \Exception(
                        "Invalid quantity for {$product->name}."
                    );
                }

                if ($product->stock_quantity < $quantity) {
                    throw new \Exception(
                        "Insufficient stock for {$product->name}."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | PRICE
                |--------------------------------------------------------------------------
                */

                $unitPrice = $product->sale_price !== null
                    ? $product->sale_price
                    : $product->price;

                $lineTotal = $unitPrice * $quantity;

                $subtotal += $lineTotal;

                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | ORDER NUMBER
            |--------------------------------------------------------------------------
            */

            $orderNumber =
                'ORD-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(6));

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

                'customer_email' => $validated['customer_email'],

                'customer_phone' => $validated['customer_phone'],

                'shipping_first_name' => $validated['first_name'],

                'shipping_last_name' => $validated['last_name'],

                'shipping_address' => $validated['address'],

                'shipping_city' => $validated['city'],

                'shipping_country' => $validated['country'],

                'subtotal' => $subtotal,

                'shipping_amount' => 0,

                'total_amount' => $subtotal,

                'payment_method' => $validated['payment_method'],

                'payment_status' => $paymentStatus,

                'status' => $orderStatus,

            ]);

            
            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER ITEMS + DEDUCT STOCK
            |--------------------------------------------------------------------------
            */

            foreach ($products as $item) {

                $product = $item['product'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['line_total'],
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
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'checkout.confirmation',
            $order
        );
    }

    /**
     * Show order confirmation page
     */
    public function confirmation(Order $order)
    {
        $order->load([
            'items.product',
        ]);

        return Inertia::render('Site/Checkout/Confirmation', [
            'order' => $order,
        ]);
    }





}
