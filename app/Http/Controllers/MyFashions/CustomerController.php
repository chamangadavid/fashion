<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    
 /**
     * All Customers
     */
    public function index()
    {
        return Inertia::render('MyFashions/Customers/Index');
    }

    /**
     * Customer Groups
     */
    public function groups()
    {
        return Inertia::render('MyFashions/Customers/Groups');
    }

    /**
     * VIP Customers
     */
    public function vip()
    {
        return Inertia::render('MyFashions/Customers/Vip');
    }



}
