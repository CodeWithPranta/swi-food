<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
        
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();
         
            if($finduser){
         
                Auth::login($finduser);
                return redirect()->intended('/');
         
            }else{
                $newUser = User::updateOrCreate(['email' => $user->email],[
                        'name' => $user->name,
                        'google_id'=> $user->id,
                        'password' => encrypt('Web#140248est'),
                        'email_verified_at' => now(),
                    ]);
         
                Auth::login($newUser);
        
                return redirect()->intended('landing');
            }
        
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
