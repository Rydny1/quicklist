<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Runs on every request and applies the language the user picked
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // if they've chosen a language before, switch the app to it
        if (session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        }
        // no choice yet = falls back to the default locale in config
        return $next($request);
    }
}