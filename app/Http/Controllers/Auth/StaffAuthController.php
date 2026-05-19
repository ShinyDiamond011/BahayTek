<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('staff')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $staffCredentials = array_merge($credentials, ['status' => 'active']);

        if (Auth::guard('staff')->attempt($staffCredentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $staff = Auth::guard('staff')->user();
            $staff->update(['last_login_at' => now()]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or your account is inactive.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
