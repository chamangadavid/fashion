<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class ReportController extends Controller
{
    
     /*
    |--------------------------------------------------------------------------
    | SALES REPORT
    |--------------------------------------------------------------------------
    */

    public function sales(Request $request)
    {
        [$startDate, $endDate] = $this->getDateRange($request);

        /*
        |--------------------------------------------------------------------------
        | TOTAL ORDERS
        |--------------------------------------------------------------------------
        */

        $totalOrders = Order::whereBetween('created_at', [
            $startDate,
            $endDate,
        ])->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL SALES
        |--------------------------------------------------------------------------
        */

        $totalSales = Order::whereBetween('created_at', [
            $startDate,
            $endDate,
        ])
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('total_amount');

        /*
        |--------------------------------------------------------------------------
        | ITEMS SOLD
        |--------------------------------------------------------------------------
        */

        $itemsSold = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->whereNotIn('status', ['cancelled', 'refunded']);
        })->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | AVERAGE ORDER VALUE
        |--------------------------------------------------------------------------
        */

        $averageOrderValue = $totalOrders > 0
            ? $totalSales / $totalOrders
            : 0;

        /*
        |--------------------------------------------------------------------------
        | SALES BY MONTH
        |--------------------------------------------------------------------------
        */

        $salesByMonth = Order::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(total_amount) as total")
            )
            ->whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ORDERS BY STATUS
        |--------------------------------------------------------------------------
        */

        $ordersByStatus = Order::select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->groupBy('status')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TOP SELLING PRODUCTS
        |--------------------------------------------------------------------------
        */

        $topProducts = OrderItem::select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as quantity_sold'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [
                    $startDate,
                    $endDate,
                ])
                ->whereNotIn('status', ['cancelled', 'refunded']);
            })
            ->groupBy(
                'product_id',
                'product_name'
            )
            ->orderByDesc('quantity_sold')
            ->limit(10)
            ->get();


        return Inertia::render('MyFashions/Reports/Sales', [
            'reports' => [
                'date_range' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ],

                'summary' => [
                    'total_orders' => $totalOrders,
                    'total_sales' => $totalSales,
                    'items_sold' => $itemsSold,
                    'average_order_value' => $averageOrderValue,
                ],

                'salesByMonth' => $salesByMonth,

                'ordersByStatus' => $ordersByStatus,

                'topProducts' => $topProducts,
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | REVENUE REPORT
    |--------------------------------------------------------------------------
    */

    public function revenue(Request $request)
    {
        [$startDate, $endDate] = $this->getDateRange($request);

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        */

        $baseQuery = Order::whereBetween('created_at', [
            $startDate,
            $endDate,
        ]);

        /*
        |--------------------------------------------------------------------------
        | TOTAL ORDERS
        |--------------------------------------------------------------------------
        */

        // $totalOrders = (clone $baseQuery)->count();

        $totalOrders = (clone $baseQuery)
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->count();
        /*
        |--------------------------------------------------------------------------
        | GROSS REVENUE
        |--------------------------------------------------------------------------
        |
        | All orders except cancelled/refunded.
        |
        */

        $grossRevenue = (clone $baseQuery)
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | PAID REVENUE
        |--------------------------------------------------------------------------
        */

        $paidRevenue = (clone $baseQuery)
            ->where('payment_status', 'paid')
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | PENDING REVENUE
        |--------------------------------------------------------------------------
        */

        $pendingRevenue = (clone $baseQuery)
            ->where('payment_status', 'pending')
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | FAILED REVENUE
        |--------------------------------------------------------------------------
        */

        $failedRevenue = (clone $baseQuery)
            ->where('payment_status', 'failed')
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | CANCELLED REVENUE
        |--------------------------------------------------------------------------
        */

        $cancelledRevenue = (clone $baseQuery)
            ->where(function ($query) {
                $query
                    ->where('status', 'cancelled')
                    ->orWhere('payment_status', 'cancelled');
            })
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | REFUNDED REVENUE
        |--------------------------------------------------------------------------
        */

        $refundedRevenue = (clone $baseQuery)
            ->where(function ($query) {
                $query
                    ->where('status', 'refunded')
                    ->orWhere('payment_status', 'refunded');
            })
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | REVENUE BY DATE
        |--------------------------------------------------------------------------
        |
        | This matches:
        | revenueByDate in Revenue.vue
        |
        */

        $revenueByDate = (clone $baseQuery)
            ->select(
                DB::raw("DATE(created_at) as date"),
                DB::raw("SUM(total_amount) as revenue")
            )
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy('date')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | REVENUE BY PAYMENT METHOD
        |--------------------------------------------------------------------------
        */

        $revenueByPaymentMethod = (clone $baseQuery)
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | REVENUE BY PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        $revenueByPaymentStatus = (clone $baseQuery)
            ->select(
                'payment_status',
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('payment_status')
            ->orderByDesc('revenue')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN REPORT
        |--------------------------------------------------------------------------
        */

        return Inertia::render('MyFashions/Reports/Revenue', [

            'reports' => [

                /*
                |--------------------------------------------------------------------------
                | DATE RANGE
                |--------------------------------------------------------------------------
                */

                'date_range' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end'   => $endDate->format('Y-m-d'),
                ],


                /*
                |--------------------------------------------------------------------------
                | SUMMARY
                |--------------------------------------------------------------------------
                */

                'summary' => [

                    'gross_revenue' => (float) $grossRevenue,

                    'paid_revenue' => (float) $paidRevenue,

                    'pending_revenue' => (float) $pendingRevenue,

                    'failed_revenue' => (float) $failedRevenue,

                    'refunded_revenue' => (float) $refundedRevenue,

                    'cancelled_revenue' => (float) $cancelledRevenue,

                    'total_orders' => (int) $totalOrders,
                ],


                /*
                |--------------------------------------------------------------------------
                | REVENUE TREND
                |--------------------------------------------------------------------------
                */

                'revenueByDate' => $revenueByDate,


                /*
                |--------------------------------------------------------------------------
                | PAYMENT METHOD
                |--------------------------------------------------------------------------
                */

                'revenueByPaymentMethod' => $revenueByPaymentMethod,


                /*
                |--------------------------------------------------------------------------
                | PAYMENT STATUS
                |--------------------------------------------------------------------------
                */

                'revenueByPaymentStatus' => $revenueByPaymentStatus,
            ],


            /*
            |--------------------------------------------------------------------------
            | FILTERS
            |--------------------------------------------------------------------------
            */

            'filters' => [

                'period' => $request->get('period', 'month'),

                'start_date' => $request->get('start_date', ''),

                'end_date' => $request->get('end_date', ''),
            ],
        ]);
    }


public function customers(Request $request)
{
    [$startDate, $endDate] = $this->getDateRange($request);

    /*
    |--------------------------------------------------------------------------
    | TOTAL CUSTOMERS
    |--------------------------------------------------------------------------
    */

    $totalCustomers = User::whereBetween('created_at', [
        $startDate,
        $endDate,
    ])->count();


    /*
    |--------------------------------------------------------------------------
    | ACTIVE / RETURNING CUSTOMERS
    |--------------------------------------------------------------------------
    */

    $activeCustomers = User::whereHas('orders', function ($query) use ($startDate, $endDate) {

        $query->whereBetween('created_at', [
            $startDate,
            $endDate,
        ]);

    })->count();


    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */

    $totalOrders = Order::whereBetween('created_at', [
        $startDate,
        $endDate,
    ])
        ->whereNotIn('status', ['cancelled', 'refunded'])
        ->count();


    /*
    |--------------------------------------------------------------------------
    | TOTAL REVENUE
    |--------------------------------------------------------------------------
    */

    $totalRevenue = Order::whereBetween('created_at', [
        $startDate,
        $endDate,
    ])
        ->whereNotIn('status', ['cancelled', 'refunded'])
        ->sum('total_amount');


    /*
    |--------------------------------------------------------------------------
    | TOP CUSTOMERS
    |--------------------------------------------------------------------------
    */

    $topCustomers = User::query()
        ->select([
            'users.id',
            'users.name',
            'users.email',
        ])
        ->selectRaw('COUNT(orders.id) as orders')
        ->selectRaw('COALESCE(SUM(orders.total_amount), 0) as revenue')
        ->selectRaw(
            'CASE 
                WHEN COUNT(orders.id) > 0 
                THEN COALESCE(SUM(orders.total_amount), 0) / COUNT(orders.id)
                ELSE 0
             END as average_order'
        )
        ->join('orders', function ($join) use ($startDate, $endDate) {

            $join->on(
                'orders.user_id',
                '=',
                'users.id'
            );

            $join->whereBetween('orders.created_at', [
                $startDate,
                $endDate,
            ]);

            $join->whereNotIn('orders.status', [
                'cancelled',
                'refunded',
            ]);

        })
        ->groupBy(
            'users.id',
            'users.name',
            'users.email'
        )
        ->orderByDesc('revenue')
        ->limit(10)
        ->get()
        ->map(function ($customer) {

            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'orders' => (int) $customer->orders,
                'revenue' => (float) $customer->revenue,
                'average_order' => (float) $customer->average_order,
            ];

        })
        ->values();


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER GROWTH
    |--------------------------------------------------------------------------
    */

    $customerGrowth = User::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as label"),
            DB::raw('COUNT(*) as customers')
        )
        ->whereBetween('created_at', [
            $startDate,
            $endDate,
        ])
        ->groupBy('label')
        ->orderBy('label')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | AVERAGE CUSTOMER VALUE
    |--------------------------------------------------------------------------
    */

    $averageCustomerValue = $activeCustomers > 0
        ? $totalRevenue / $activeCustomers
        : 0;


    /*
    |--------------------------------------------------------------------------
    | RETURN
    |--------------------------------------------------------------------------
    */

    return Inertia::render('MyFashions/Reports/Customers', [

        'filters' => [
            'range' => $request->get('range', 'month'),
            'start_date' => $request->get('start_date', ''),
            'end_date' => $request->get('end_date', ''),
        ],

        'reports' => [

            'date_range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],

            'summary' => [

                'totalCustomers' => $totalCustomers,

                'newCustomers' => $totalCustomers,

                'returningCustomers' => max(
                    0,
                    $activeCustomers - $totalCustomers
                ),

                'totalOrders' => $totalOrders,

                'totalRevenue' => $totalRevenue,

                'averageCustomerValue' => $averageCustomerValue,

            ],

            'topCustomers' => $topCustomers,

            'customerGrowth' => $customerGrowth,

        ],

    ]);
}




    /*
    |--------------------------------------------------------------------------
    | PRODUCT REPORT
    |--------------------------------------------------------------------------
    */

    public function products(Request $request)
    {
        [$startDate, $endDate] = $this->getDateRange($request);

        /*
        |--------------------------------------------------------------------------
        | TOTAL PRODUCTS SOLD
        |--------------------------------------------------------------------------
        */

        $itemsSold = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [
                $startDate,
                $endDate,
            ])
            ->whereNotIn('status', ['cancelled', 'refunded']);
        })->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | PRODUCT PERFORMANCE
        |--------------------------------------------------------------------------
        */

        $products = OrderItem::select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as quantity_sold'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(DISTINCT order_id) as orders_count')
            )
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [
                    $startDate,
                    $endDate,
                ])
                ->whereNotIn('status', ['cancelled', 'refunded']);
            })
            ->groupBy(
                'product_id',
                'product_name'
            )
            ->orderByDesc('revenue')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TOP PRODUCTS
        |--------------------------------------------------------------------------
        */

        $topProducts = $products->take(10)->values();

        return Inertia::render('MyFashions/Reports/Products', [
            'reports' => [
                'date_range' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ],

                'summary' => [
                    'items_sold' => $itemsSold,
                    'products_count' => $products->count(),
                ],

                'products' => $products,

                'top_products' => $topProducts,
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DATE RANGE
    |--------------------------------------------------------------------------
    */

    private function getDateRange(Request $request)
    {
        $range = $request->get('range', 'month');

        switch ($range) {

            case 'today':

                $startDate = Carbon::today();

                $endDate = Carbon::now();

                break;


            case 'week':

                $startDate = Carbon::now()->startOfWeek();

                $endDate = Carbon::now()->endOfWeek();

                break;


            case 'month':

                $startDate = Carbon::now()->startOfMonth();

                $endDate = Carbon::now()->endOfMonth();

                break;


            case 'year':

                $startDate = Carbon::now()->startOfYear();

                $endDate = Carbon::now()->endOfYear();

                break;


            case 'custom':

                $startDate = $request->filled('start_date')
                    ? Carbon::parse($request->start_date)->startOfDay()
                    : Carbon::now()->startOfMonth();

                $endDate = $request->filled('end_date')
                    ? Carbon::parse($request->end_date)->endOfDay()
                    : Carbon::now()->endOfDay();

                break;


            default:

                $startDate = Carbon::now()->startOfMonth();

                $endDate = Carbon::now()->endOfMonth();

                break;
        }

        return [
            $startDate,
            $endDate,
        ];
    }


}
