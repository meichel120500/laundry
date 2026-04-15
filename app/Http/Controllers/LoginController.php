<?php

namespace App\Http\Controllers;

use  Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index (){
        return view("auth.login");
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password'=> ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->id_level == 1) {
                return redirect()->intended('/admin/dashboard');
         } else if ($user->id_level == 2) {
             return redirect()->intended('/operator/orders');
         } else if ($user->id_level == 3) {
             return redirect()->intended('/pimpinan/dashboard');
         }
         return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


    public function logout (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}


