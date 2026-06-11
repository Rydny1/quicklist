<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed', // 'confirmed' checks it matches password_confirmation
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'registered',
        ]);

        // log them in right away so they don't have to do it manually after registering
        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        return redirect()->route('home');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // intended() sends them to the page they were originally trying to open
            return redirect()->intended(route('home'));
        }

        // don't say which field was wrong
        return back()->withErrors(['email' => 'Wrong email or password']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function dashboard()
    {
        $listings = auth()->user()->listings()->with('category')->latest()->get();
        return view('user.dashboard', compact('listings'));
    }
}
