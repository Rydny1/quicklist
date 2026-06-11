<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Lets the user switch between English and Latvian
class LocalizationController extends Controller
{
    public function switchLanguage($lang)
    {
        // only allow the two languages we actually support, ignore anything else
        if (in_array($lang, ['en', 'lv'])) {
            session()->put('locale', $lang); // SetLocale middleware reads this on every request
        }
        return back(); // stay on whatever page they were on
    }
}