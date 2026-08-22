<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            'cartCount' => function (Request $request) {
                return collect(
                    $request->session()->get('cart', [])
                )->sum('quantity');
            },
        ]);

        Vite::prefetch(concurrency: 3);
    }
}
