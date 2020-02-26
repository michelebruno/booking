<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        return view('cart');
    }

    public function checkout(Request $request)
    {
        # code...
    }
    
    public function checkoutCompleted(Request $request)
    {
        # code...
    }
}
