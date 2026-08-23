<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FashionSettingsController extends Controller
{
    
/**
     * General Settings
     */
    // public function index()
    // {
    //     return Inertia::render('MyFashions/Settings/Index');
    // }

    public function index()
    {
        return Inertia::render('MyFashions/Settings/Index', [
            'settings' => [
                'store_name' => '',
                'store_email' => '',
                'store_phone' => '',
                'store_address' => '',
                'store_city' => '',
                'store_country' => 'Zambia',
                'currency' => 'ZMW',
                'timezone' => 'Africa/Lusaka',
            ],
        ]);
    }

     public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_email' => ['nullable', 'email', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:50'],
            'store_address' => ['nullable', 'string', 'max:500'],
            'store_city' => ['nullable', 'string', 'max:100'],
            'store_country' => ['nullable', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:10'],
            'timezone' => ['required', 'string', 'max:100'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | For now
        |--------------------------------------------------------------------------
        |
        | We can connect this to a settings table next.
        |
        */

        return back()->with(
            'success',
            'Store settings updated successfully.'
        );
    }
    

    /**
     * Store Settings
     */
    public function store()
    {
        return Inertia::render('MyFashions/Settings/Store');
    }

     /**
     * Payment Settings
     */
    public function payments()
    {
        return Inertia::render('MyFashions/Settings/Payments');
    }

    /**
     * Shipping Settings
     */
    public function shipping()
    {
        return Inertia::render('MyFashions/Settings/Shipping');
    }
    
}
