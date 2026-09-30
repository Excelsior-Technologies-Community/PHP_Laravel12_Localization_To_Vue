<?php

namespace App\Http\Controllers;

use App\Models\LanguageActivity;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LocalizationAnalyticsController extends Controller
{
    public function index(Request $request): Response
    {
        $query = LanguageActivity::query()
            ->with('user')
            ->latest();

        if ($request->filled('locale')) {
            $query->where('to_locale', $request->locale);
        }

        if ($request->filled('from_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        $activities = $query
            ->paginate(10)
            ->withQueryString();

        $totalActivities = LanguageActivity::count();

        $englishSelections = LanguageActivity::where(
            'to_locale',
            'en'
        )->count();

        $gujaratiSelections = LanguageActivity::where(
            'to_locale',
            'gu'
        )->count();

        $todaySelections = LanguageActivity::whereDate(
            'created_at',
            today()
        )->count();

        return Inertia::render('Localization/Analytics', [
            'activities' => $activities,

            'statistics' => [
                'total' => $totalActivities,
                'english' => $englishSelections,
                'gujarati' => $gujaratiSelections,
                'today' => $todaySelections,
            ],

            'filters' => [
                'locale' => $request->locale,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
            ],
        ]);
    }
}