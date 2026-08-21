<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    
/**
     * Sales Reports
     */
    public function sales()
    {
        return Inertia::render('MyFashions/Reports/Sales');
    }

    /**
     * Product Reports
     */
    public function products()
    {
        return Inertia::render('MyFashions/Reports/Products');
    }

    /**
     * Customer Reports
     */
    public function customers()
    {
        return Inertia::render('MyFashions/Reports/Customers');
    }

    /**
     * Revenue Reports
     */
    public function revenue()
    {
        return Inertia::render('MyFashions/Reports/Revenue');
    }


}
