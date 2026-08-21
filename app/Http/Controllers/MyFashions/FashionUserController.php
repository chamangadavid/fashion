<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FashionUserController extends Controller
{
    
/**
     * All Users
     */
    public function index()
    {
        return Inertia::render('MyFashions/Users/Index');
    }

    /**
     * Create User
     */
    public function create()
    {
        return Inertia::render('MyFashions/Users/Create');
    }

    /**
     * Roles & Permissions
     */
    public function roles()
    {
        return Inertia::render('MyFashions/Users/Roles');
    }

}
