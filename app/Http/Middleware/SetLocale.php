<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = Session::get('locale');

        if (!$locale && $request->user()) {
            $locale = $request->user()->preferred_locale;
        }

        if (!in_array($locale, ['en', 'gu'])) {
            $locale = 'en';
        }

        App::setLocale($locale);

        Session::put('locale', $locale);

        return $next($request);
    }
}