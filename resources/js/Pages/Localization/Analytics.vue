<script setup>
import { computed, reactive } from 'vue';

import {
    Head,
    router,
    usePage,
} from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();

const lang = computed(
    () => page.props.language || {}
);

const availableLocales = computed(
    () => page.props.availableLocales || []
);

const props = defineProps({
    activities: Object,
    statistics: Object,
    usage: Object,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
    locale: props.filters?.locale || '',
    from_date: props.filters?.from_date || '',
    to_date: props.filters?.to_date || '',
    sort: props.filters?.sort || 'created_at',
    direction: props.filters?.direction || 'desc',
});

const applyFilters = () => {
    router.get(
        route('localization.analytics'),
        filters,
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const resetFilters = () => {
    filters.search = '';
    filters.locale = '';
    filters.from_date = '';
    filters.to_date = '';
    filters.sort = 'created_at';
    filters.direction = 'desc';

    router.get(
        route('localization.analytics')
    );
};

const exportCsv = () => {
    const params = new URLSearchParams();

    Object.entries(filters).forEach(
        ([key, value]) => {
            if (value) {
                params.append(key, value);
            }
        }
    );

    window.location.href =
        route('localization.analytics.export') +
        '?' +
        params.toString();
};

const languageName = (locale) => {
    const language =
        availableLocales.value.find(
            item => item.code === locale
        );

    return language?.native_name ||
        language?.name ||
        locale ||
        '-';
};

const languageFlag = (locale) => {
    const language =
        availableLocales.value.find(
            item => item.code === locale
        );

    return language?.flag || '';
};

const formatDate = (date) => {
    if (!date) {
        return '-';
    }

    const languageMap = {
        en: 'en-IN',
        gu: 'gu-IN',
        hi: 'hi-IN',
        es: 'es-ES',
        fr: 'fr-FR',
    };

    return new Date(date).toLocaleString(
        languageMap[page.props.locale] ||
        'en-IN'
    );
};

const changeSort = (field) => {
    if (filters.sort === field) {
        filters.direction =
            filters.direction === 'asc'
                ? 'desc'
                : 'asc';
    } else {
        filters.sort = field;
        filters.direction = 'asc';
    }

    applyFilters();
};

const usageValue = (locale) => {
    return props.usage?.[locale] || {
        count: 0,
        percentage: 0,
    };
};
</script>

<template>

    <Head
        :title="
            lang.localization_analytics ||
            'Localization Analytics'
        "
    />

    <AuthenticatedLayout>

        <template #header>

            <div
                class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center"
            >

                <h2
                    class="text-2xl font-bold text-gray-900"
                >
                    {{
                        lang.localization_analytics ||
                        'Localization Analytics'
                    }}
                </h2>

                <button
                    @click="exportCsv"
                    class="rounded-lg bg-green-600 px-5 py-2.5 font-semibold text-white hover:bg-green-700"
                >
                    📥
                    {{
                        lang.export_csv ||
                        'Export CSV'
                    }}
                </button>

            </div>

        </template>

        <div
            class="min-h-screen bg-gray-50 py-10"
        >

            <div
                class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8"
            >

                <!-- Statistics -->

                <div
                    class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4"
                >

                    <div
                        class="rounded-2xl bg-white p-6 shadow-sm"
                    >

                        <p
                            class="text-sm font-semibold text-gray-500"
                        >
                            {{
                                lang.total_language_changes ||
                                'Total Language Changes'
                            }}
                        </p>

                        <p
                            class="mt-3 text-4xl font-black text-gray-900"
                        >
                            {{ statistics.total }}
                        </p>

                    </div>

                    <div
                        class="rounded-2xl bg-white p-6 shadow-sm"
                    >

                        <p
                            class="text-sm font-semibold text-gray-500"
                        >
                            {{
                                lang.today_selections ||
                                'Today'
                            }}
                        </p>

                        <p
                            class="mt-3 text-4xl font-black text-red-600"
                        >
                            {{ statistics.today }}
                        </p>

                    </div>

                    <div
                        class="rounded-2xl bg-white p-6 shadow-sm"
                    >

                        <p
                            class="text-sm font-semibold text-gray-500"
                        >
                            {{
                                lang.english_selections ||
                                'English'
                            }}
                        </p>

                        <p
                            class="mt-3 text-4xl font-black text-blue-600"
                        >
                            {{ statistics.english }}
                        </p>

                    </div>

                    <div
                        class="rounded-2xl bg-white p-6 shadow-sm"
                    >

                        <p
                            class="text-sm font-semibold text-gray-500"
                        >
                            {{
                                lang.gujarati_selections ||
                                'Gujarati'
                            }}
                        </p>

                        <p
                            class="mt-3 text-4xl font-black text-green-600"
                        >
                            {{ statistics.gujarati }}
                        </p>

                    </div>

                </div>

                <!-- Language Usage -->

                <div
                    class="rounded-2xl bg-white p-6 shadow-sm"
                >

                    <h3
                        class="mb-5 text-lg font-bold text-gray-900"
                    >
                        {{
                            lang.language_usage ||
                            'Language Usage'
                        }}
                    </h3>

                    <div
                        class="grid grid-cols-1 gap-4 md:grid-cols-5"
                    >

                        <div
                            v-for="language in availableLocales"
                            :key="language.code"
                            class="rounded-xl border border-gray-100 p-4"
                        >

                            <div
                                class="flex items-center justify-between"
                            >

                                <span
                                    class="font-semibold"
                                >
                                    {{ language.flag }}
                                    {{ language.native_name }}
                                </span>

                                <span
                                    class="text-sm font-bold text-red-600"
                                >
                                    {{
                                        usageValue(
                                            language.code
                                        ).percentage
                                    }}%
                                </span>

                            </div>

                            <div
                                class="mt-3 h-2 overflow-hidden rounded-full bg-gray-100"
                            >

                                <div
                                    class="h-full rounded-full bg-red-600"
                                    :style="{
                                        width:
                                            usageValue(
                                                language.code
                                            ).percentage + '%'
                                    }"
                                />

                            </div>

                            <p
                                class="mt-2 text-xs text-gray-500"
                            >
                                {{
                                    usageValue(
                                        language.code
                                    ).count
                                }}
                                {{
                                    lang.selections ||
                                    'selections'
                                }}
                            </p>

                        </div>

                    </div>

                </div>

                <!-- Filters -->

                <div
                    class="rounded-2xl bg-white p-6 shadow-sm"
                >

                    <div
                        class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-6"
                    >

                        <!-- Search -->

                        <div class="lg:col-span-2">

                            <label
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                {{
                                    lang.search ||
                                    'Search'
                                }}
                            </label>

                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="text"
                                :placeholder="
                                    lang.search_activity ||
                                    'Search user, email, IP or language...'
                                "
                                class="w-full rounded-lg border-gray-300"
                            />

                        </div>

                        <!-- Language -->

                        <div>

                            <label
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                {{
                                    lang.language ||
                                    'Language'
                                }}
                            </label>

                            <select
                                v-model="filters.locale"
                                class="w-full rounded-lg border-gray-300"
                            >

                                <option value="">
                                    {{
                                        lang.all_languages ||
                                        'All Languages'
                                    }}
                                </option>

                                <option
                                    v-for="language in availableLocales"
                                    :key="language.code"
                                    :value="language.code"
                                >
                                    {{ language.native_name }}
                                </option>

                            </select>

                        </div>

                        <!-- From -->

                        <div>

                            <label
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                {{
                                    lang.from_date ||
                                    'From Date'
                                }}
                            </label>

                            <input
                                v-model="filters.from_date"
                                type="date"
                                class="w-full rounded-lg border-gray-300"
                            />

                        </div>

                        <!-- To -->

                        <div>

                            <label
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                {{
                                    lang.to_date ||
                                    'To Date'
                                }}
                            </label>

                            <input
                                v-model="filters.to_date"
                                type="date"
                                class="w-full rounded-lg border-gray-300"
                            />

                        </div>

                        <!-- Actions -->

                        <div
                            class="flex items-end gap-2"
                        >

                            <button
                                @click="applyFilters"
                                class="rounded-lg bg-red-600 px-4 py-2.5 font-semibold text-white hover:bg-red-700"
                            >
                                {{
                                    lang.filter ||
                                    'Filter'
                                }}
                            </button>

                            <button
                                @click="resetFilters"
                                class="rounded-lg bg-gray-200 px-4 py-2.5 font-semibold text-gray-700 hover:bg-gray-300"
                            >
                                {{
                                    lang.reset ||
                                    'Reset'
                                }}
                            </button>

                        </div>

                    </div>

                </div>

                <!-- Activity Table -->

                <div
                    class="overflow-hidden rounded-2xl bg-white shadow-sm"
                >

                    <div
                        class="border-b border-gray-100 p-6"
                    >

                        <div
                            class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center"
                        >

                            <div>

                                <h3
                                    class="text-lg font-bold text-gray-900"
                                >
                                    {{
                                        lang.recent_language_activity ||
                                        'Recent Language Activity'
                                    }}
                                </h3>

                                <p
                                    class="mt-1 text-sm text-gray-500"
                                >
                                    {{
                                        activities.total
                                    }}
                                    {{
                                        lang.total_results ||
                                        'total results'
                                    }}
                                </p>

                            </div>

                        </div>

                    </div>

                    <div
                        class="overflow-x-auto"
                    >

                        <table
                            class="w-full text-left"
                        >

                            <thead
                                class="bg-gray-50"
                            >

                                <tr>

                                    <th
                                        class="px-6 py-4 text-xs font-bold uppercase text-gray-500"
                                    >
                                        {{ lang.user || 'User' }}
                                    </th>

                                    <th
                                        class="cursor-pointer px-6 py-4 text-xs font-bold uppercase text-gray-500"
                                        @click="
                                            changeSort(
                                                'from_locale'
                                            )
                                        "
                                    >
                                        {{ lang.from_language || 'From' }}
                                    </th>

                                    <th
                                        class="cursor-pointer px-6 py-4 text-xs font-bold uppercase text-gray-500"
                                        @click="
                                            changeSort(
                                                'to_locale'
                                            )
                                        "
                                    >
                                        {{ lang.to_language || 'To' }}
                                    </th>

                                    <th
                                        class="px-6 py-4 text-xs font-bold uppercase text-gray-500"
                                    >
                                        IP
                                    </th>

                                    <th
                                        class="cursor-pointer px-6 py-4 text-xs font-bold uppercase text-gray-500"
                                        @click="
                                            changeSort(
                                                'created_at'
                                            )
                                        "
                                    >
                                        {{ lang.date || 'Date' }}
                                    </th>

                                </tr>

                            </thead>

                            <tbody
                                class="divide-y divide-gray-100"
                            >

                                <tr
                                    v-for="activity in activities.data"
                                    :key="activity.id"
                                    class="hover:bg-gray-50"
                                >

                                    <td
                                        class="px-6 py-4"
                                    >

                                        <div
                                            class="font-semibold text-gray-900"
                                        >
                                            {{
                                                activity.user?.name ||
                                                'Guest'
                                            }}
                                        </div>

                                        <div
                                            class="text-xs text-gray-500"
                                        >
                                            {{
                                                activity.user?.email ||
                                                ''
                                            }}
                                        </div>

                                    </td>

                                    <td
                                        class="px-6 py-4 text-gray-600"
                                    >
                                        {{ languageFlag(activity.from_locale) }}
                                        {{
                                            languageName(
                                                activity.from_locale
                                            )
                                        }}
                                    </td>

                                    <td
                                        class="px-6 py-4"
                                    >

                                        <span
                                            class="rounded-full bg-red-50 px-3 py-1 text-sm font-semibold text-red-600"
                                        >
                                            {{ languageFlag(activity.to_locale) }}
                                            {{
                                                languageName(
                                                    activity.to_locale
                                                )
                                            }}
                                        </span>

                                    </td>

                                    <td
                                        class="px-6 py-4 text-sm text-gray-500"
                                    >
                                        {{
                                            activity.ip_address ||
                                            '-'
                                        }}
                                    </td>

                                    <td
                                        class="px-6 py-4 text-sm text-gray-500"
                                    >
                                        {{
                                            formatDate(
                                                activity.created_at
                                            )
                                        }}
                                    </td>

                                </tr>

                                <tr
                                    v-if="
                                        !activities.data?.length
                                    "
                                >

                                    <td
                                        colspan="5"
                                        class="px-6 py-12 text-center text-gray-500"
                                    >
                                        {{
                                            lang.no_activity ||
                                            'No language activity found.'
                                        }}
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <!-- Numeric Pagination -->

                    <div
                        v-if="
                            activities.last_page > 1
                        "
                        class="flex flex-wrap gap-2 border-t border-gray-100 p-6"
                    >

                        <template
                            v-for="pageNumber in activities.last_page"
                            :key="pageNumber"
                        >

                            <button
                                @click="
                                    router.get(
                                        activities.path +
                                        '?page=' +
                                        pageNumber +
                                        '&search=' +
                                        encodeURIComponent(filters.search) +
                                        '&locale=' +
                                        filters.locale +
                                        '&from_date=' +
                                        filters.from_date +
                                        '&to_date=' +
                                        filters.to_date +
                                        '&sort=' +
                                        filters.sort +
                                        '&direction=' +
                                        filters.direction
                                    )
                                "
                                class="rounded-lg px-3 py-2 text-sm font-semibold"
                                :class="
                                    pageNumber ===
                                    activities.current_page
                                        ? 'bg-red-600 text-white'
                                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                "
                            >
                                {{ pageNumber }}
                            </button>

                        </template>

                    </div>

                </div>

            </div>

        </div>

    </AuthenticatedLayout>

</template>