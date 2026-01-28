<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Get the authenticated user

            
            if ($user->role === 'caregiver') {
                return redirect()->route('caregiver.dashboard');
            } elseif ($user->role === 'patient') {
                return redirect()->route('patient.dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {

                Auth::logout();
                return redirect()->route('login')->with('error', 'Unauthorized role.');
            }
        }

        // If authentication fails
        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    // ✅ Logout method
    public function logout(Request $request)
    {
        Auth::logout(); // Log out the user

        $request->session()->invalidate(); // Optional: invalidate session
        $request->session()->regenerateToken(); // Optional: regenerate CSRF token

        return redirect()->route('auth.login')->with('success', 'You have been logged out.');
    }
}
