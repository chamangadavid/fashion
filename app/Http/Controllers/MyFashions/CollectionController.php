<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CollectionController extends Controller
{
    
/**
     * All Collections
     */
    public function index()
    {
        return Inertia::render('MyFashions/Collections/Index');
    }

    /**
     * Create Collection
     */
    public function create()
    {
        return Inertia::render('MyFashions/Collections/Create');
    }

    /**
     * Featured Collections
     */
    public function featured()
    {
        return Inertia::render('MyFashions/Collections/Featured');
    }



}
