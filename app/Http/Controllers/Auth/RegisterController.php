<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    // Menampilkan form register
    public function showForm()
    {
        return view('auth.register');
    }

    // Logic register
    public function register(Request $request)
    {
        // Validasi
        $request->validate([
            'username' => 'required|string|max:255|unique:users,name',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'username.unique' => 'This username is already taken.',
            'email.unique' => 'This email is already registered.'
        ]);

        try {
            // Create user
            User::create([
                'name'     => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('login')->with('success', 'Registration successful! Please login.');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()->with('error', 'Registration failed. Please try again.')->withInput();
        }
    }
}
