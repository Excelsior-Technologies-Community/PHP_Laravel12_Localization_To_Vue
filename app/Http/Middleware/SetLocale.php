<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    private array $allowedLocales = [
        'en',
        'gu',
        'hi',
        'es',
        'fr',
    ];

    public function handle(
        Request $request,
        Closure $next
    ) {
        $locale = Session::get('locale');

        if (!$locale && $request->user()) {
            $locale = $request->user()->preferred_locale;
        }

        if (!in_array($locale, $this->allowedLocales, true)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        Session::put('locale', $locale);

        return $next($request);
    }
}