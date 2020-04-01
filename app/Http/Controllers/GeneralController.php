<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function returnApp()
    {
        return view('app');
    }

    public function redirectToLogin()
    {
        return redirect('/login');
    }
}
