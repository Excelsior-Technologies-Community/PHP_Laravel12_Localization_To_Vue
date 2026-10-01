<?php

namespace App\Http\Controllers;

use App\Models\LanguageActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    private array $allowedLocales = [
        'en',
        'gu',
        'hi',
        'es',
        'fr',
    ];

    public function switch(
        Request $request,
        string $locale
    ): RedirectResponse {
        if (!in_array($locale, $this->allowedLocales, true)) {
            return redirect()->back();
        }

        $oldLocale = Session::get(
            'locale',
            $request->user()?->preferred_locale ?? 'en'
        );

        Session::put('locale', $locale);

        if ($request->user()) {
            $request->user()->update([
                'preferred_locale' => $locale,
            ]);
        }

        if ($oldLocale !== $locale) {
            LanguageActivity::create([
                'user_id' => $request->user()?->id,
                'from_locale' => $oldLocale,
                'to_locale' => $locale,
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()->back();
    }

    public function updatePreference(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'locale' => [
                'required',
                'in:' . implode(',', $this->allowedLocales),
            ],
        ]);

        $oldLocale = Session::get(
            'locale',
            $request->user()->preferred_locale ?? 'en'
        );

        $locale = $validated['locale'];

        $request->user()->update([
            'preferred_locale' => $locale,
        ]);

        Session::put('locale', $locale);

        if ($oldLocale !== $locale) {
            LanguageActivity::create([
                'user_id' => $request->user()->id,
                'from_locale' => $oldLocale,
                'to_locale' => $locale,
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()
            ->route('language.settings')
            ->with(
                'success',
                __('language.preference_updated')
            );
    }
}