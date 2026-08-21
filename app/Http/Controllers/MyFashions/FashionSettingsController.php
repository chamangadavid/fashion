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
    public function index()
    {
        return Inertia::render('MyFashions/Settings/Index');
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
