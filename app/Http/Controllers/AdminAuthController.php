<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    //
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login with the provided credentials
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Check if the authenticated user is actually an admin
            if (Auth::user()->is_admin) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            // Immediately clear non-admin auth attempts
            Auth::logout();
            return back()->withErrors(['email' => 'You do not have administrative privileges.']);
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }
    public function dashboard()
    {
        // Simple extra check just in case, though middleware handles it
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect()->route('admin.login');
        }

        return view('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

}
