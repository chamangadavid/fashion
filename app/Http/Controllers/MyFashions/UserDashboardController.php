<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;

class UserDashboardController extends Controller
{
    
   public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | SESSION CART
        |--------------------------------------------------------------------------
        */

        $cart = $request->session()->get('cart', []);

        $cartItems = array_values($cart);

        $cartItemCount = collect($cart)->sum('quantity');

        $cartSubtotal = collect($cart)->sum(function ($item) {
            $price = $item['sale_price'] ?? $item['price'];

            return (float) $price * (int) $item['quantity'];
        });

        /*
        |--------------------------------------------------------------------------
        | USER ORDERS
        |--------------------------------------------------------------------------
        */

        $ordersQuery = Order::with('items')
            ->where('user_id', $user->id);

        $orders = $ordersQuery
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

        $totalSpent = $orders
            ->whereNotIn('status', ['cancelled'])
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
        | CART
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
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        return Inertia::render('UserDashboard', [
            'auth' => [
                'user' => $user,
            ],

            'cart' => $cartData,

            'orders' => $recentOrders,

            'stats' => [
                'total_orders' => $totalOrders,
                'pending_orders' => $pendingOrders,
                'completed_orders' => $completedOrders,
                'total_spent' => $totalSpent,
            ],
        ]);
    }
}
