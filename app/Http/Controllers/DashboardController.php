<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
    

    // public function fashionDashboard()
    // {
    //     return Inertia::render('MyFashions/Dashboard');
    // }


public function index()
{
    $user = auth()->user();

    $cart = $user->cart()
        ->with('items.product')
        ->first();

    $orders = $user->orders()
        ->latest()
        ->take(5)
        ->get()
        ->map(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total' => $order->total,
                'items_count' => $order->items()->count(),
                'created_at' => $order->created_at->format('d M Y'),
            ];
        });

    $payments = $user->payments()
        ->latest()
        ->take(5)
        ->get()
        ->map(function ($payment) {
            return [
                'id' => $payment->id,
                'reference' => $payment->reference,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'status' => $payment->status,
            ];
        });

    return Inertia::render('UserDashboard', [
        'cart' => [
            'items' => $cart?->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'image' => $item->product->image
                        ? asset('storage/' . $item->product->image)
                        : null,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'variant' => $item->variant ?? null,
                ];
            }) ?? [],

            'subtotal' => $cart?->subtotal ?? 0,
            'shipping' => $cart?->shipping ?? 0,
            'total' => $cart?->total ?? 0,
            'item_count' => $cart?->items->sum('quantity') ?? 0,
        ],

        'orders' => $orders,

        'payments' => $payments,

        'stats' => [
            'total_orders' => $user->orders()->count(),

            'pending_orders' => $user->orders()
                ->whereIn('status', [
                    'pending',
                    'processing',
                    'shipped',
                ])
                ->count(),

            'completed_orders' => $user->orders()
                ->whereIn('status', [
                    'completed',
                    'delivered',
                ])
                ->count(),

            'total_spent' => $user->orders()
                ->whereIn('status', [
                    'completed',
                    'delivered',
                ])
                ->sum('total'),
        ],
    ]);
}



public function dashboard(Request $request)
{
    $user = $request->user();

    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */

    $roles = method_exists($user, 'getRoleNames')
        ? $user->getRoleNames()->values()->toArray()
        : [];


    /*
    |--------------------------------------------------------------------------
    | PERMISSIONS
    |--------------------------------------------------------------------------
    */

    $permissions = method_exists($user, 'getAllPermissions')
        ? $user->getAllPermissions()
            ->pluck('name')
            ->values()
            ->toArray()
        : [];


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD STATISTICS
    |--------------------------------------------------------------------------
    */

    $stats = [

        'total_orders' => Order::count(),

        'pending_orders' => Order::where(
            'status',
            'pending'
        )->count(),

        'processing_orders' => Order::where(
            'status',
            'processing'
        )->count(),

        'completed_orders' => Order::where(
            'status',
            'completed'
        )->count(),

        'cancelled_orders' => Order::where(
            'status',
            'cancelled'
        )->count(),

        'total_revenue' => Order::where(
            'status',
            'completed'
        )->sum('total_amount'),

        'total_customers' => User::count(),

        'products' => Product::count(),
    ];


    /*
    |--------------------------------------------------------------------------
    | RECENT ORDERS
    |--------------------------------------------------------------------------
    */

    $orders = Order::with('items')
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($order) {

            return [

                'id' => $order->id,

                'order_number' => $order->order_number,

                'customer_name' => trim(
                    ($order->shipping_first_name ?? '') .
                    ' ' .
                    ($order->shipping_last_name ?? '')
                ),

                'product' => $order->items
                    ->pluck('product_name')
                    ->implode(', '),

                'amount' => (float) $order->total_amount,

                'status' => $order->status,

                'date' => $order->created_at
                    ? $order->created_at->diffForHumans()
                    : null,
            ];
        });


    /*
    |--------------------------------------------------------------------------
    | RECENT REPORTS
    |--------------------------------------------------------------------------
    */

    $reports = [

        [
            'type' => 'Sales',
            'title' => 'Sales Report',
            'date' => now()->format('d M Y'),
            'url' => route('fashion.reports.sales'),
        ],

        [
            'type' => 'Revenue',
            'title' => 'Revenue Report',
            'date' => now()->format('d M Y'),
            'url' => route('fashion.reports.revenue'),
        ],

        [
            'type' => 'Customers',
            'title' => 'Customer Report',
            'date' => now()->format('d M Y'),
            'url' => route('fashion.reports.customers'),
        ],

        [
            'type' => 'Products',
            'title' => 'Product Report',
            'date' => now()->format('d M Y'),
            'url' => route('fashion.reports.products'),
        ],
    ];


    /*
    |--------------------------------------------------------------------------
    | THREAD FEED
    |--------------------------------------------------------------------------
    */

    // $threadFeed = [];
    /*
|--------------------------------------------------------------------------
| RECENT PRODUCTS
|--------------------------------------------------------------------------
*/

$products = Product::with('category')
    ->latest()
    ->take(6)
    ->get()
    ->map(function ($product) {

        return [
            'id' => $product->id,

            'name' => $product->name,

            'slug' => $product->slug,

            'sku' => $product->sku,

            'category' => $product->category?->name,

            'price' => (float) $product->price,

            'sale_price' => $product->sale_price !== null
                ? (float) $product->sale_price
                : null,

            'stock_quantity' => (int) $product->stock_quantity,

            'low_stock_threshold' => (int) $product->low_stock_threshold,

            'image' => $product->image,

            'is_active' => (bool) $product->is_active,

            'is_featured' => (bool) $product->is_featured,

            'date' => $product->created_at
                ? $product->created_at->diffForHumans()
                : null,
        ];
    });


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD TYPE
    |--------------------------------------------------------------------------
    */

    $isUser = in_array(
        'Users',
        $roles,
        true
    );

    $isSuperAdmin = in_array(
        'Super Admin',
        $roles,
        true
    );

    $isAdminOrStaff =
        $isSuperAdmin ||
        in_array('manage access control', $permissions, true) ||
        in_array('manage staff access control', $permissions, true);


    /*
    |--------------------------------------------------------------------------
    | RETURN DASHBOARD
    |--------------------------------------------------------------------------
    */

    return Inertia::render('Dashboard', [

        'auth' => [

            'user' => $user,

            'roles' => $roles,

            'permissions' => $permissions,

        ],

        'dashboard_type' =>
            $isUser
                ? 'user'
                : ($isAdminOrStaff ? 'admin' : 'none'),

        'stats' => $stats,

        'orders' => $orders,

        'products' => $products,

        'reports' => $reports,

        'charts' => [],
    ]);
}




}
