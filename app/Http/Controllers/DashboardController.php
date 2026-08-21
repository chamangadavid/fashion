<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;


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

}
