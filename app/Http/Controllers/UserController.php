<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Handles registration, login/logout and the user's own dashboard
class UserController extends Controller
{
    // just show the sign up page
    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // email must be unique, and 'confirmed' checks it matches password_confirmation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // every new account starts as a normal "registered" user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // the model auto-hashes this (casts)
            'role' => 'registered',
        ]);

        // log them in straight away so they don't have to type it again
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

        // Auth::attempt hashes the password and checks it against the db for us
        if (Auth::attempt($credentials)) {
            // intended() sends them back to the page they originally wanted
            return redirect()->intended(route('home'));
        }

        // don't say WHICH field was wrong - safer that way
        return back()->withErrors(['email' => 'Wrong email or password']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home'); // redirect back to homepage after logout
    }

    // the logged in user's personal page - only shows listings they own
    public function dashboard()
    {
        $listings = auth()->user()->listings()->with('category')->latest()->get();
        return view('user.dashboard', compact('listings'));
    }
}