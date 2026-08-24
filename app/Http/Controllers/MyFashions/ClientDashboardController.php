<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Order;

class ClientDashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CLIENT DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | CART
        |--------------------------------------------------------------------------
        */

        $cart = $request->session()->get('cart', []);

        $cartItems = array_values($cart);

        $cartItemCount = collect($cart)->sum(function ($item) {
            return (int) ($item['quantity'] ?? 0);
        });

        $cartSubtotal = collect($cart)->sum(function ($item) {
            $price = $item['sale_price']
                ?? $item['price']
                ?? 0;

            $quantity = $item['quantity'] ?? 0;

            return (float) $price * (int) $quantity;
        });

        /*
        |--------------------------------------------------------------------------
        | USER ORDERS
        |--------------------------------------------------------------------------
        */

        $orders = Order::with('items')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ORDER STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalOrders = $orders->count();

        $pendingOrders = $orders
            ->whereIn('status', [
                'pending',
                'processing',
            ])
            ->count();

        $completedOrders = $orders
            ->whereIn('status', [
                'completed',
                'delivered',
            ])
            ->count();

        $cancelledOrders = $orders
            ->where('status', 'cancelled')
            ->count();

        $totalSpent = $orders
            ->whereNotIn('status', [
                'cancelled',
            ])
            ->sum('total_amount');

        /*
        |--------------------------------------------------------------------------
        | RECENT ORDERS
        |--------------------------------------------------------------------------
        */

        $recentOrders = $orders
            ->take(5)
            ->map(function ($order) {

                return [
                    'id' => $order->id,

                    'order_number' => $order->order_number,

                    'status' => $order->status,

                    'total' => (float) $order->total_amount,

                    'items_count' => $order->items->sum('quantity'),

                    'created_at' => optional($order->created_at)
                        ->format('d M Y, H:i'),
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | CART DATA
        |--------------------------------------------------------------------------
        */

        $cartData = [
            'items' => $cartItems,

            'item_count' => $cartItemCount,

            'subtotal' => $cartSubtotal,

            'shipping' => 0,

            'total' => $cartSubtotal,
        ];

        /*
        |--------------------------------------------------------------------------
        | RECENT ORDER
        |--------------------------------------------------------------------------
        */

        $recentOrder = $recentOrders->first();

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        return Inertia::render(
            'MyFashions/Clients/Dashboard',
            [
                'auth' => [
                    'user' => $user,
                ],

                'cart' => $cartData,

                'orders' => $recentOrders,

                'recentOrder' => $recentOrder,

                'stats' => [
                    'total_orders' => $totalOrders,

                    'pending_orders' => $pendingOrders,

                    'completed_orders' => $completedOrders,

                    'cancelled_orders' => $cancelledOrders,

                    'total_spent' => (float) $totalSpent,
                ],
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOP
    |--------------------------------------------------------------------------
    */

    public function shop(Request $request)
    {
        return Inertia::render(
            'MyFashions/Clients/Shop'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | You already have:
    |
    | resources/js/Pages/Site/Cart/Index.vue
    |
    | Therefore this method simply redirects to the existing
    | working cart instead of creating another cart page.
    |
    */

    public function cart(Request $request)
    {
        return redirect()->route('cart.index');
    }


    /*
    |--------------------------------------------------------------------------
    | ALL CUSTOMER ORDERS
    |--------------------------------------------------------------------------
    */

    public function orders(Request $request)
    {
        $user = $request->user();

        $orders = Order::with(['items'])
            ->where(function ($query) use ($user) {

                /*
                |--------------------------------------------------------------------------
                | PRIMARY MATCH
                |--------------------------------------------------------------------------
                | Use user_id when the order is properly linked to the customer.
                */

                $query->where('user_id', $user->id)

                    /*
                    |--------------------------------------------------------------------------
                    | FALLBACK MATCH
                    |--------------------------------------------------------------------------
                    | Existing orders have user_id = NULL, so match using
                    | the customer's email address.
                    */

                    ->orWhere(function ($query) use ($user) {

                        $query->whereNull('user_id')
                            ->where('customer_email', $user->email);

                    });

            })
            ->latest()
            ->paginate(10)
            ->through(function ($order) {

                return [
                    'id' => $order->id,

                    'order_number' => $order->order_number,

                    'status' => $order->status,

                    'customer_email' => $order->customer_email,

                    'customer_phone' => $order->customer_phone,

                    'shipping_first_name' => $order->shipping_first_name,

                    'shipping_last_name' => $order->shipping_last_name,

                    'shipping_address' => $order->shipping_address,

                    'shipping_city' => $order->shipping_city,

                    'shipping_country' => $order->shipping_country,

                    'subtotal' => (float) $order->subtotal,

                    'shipping_amount' => (float) $order->shipping_amount,

                    'total_amount' => (float) $order->total_amount,

                    'payment_method' => $order->payment_method,

                    'payment_status' => $order->payment_status,

                    'payment_reference' => $order->payment_reference,

                    'notes' => $order->notes,

                    'created_at' => optional($order->created_at)
                        ->format('d M Y, H:i'),

                    'items' => $order->items
                        ->map(function ($item) {

                            return [
                                'id' => $item->id,

                                'quantity' => (int) $item->quantity,

                                'price' => (float) ($item->price ?? 0),
                            ];

                        })
                        ->values(),
                ];
            });

        return Inertia::render('MyFashions/Clients/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PENDING ORDERS
    |--------------------------------------------------------------------------
    */

    public function show(Order $order)
    {
        $order->load([
            'items.product',
            'statusHistory.user',
        ]);

        return Inertia::render('MyFashions/Clients/Orders/Show', [
            'order' => $order,
        ]);
    }

    public function pendingOrders(Request $request)
    {
        return $this->ordersByStatus(
            $request,
            [
                'pending',
            ],
            'Pending Orders'
        );
    }

    private function ordersByStatus(Request $request, array $statuses,  string $title) 
    {
        $user = $request->user();

        $query = Order::with(['items'])
            ->where('customer_email', $user->email)
            ->whereIn('status', $statuses)
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('MyFashions/Clients/Orders/Pending', [
            'orders' => $orders,
            'title' => $title,
            'filters' => [
                'search' => $request->search ?? '',
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESSING ORDERS
    |--------------------------------------------------------------------------
    */

    public function processingOrders(Request $request)
    {
        return $this->ordersProcessingByStatus(
            $request,
            [
                'processing',
            ],
            'Processing Orders'
        );
    }

    
    private function ordersProcessingByStatus(Request $request, array $statuses,  string $title) 
    {
        $user = $request->user();

        $query = Order::with(['items'])
            ->where('customer_email', $user->email)
            ->whereIn('status', $statuses)
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('MyFashions/Clients/Orders/Processing', [
            'orders' => $orders,
            'title' => $title,
            'filters' => [
                'search' => $request->search ?? '',
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETED ORDERS
    |--------------------------------------------------------------------------
    */

    public function completedOrders(Request $request)
    {
        return $this->ordersCompletedByStatus(
            $request,
            [
                'completed',
                'delivered',
            ],
            'Completed Orders'
        );
    }

    private function ordersCompletedByStatus(Request $request, array $statuses,  string $title) 
    {
        $user = $request->user();

        $query = Order::with(['items'])
            ->where('customer_email', $user->email)
            ->whereIn('status', $statuses)
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('MyFashions/Clients/Orders/Completed', [
            'orders' => $orders,
            'title' => $title,
            'filters' => [
                'search' => $request->search ?? '',
            ],
        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | CANCELLED ORDERS
    |--------------------------------------------------------------------------
    */

    public function cancelledOrders(Request $request)
    {
        return $this->ordersCancelledByStatus($request, [
                'cancelled',
            ],
            'Cancelled Orders'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ORDERS BY STATUS
    |--------------------------------------------------------------------------
    */

    private function ordersCancelledByStatus(Request $request, array $statuses,  string $title) 
    {
        $user = $request->user();

        $query = Order::with(['items'])
            ->where('customer_email', $user->email)
            ->whereIn('status', $statuses)
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('MyFashions/Clients/Orders/Cancelled', [
            'orders' => $orders,
            'title' => $title,
            'filters' => [
                'search' => $request->search ?? '',
            ],
        ]);
    }


    

    /*
    |--------------------------------------------------------------------------
    | PAYMENT HISTORY
    |--------------------------------------------------------------------------
    */

    public function paymentHistory(Request $request)
    {
        $user = $request->user();

        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PAYMENT DATA
        |--------------------------------------------------------------------------
        |
        | This assumes your Order model contains payment-related
        | information such as:
        |
        | payment_method
        | payment_status
        | amount_paid
        |
        | We will adjust this once we see your actual Order model.
        |
        */

        $payments = $orders
            ->map(function ($order) {

                return [
                    'id' => $order->id,

                    'order_id' => $order->id,

                    'order_number' => $order->order_number,

                    'amount' => (float) (
                        $order->amount_paid
                        ?? $order->total_amount
                        ?? 0
                    ),

                    'payment_method' => $order->payment_method
                        ?? null,

                    'payment_status' => $order->payment_status
                        ?? 'pending',

                    'date' => optional($order->created_at)
                        ->format('d M Y, H:i'),
                ];
            })
            ->values();

        return Inertia::render(
            'MyFashions/Clients/Payments/Index',
            [
                'payments' => $payments,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    public function profile(Request $request)
    {
        $user = $request->user();

        return Inertia::render(
            'MyFashions/Clients/Profile/Index',
            [
                'user' => $user,
            ]
        );
    }

}
