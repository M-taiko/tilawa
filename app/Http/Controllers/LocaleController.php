<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request)
    {
        $locale = $request->input('locale', 'ar');
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }
        session(['locale' => $locale]);

        return back();
    }
}
