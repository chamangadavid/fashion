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

class OrderController extends Controller
{
    
/**
     * All Orders
     */
    // public function index()
    // {
    //     return Inertia::render('MyFashions/Orders/Index');
    // }

    /**
     * Display all orders
     */
    // public function index()
    // {
    //     $orders = Order::with('items')
    //         ->latest()
    //         ->get();

    //     return Inertia::render('MyFashions/Orders/Index', [
    //         'orders' => $orders,
    //     ]);
    // }


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

    /**
 * Display pending orders
 */
// public function pending()
// {
//     $orders = Order::with('items')
//         ->where('status', 'pending')
//         ->latest()
//         ->get();

//     return Inertia::render('MyFashions/Orders/Pending', [
//         'orders' => $orders,
//     ]);
// }

/**
 * Display processing orders
 */
// public function processing()
// {
//     $orders = Order::with('items')
//         ->where('status', 'processing')
//         ->latest()
//         ->get();

//     return Inertia::render('MyFashions/Orders/Processing', [
//         'orders' => $orders,
//     ]);
// }

/**
 * Display completed orders
 */
// public function completed()
// {
//     $orders = Order::with('items')
//         ->where('status', 'completed')
//         ->latest()
//         ->get();

//     return Inertia::render('MyFashions/Orders/Completed', [
//         'orders' => $orders,
//     ]);
// }

/**
 * Display a single order
 */
// public function show(Order $order)
// {
//     $order->load([
//         'items.product',
//     ]);

//     return Inertia::render('MyFashions/Orders/ViewOrder', [
//         'order' => $order,
//     ]);
// }

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

// public function show(Order $order)
// {
//     $order->load([
//         'items.product',
//     ]);

//     return Inertia::render('MyFashions/Orders/View', [
//         'order' => $order,
//     ]);
// }




    /**
     * Pending Orders
     */
    // public function pending()
    // {
    //     return Inertia::render('MyFashions/Orders/Pending');
    // }

    /**
     * Processing Orders
     */
    // public function processing()
    // {
    //     return Inertia::render('MyFashions/Orders/Processing');
    // }


    /**
     * Completed Orders
     */
    // public function completed()
    // {
    //     return Inertia::render('MyFashions/Orders/Completed');
    // }

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
            | CREATE ORDER HISTORY
            |--------------------------------------------------------------------------
            */

            OrderStatusHistory::create([
                'order_id' => $order->id,

                'old_status' => null,

                'new_status' => 'pending',

                'changed_by' => Auth::id(),

                'note' => 'Order placed.',
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


    public function updateStatus(
    Request $request,
    Order $order
) {
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




//     public function updateStatus(Request $request, Order $order)
// {
//     $validated = $request->validate([
//         'status' => [
//             'required',
//             'in:pending,processing,completed,cancelled',
//         ],
//     ]);

//     $newStatus = $validated['status'];
//     $oldStatus = $order->status;

//     // Nothing to do
//     if ($oldStatus === $newStatus) {
//         return back()->with('info', 'Order status is already ' . $newStatus . '.');
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | CANCEL ORDER
//     |--------------------------------------------------------------------------
//     |
//     | Stock was already deducted when the order was placed.
//     |
//     | Therefore:
//     | pending/processing/completed -> cancelled
//     | restores stock.
//     |
//     | We do NOT restore stock if the order is already cancelled.
//     |
//     */

//     if ($newStatus === 'cancelled') {

//         // Do not allow cancelling an already cancelled order
//         if ($oldStatus === 'cancelled') {
//             return back()->with(
//                 'info',
//                 'This order has already been cancelled.'
//             );
//         }

//         DB::transaction(function () use ($order) {

//             $order->load('items');

//             foreach ($order->items as $item) {

//                 $product = Product::lockForUpdate()
//                     ->find($item->product_id);

//                 if (!$product) {
//                     throw new \Exception(
//                         "Product {$item->product_name} no longer exists."
//                     );
//                 }

//                 /*
//                 |--------------------------------------------------------------------------
//                 | RESTORE STOCK
//                 |--------------------------------------------------------------------------
//                 */

//                 $product->increment(
//                     'stock_quantity',
//                     $item->quantity
//                 );
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | UPDATE ORDER STATUS
//             |--------------------------------------------------------------------------
//             */

//             $order->update([
//                 'status' => 'cancelled',
//             ]);
//         });

//         return back()->with(
//             'success',
//             'Order cancelled and stock restored successfully.'
//         );
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | NORMAL STATUS UPDATE
//     |--------------------------------------------------------------------------
//     */

//     $order->update([
//         'status' => $newStatus,
//     ]);

//     return back()->with(
//         'success',
//         'Order status updated successfully.'
//     );
// }





}
