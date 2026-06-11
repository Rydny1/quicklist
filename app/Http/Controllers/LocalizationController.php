<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    public function switchLanguage($lang)
    {
        if (in_array($lang, ['en', 'lv'])) {
            session()->put('locale', $lang);
        }
        return back();
    }
}
