<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        $remember = $request->boolean('remember');

        // Attempt Login
        if (!Auth::attempt([
            'email'    => $validated['email'],
            'password' => $validated['password']
        ], $remember)) {

            return back()
                ->withErrors([
                    'email' => 'Invalid email or password.'  // generic error (security)
                ])
                ->withInput($request->except('password'));
        }

        // Regenerate session for security
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
