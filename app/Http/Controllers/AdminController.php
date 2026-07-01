<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.dashboard'); 
    }
    public function showLogin() {
        return view('auth.login');
    }
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role
            if (auth()->user()->is_admin) {
                return redirect()->intended('admin/dashboard');
            }
            return redirect()->intended('/home');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }
}
