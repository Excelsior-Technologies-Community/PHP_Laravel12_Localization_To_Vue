<script setup>
import { computed, reactive } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();

const lang = computed(() => page.props.language || {});

const props = defineProps({
    activities: Object,

    statistics: Object,

    filters: Object,
});

const filters = reactive({
    locale: props.filters?.locale || '',
    from_date: props.filters?.from_date || '',
    to_date: props.filters?.to_date || '',
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
    filters.locale = '';
    filters.from_date = '';
    filters.to_date = '';

    router.get(route('localization.analytics'));
};

const languageName = (locale) => {
    if (locale === 'gu') {
        return lang.value.gujarati || 'Gujarati';
    }

    if (locale === 'en') {
        return lang.value.english || 'English';
    }

    return locale || '-';
};

const formatDate = (date) => {
    if (!date) {
        return '-';
    }

    return new Date(date).toLocaleString(
        page.props.locale === 'gu'
            ? 'gu-IN'
            : 'en-IN'
    );
};
</script>

<template>

    <Head :title="lang.localization_analytics || 'Localization Analytics'" />

    <AuthenticatedLayout>

        <template #header>

            <h2 class="text-2xl font-bold text-gray-900">
                {{ lang.localization_analytics || 'Localization Analytics' }}
            </h2>

        </template>

        <div class="min-h-screen bg-gray-50 py-10">

            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

                <!-- Statistics -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-4">

                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <p class="text-sm font-semibold text-gray-500">
                            {{ lang.total_language_changes }}
                        </p>

                        <p class="mt-3 text-4xl font-black text-gray-900">
                            {{ statistics.total }}
                        </p>

                    </div>

                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <p class="text-sm font-semibold text-gray-500">
                            {{ lang.english_selections }}
                        </p>

                        <p class="mt-3 text-4xl font-black text-blue-600">
                            {{ statistics.english }}
                        </p>

                    </div>

                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <p class="text-sm font-semibold text-gray-500">
                            {{ lang.gujarati_selections }}
                        </p>

                        <p class="mt-3 text-4xl font-black text-green-600">
                            {{ statistics.gujarati }}
                        </p>

                    </div>

                    <div class="rounded-2xl bg-white p-6 shadow-sm">

                        <p class="text-sm font-semibold text-gray-500">
                            {{ lang.today_selections }}
                        </p>

                        <p class="mt-3 text-4xl font-black text-red-600">
                            {{ statistics.today }}
                        </p>

                    </div>

                </div>

                <!-- Filters -->
                <div class="rounded-2xl bg-white p-6 shadow-sm">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                {{ lang.all_languages }}
                            </label>

                            <select
                                v-model="filters.locale"
                                class="w-full rounded-lg border-gray-300"
                            >

                                <option value="">
                                    {{ lang.all_languages }}
                                </option>

                                <option value="en">
                                    {{ lang.english }}
                                </option>

                                <option value="gu">
                                    {{ lang.gujarati }}
                                </option>

                            </select>

                        </div>

                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                {{ lang.from_date }}
                            </label>

                            <input
                                type="date"
                                v-model="filters.from_date"
                                class="w-full rounded-lg border-gray-300"
                            />

                        </div>

                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                {{ lang.to_date }}
                            </label>

                            <input
                                type="date"
                                v-model="filters.to_date"
                                class="w-full rounded-lg border-gray-300"
                            />

                        </div>

                        <div class="flex items-end gap-2">

                            <button
                                @click="applyFilters"
                                class="rounded-lg bg-red-600 px-5 py-2.5 font-semibold text-white hover:bg-red-700"
                            >
                                {{ lang.filter }}
                            </button>

                            <button
                                @click="resetFilters"
                                class="rounded-lg bg-gray-200 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-300"
                            >
                                {{ lang.reset }}
                            </button>

                        </div>

                    </div>

                </div>

                <!-- Activity -->
                <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

                    <div class="border-b border-gray-100 p-6">

                        <h3 class="text-lg font-bold text-gray-900">
                            {{ lang.recent_language_activity }}
                        </h3>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full text-left">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">
                                        {{ lang.user }}
                                    </th>

                                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">
                                        {{ lang.from_language }}
                                    </th>

                                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">
                                        {{ lang.to_language }}
                                    </th>

                                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">
                                        {{ lang.date }}
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                <tr
                                    v-for="activity in activities.data"
                                    :key="activity.id"
                                    class="hover:bg-gray-50"
                                >

                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ activity.user?.name || 'Guest' }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ languageName(activity.from_locale) }}
                                    </td>

                                    <td class="px-6 py-4">

                                        <span class="rounded-full bg-red-50 px-3 py-1 text-sm font-semibold text-red-600">
                                            {{ languageName(activity.to_locale) }}
                                        </span>

                                    </td>

                                    <td class="px-6 py-4 text-gray-500">
                                        {{ formatDate(activity.created_at) }}
                                    </td>

                                </tr>

                                <tr v-if="!activities.data?.length">

                                    <td
                                        colspan="4"
                                        class="px-6 py-12 text-center text-gray-500"
                                    >
                                        {{ lang.no_activity }}
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="activities.links?.length > 3"
                        class="flex flex-wrap gap-2 border-t border-gray-100 p-6"
                    >

                        <template
                            v-for="link in activities.links"
                            :key="link.label"
                        >

                            <button
                                v-if="link.url"
                                @click="router.get(link.url)"
                                class="rounded-lg px-3 py-2 text-sm"
                                :class="link.active
                                    ? 'bg-red-600 text-white'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                v-html="link.label"
                            />

                        </template>

                    </div>

                </div>

            </div>

        </div>

    </AuthenticatedLayout>

</template>