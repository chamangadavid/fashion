<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $cart = session()->get('cart', []);

        $cartCount = collect($cart)->sum(function ($item) {
            return (int) ($item['quantity'] ?? 0);
        });

        $cartTotal = collect($cart)->sum(function ($item) {
            return (float) ($item['price'] ?? 0)
                * (int) ($item['quantity'] ?? 0);
        });

        return [
            ...parent::share($request),

            'auth' => [
                'user' => $request->user()
                    ? [
                        ...$request->user()->toArray(),
                        'roles' => $request->user()->getRoleNames(),
                    ]
                    : null,

                'permissions' => $request->user()
                    ? $request->user()->getAllPermissions()->pluck('name')
                    : [],
            ],

            'cart' => [
                'item_count' => $cartCount,
                'total' => $cartTotal,
            ],
        ];
    }

    // public function share(Request $request): array
    // {
        

    //     return [
    //         ...parent::share($request),
    //         'auth' => [
    //             //'user' => $request->user(),
    //             'user' => $request->user() ? [
    //             ...$request->user()->toArray(),
    //             'roles' => $request->user()->getRoleNames() // Add this line
    //             ] : null,
                
    //             'permissions' => $request->user()
    //             ? $request->user()->getAllPermissions()->pluck('name')
    //             : [],
    //         ],
    //     ];
    // }
}
