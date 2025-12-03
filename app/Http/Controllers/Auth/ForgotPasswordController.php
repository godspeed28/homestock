<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Jobs\SendEmailResetPassword;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form forgot password
     */
    public function showForm()
    {
        return view('auth.forgotpass');
    }

    /**
     * Kirim link reset password
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        // Rate limiting
        $key = $this->throttleKey($request);
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            Log::warning("Too many password reset attempts from IP: {$request->ip()}, email: {$request->input('email', '')}");
            return back()->withErrors([
                'email' => "Too many reset attempts. Please try again in {$seconds} seconds."
            ])->withInput($request->only('email'));
        }

        // Increment attempts
        RateLimiter::hit($key, 300); // batasi 3 percobaan per 5 menit

        // Kirim reset link
        SendEmailResetPassword::dispatch($request->only('email'));

        // Clear attempts langsung (email sudah dijadwalkan terkirim)
        RateLimiter::clear($key);

        Log::info("Sending reset email to: {$request->email} from IP {$request->ip()}");

        return back()->with('success', 'If the email exists, a reset link will be sent.');
    }

    /**
     * Generate throttle key (safe walau email kosong)
     */
    private function throttleKey(Request $request)
    {
        return Str::lower('forgot-password|' . $request->ip() . '|' . $request->input('email', ''));
    }

    /**
     * Pesan error user-friendly
     */
    private function getErrorMessage($status)
    {
        return match ($status) {
            Password::INVALID_USER => 'If this email exists, we have sent a reset link to it.',
            Password::RESET_THROTTLED => 'Please wait before requesting another reset link.',
            default => 'If this email exists, we have sent a reset link to it.',
        };
    }
}
