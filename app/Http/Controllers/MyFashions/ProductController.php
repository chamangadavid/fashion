<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    
/**
     * All Products
     */
    public function index()
    {
        return Inertia::render('MyFashions/Products/Index');
    }

    /**
     * Create Product
     */
    public function create()
    {
        return Inertia::render('MyFashions/Products/Create');
    }

     /**
     * Product Categories
     */
    public function categories()
    {
        return Inertia::render('MyFashions/Products/Categories');
    }

    /**
     * Product Inventory
     */
    public function inventory()
    {
        return Inertia::render('MyFashions/Products/Inventory');
    }

}
