<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\User;

class CustomerController extends Controller
{

    public function customers(Request $request)
    {
        $search = $request->input('search');


        /*
        |--------------------------------------------------------------------------
        | CUSTOMER EMAILS
        |--------------------------------------------------------------------------
        */

        $query = Order::query()
            ->select('customer_email')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(total_amount) as total_spent')
            ->selectRaw('MAX(created_at) as last_order_at')
            ->whereNotNull('customer_email');


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $query->where(function ($q) use ($search) {

                $q->where('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('shipping_first_name', 'like', "%{$search}%")
                    ->orWhere('shipping_last_name', 'like', "%{$search}%");

            });

        }


        $customers = $query
            ->groupBy('customer_email')
            ->orderByDesc('last_order_at')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | ADD CUSTOMER DETAILS
        |--------------------------------------------------------------------------
        */

        $customers->getCollection()->transform(
            function ($customer) {

                $latestOrder = Order::where(
                        'customer_email',
                        $customer->customer_email
                    )
                    ->latest()
                    ->first();

                $customer->customer_phone =
                    $latestOrder?->customer_phone;

                $customer->shipping_first_name =
                    $latestOrder?->shipping_first_name;

                $customer->shipping_last_name =
                    $latestOrder?->shipping_last_name;

                return $customer;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalCustomers = Order::query()
            ->whereNotNull('customer_email')
            ->distinct()
            ->count('customer_email');


        $totalOrders = Order::query()
            ->whereNotNull('customer_email')
            ->count();


        $totalRevenue = Order::query()
            ->whereNotNull('customer_email')
            ->sum('total_amount');


        $averageOrderValue = $totalOrders > 0
            ? $totalRevenue / $totalOrders
            : 0;


        return Inertia::render(
            'MyFashions/Customers/Index',
            [

                'customers' => $customers,

                'statistics' => [

                    'total_customers' =>
                        $totalCustomers,

                    'total_orders' =>
                        $totalOrders,

                    'total_revenue' =>
                        $totalRevenue,

                    'average_order_value' =>
                        $averageOrderValue,

                ],

                'filters' => [

                    'search' =>
                        $request->search ?? '',

                ],

            ]
        );
    }

    public function show($customer)
    {
        $email = urldecode($customer);

        $orders = Order::where('customer_email', $email)
            ->with('items')
            ->latest()
            ->get();

        if ($orders->isEmpty()) {
            abort(404, 'Customer not found.');
        }

        $latestOrder = $orders->first();

        $customerData = [
            'customer_email' => $email,
            'customer_phone' => $latestOrder?->customer_phone,
            'shipping_first_name' => $latestOrder?->shipping_first_name,
            'shipping_last_name' => $latestOrder?->shipping_last_name,
            'created_at' => $latestOrder?->created_at,
        ];

        return Inertia::render(
            'MyFashions/Customers/ViewCustomer',
            [
                'customer' => $customerData,
                'orders' => $orders,
            ]
        );
    }

    public function groups()
    {
        $customers = Order::query()
            ->select('customer_email')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(total_amount) as orders_sum_total_amount')
            ->selectRaw('MAX(created_at) as last_order_at')
            ->whereNotNull('customer_email')
            ->groupBy('customer_email')
            ->orderByDesc('last_order_at')
            ->get();

        $customers->transform(function ($customer) {

            $latestOrder = Order::where(
                'customer_email',
                $customer->customer_email
            )
            ->latest()
            ->first();

            $customer->id = md5($customer->customer_email);

            $customer->name = trim(
                ($latestOrder?->shipping_first_name ?? '') . ' ' .
                ($latestOrder?->shipping_last_name ?? '')
            );

            $customer->email = $customer->customer_email;

            $customer->customer_phone =
                $latestOrder?->customer_phone;

            return $customer;
        });

        return Inertia::render(
            'MyFashions/Customers/Groups',
            [
                'customers' => $customers,
            ]
        );
    }

    public function vip()
    {
        $customers = Order::query()
            ->select('customer_email')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw("
                SUM(
                    CASE
                        WHEN status NOT IN ('cancelled','refunded')
                        THEN total_amount
                        ELSE 0
                    END
                ) as total_spent
            ")
            ->selectRaw('MAX(created_at) as last_order_at')
            ->whereNotNull('customer_email')
            ->groupBy('customer_email')
            ->havingRaw('SUM(CASE WHEN status NOT IN ("cancelled","refunded") THEN total_amount ELSE 0 END) >= 20000')
            ->orderByDesc('total_spent')
            ->get();

        $customers->transform(function ($customer) {

            $latestOrder = Order::where(
                'customer_email',
                $customer->customer_email
            )
            ->latest()
            ->first();

            $customer->name = trim(
                ($latestOrder?->shipping_first_name ?? '') . ' ' .
                ($latestOrder?->shipping_last_name ?? '')
            );

            $customer->first_name = $latestOrder?->shipping_first_name;
            $customer->last_name = $latestOrder?->shipping_last_name;
            $customer->email = $customer->customer_email;
            $customer->phone = $latestOrder?->customer_phone;

            return $customer;
        });

        return Inertia::render('MyFashions/Customers/Vip', [
            'customers' => $customers,
        ]);
    }



}
