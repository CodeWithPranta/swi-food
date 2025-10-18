<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        return view('account.index');
    }

    public function upgradeAccount(Request $request)
    {
        // Logic to upgrade account
        if (Auth::user()->user_type === 2) {
            return redirect()->route('account.index')->with('status', 'Your account is already a vendor account.');
        }else{
            $user = Auth::user();
            $user->user_type = 2; // Set user type to vendor
            $user->save();
        }
        return redirect()->route('account.index')->with('status', 'Account upgraded successfully!');
    }
}
