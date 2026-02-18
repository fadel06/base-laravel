<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\CaptchaRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // Generate captcha jika belum ada
        if (!session()->has('captcha')) {
            session(['captcha' => $this->generateCaptcha()]);
        }

        return view('pages.auth.signin');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        $request->validate([
            'login_email'    => ['required', 'email'],
            'login_password' => ['required'],
            'captcha'        => ['required', new CaptchaRule()],
        ], [
            'login_email.required'    => 'Email wajib diisi.',
            'login_email.email'       => 'Format email tidak valid.',
            'login_password.required' => 'Password wajib diisi.',
            'captcha.required'        => 'Captcha wajib diisi.',
        ]);

        $credentials = [
            'email'    => $request->login_email,
            'password' => $request->login_password,
        ];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {

            $request->session()->regenerate();
            session()->forget('captcha');

            alert()->success('Berhasil', 'Selamat datang, ' . Auth::user()->name . '!');
            return redirect()->intended(route('dashboard'));
        }

        session(['captcha' => $this->generateCaptcha()]);

        return back()
            ->withErrors(['login_email' => 'Email atau password salah.'])
            ->withInput($request->except('login_password', 'captcha'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $userName = Auth::user()->name;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        alert()->success('Berhasil', 'Anda telah berhasil logout. Sampai jumpa, ' . $userName . '!');
        return redirect()->route('login');
    }

    /**
     * Refresh captcha via AJAX
     */
    public function refreshCaptcha()
    {
        $captcha = $this->generateCaptcha();
        session(['captcha' => $captcha]);

        return response()->json(['captcha' => $captcha]);
    }

    /**
     * Generate alphanumeric captcha (6 karakter)
     */
    private function generateCaptcha(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captcha    = '';

        for ($i = 0; $i < 6; $i++) {
            $captcha .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $captcha;
    }
}
