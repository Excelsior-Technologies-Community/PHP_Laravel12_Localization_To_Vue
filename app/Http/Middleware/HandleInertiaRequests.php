<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [

            'locale' => app()->getLocale(),

            'language' => function () {

                $locale = app()->getLocale();

                $path = base_path("lang/{$locale}.json");

                if (!file_exists($path)) {
                    return [];
                }

                return json_decode(
                    file_get_contents($path),
                    true
                ) ?? [];
            },

            'availableLocales' => [
                [
                    'code' => 'en',
                    'name' => 'English',
                    'native_name' => 'English',
                ],
                [
                    'code' => 'gu',
                    'name' => 'Gujarati',
                    'native_name' => 'ગુજરાતી',
                ],
            ],

        ]);
    }
}