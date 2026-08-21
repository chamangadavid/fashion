<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    
/**
     * All Orders
     */
    public function index()
    {
        return Inertia::render('MyFashions/Orders/Index');
    }


    /**
     * Pending Orders
     */
    public function pending()
    {
        return Inertia::render('MyFashions/Orders/Pending');
    }

    /**
     * Processing Orders
     */
    public function processing()
    {
        return Inertia::render('MyFashions/Orders/Processing');
    }


    /**
     * Completed Orders
     */
    public function completed()
    {
        return Inertia::render('MyFashions/Orders/Completed');
    }




}
