<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // Show register page
    public function showRegister()
    {
        return view('backend.auth.register');
    }

    // Handle register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:caregiver,patient',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('backend.auth.login')->with('success', 'Registration successful! Please login.');
    }

    // Show login page
    public function showLogin()
    {
        return view('backend.auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required|string',
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            
            // Redirect based on user role
            $user = Auth::user();
            switch($user->role) {
                case 'patient':
                    return redirect()->route('patient.dashboard');
                case 'caregiver':
                    // Add caregiver dashboard route when available
                    return redirect()->route('caregiver.dashboard'); // Temporary
                case 'admin':
                    // Add admin dashboard route when available
                    return redirect()->route('admin.dashboard'); // Temporary
                default:
                    return redirect()->route('patient.dashboard');
            }
        }

        return back()->withErrors(['email'=>'Invalid credentials'])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('backend.auth.login');
    }

    // Show forgot password
    public function showForgot()
    {
        return view('backend.auth.forgot-password');
    }

    // Send reset link
    public function sendReset(Request $request)
    {
        $request->validate(['email'=>'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Reset link sent to your email!')
            : back()->withErrors(['email'=>'Unable to send reset link']);
    }

    // Show reset password page
    public function showReset(Request $request, $token = null)
    {
        return view('backend.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Handle reset
    public function reset(Request $request)
    {
        $request->validate([
            'token'=>'required',
            'email'=>'required|email|exists:users,email',
            'password'=>'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function($user, $password){
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('backend.auth.login')->with('success','Password reset successfully!')
            : back()->withErrors(['email'=>'Reset failed']);
    }
}
