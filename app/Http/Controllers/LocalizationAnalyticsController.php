<?php

namespace App\Http\Controllers;

use App\Models\LanguageActivity;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LocalizationAnalyticsController extends Controller
{
    private array $locales = [
        'en',
        'gu',
        'hi',
        'es',
        'fr',
    ];

    public function index(Request $request)
    {
        $query = LanguageActivity::query()
            ->with('user');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('from_locale', 'like', "%{$search}%")
                    ->orWhere('to_locale', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Language Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('locale')) {
            $query->where(function ($q) use ($request) {
                $q->where(
                    'from_locale',
                    $request->locale
                )->orWhere(
                    'to_locale',
                    $request->locale
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = [
            'created_at',
            'from_locale',
            'to_locale',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $direction = $direction === 'asc'
            ? 'asc'
            : 'desc';

        $query->orderBy($sort, $direction);

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $activities = $query
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [
            'total' => LanguageActivity::count(),

            'today' => LanguageActivity::whereDate(
                'created_at',
                today()
            )->count(),

            'english' => LanguageActivity::where(
                'to_locale',
                'en'
            )->count(),

            'gujarati' => LanguageActivity::where(
                'to_locale',
                'gu'
            )->count(),

            'hindi' => LanguageActivity::where(
                'to_locale',
                'hi'
            )->count(),

            'spanish' => LanguageActivity::where(
                'to_locale',
                'es'
            )->count(),

            'french' => LanguageActivity::where(
                'to_locale',
                'fr'
            )->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Language Usage
        |--------------------------------------------------------------------------
        */

        $usage = [];

        foreach ($this->locales as $locale) {
            $count = LanguageActivity::where(
                'to_locale',
                $locale
            )->count();

            $usage[$locale] = [
                'count' => $count,
                'percentage' => $statistics['total'] > 0
                    ? round(
                        ($count / $statistics['total']) * 100,
                        2
                    )
                    : 0,
            ];
        }

        return Inertia::render(
            'Localization/Analytics',
            [
                'activities' => $activities,

                'statistics' => $statistics,

                'usage' => $usage,

                'filters' => [
                    'search' => $request->get('search', ''),
                    'locale' => $request->get('locale', ''),
                    'from_date' => $request->get('from_date', ''),
                    'to_date' => $request->get('to_date', ''),
                    'sort' => $sort,
                    'direction' => $direction,
                ],
            ]
        );
    }

    public function export(Request $request): StreamedResponse
    {
        $query = LanguageActivity::query()
            ->with('user');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('from_locale', 'like', "%{$search}%")
                    ->orWhere('to_locale', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('locale')) {
            $query->where(function ($q) use ($request) {
                $q->where(
                    'from_locale',
                    $request->locale
                )->orWhere(
                    'to_locale',
                    $request->locale
                );
            });
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

        $sort = $request->get('sort', 'created_at');

        $direction = $request->get(
            'direction',
            'desc'
        );

        $allowedSorts = [
            'created_at',
            'from_locale',
            'to_locale',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $direction = $direction === 'asc'
            ? 'asc'
            : 'desc';

        $query->orderBy($sort, $direction);

        $filename = 'localization-analytics-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        return response()->streamDownload(
            function () use ($query) {
                $handle = fopen('php://output', 'w');

                fputcsv($handle, [
                    'ID',
                    'User',
                    'Email',
                    'From Language',
                    'To Language',
                    'IP Address',
                    'Date',
                ]);

                $query->chunk(500, function ($activities) use ($handle) {
                    foreach ($activities as $activity) {
                        fputcsv($handle, [
                            $activity->id,
                            $activity->user?->name ?? 'Guest',
                            $activity->user?->email ?? '',
                            $activity->from_locale,
                            $activity->to_locale,
                            $activity->ip_address,
                            optional($activity->created_at)
                                ->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' => 'text/csv',
            ]
        );
    }
}