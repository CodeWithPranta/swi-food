<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('homestaurant.application');
    }
}
