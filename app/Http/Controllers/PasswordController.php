<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class PasswordController extends Controller
{
    //
    public function edit()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        // 1. Validate form fields
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'], // Built-in rule checks old password
            'password' => ['required', 'confirmed', Password::defaults()], // Checks strength & match
        ]);

        // 2. Update the password in the users table
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Redirect with a success notification
        return redirect()->route('dashboard')->with('status', 'Password changed successfully!');
    }
}
