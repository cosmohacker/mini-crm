<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Constants\Messages;

class AuthController extends Controller
{
    // Login Form
    public function auth()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('users.index');
            }
            return redirect()->route('tickets.index');
        }

        return view('auth.login');
    }

    // Login Request
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            if (Auth::user()->hasRole('admin')) {
                return redirect()->intended(route('users.index'));
            }
            return redirect()->intended(route('tickets.index'));
        }

        throw ValidationException::withMessages([
            'email' => [Messages::AUTH_CREDENTIALS_DO_NOT_MATCH],
        ]);
    }

    // Logout Request
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
