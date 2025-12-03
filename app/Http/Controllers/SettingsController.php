<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Tetap menggunakan Auth:: karena di blade kita panggil Auth
        $data = Auth::user();
        return view('pages.settings.index', compact('data'));
    }

    public function update(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'current_password' => ['required', 'current_password'],
            'password' => 'nullable|string|min:8|confirmed',
            'whatsapp_number' => ['nullable', 'string', 'regex:/^62\d{9,13}$/'],
        ], [
            'whatsapp_number.regex' => 'Format nomor WhatsApp tidak valid. Contoh: 6281234567890',
            'current_password.current_password' => 'Password saat ini tidak valid',
        ]);

        $user = User::findOrFail($userId);

        // Format WhatsApp number
        $whatsapp = $request->whatsapp_number;
        if ($whatsapp) {
            // Pastikan formatnya 62...
            if (!str_starts_with($whatsapp, '62')) {
                $whatsapp = '62' . ltrim($whatsapp, '0');
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->whatsapp_number = $whatsapp;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
