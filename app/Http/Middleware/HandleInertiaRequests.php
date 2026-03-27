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

                $path = base_path("lang/$locale.json");

                if (!file_exists($path)) {

                    return [
                        'welcome_message' => 'Welcome',
                        'login' => 'Login',
                        'register' => 'Register',
                        'dashboard_title' => 'Jewelry Dashboard',
                        'welcome_user' => 'Welcome',
                        'gold_stock' => 'Gold Items',
                        'silver_stock' => 'Silver Items',
                        'diamond_stock' => 'Diamond Items',
                        'recent_items' => 'Recent Jewelry Added'
                    ];
                }

                return json_decode(file_get_contents($path), true);
            },

        ]);
    }
}