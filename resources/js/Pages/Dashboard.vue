<script setup>
import { computed, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();

const lang = computed(() => page.props.language || {});

const user = computed(() => page.props.auth?.user || {});

const search = ref('');

const selectedCategory = ref('all');

const stats = [
    {
        label: 'gold_stock',
        value: 24,
        icon: '👑',
        textColor: 'text-yellow-700',
        bgColor: 'bg-yellow-50',
    },
    {
        label: 'silver_stock',
        value: 12,
        icon: '💎',
        textColor: 'text-gray-700',
        bgColor: 'bg-gray-50',
    },
    {
        label: 'diamond_stock',
        value: 8,
        icon: '✨',
        textColor: 'text-blue-700',
        bgColor: 'bg-blue-50',
    },
];

const recentItems = [
    {
        id: 1,
        name: 'Gold Ring',
        category: 'ring',
        weight: '5g',
        price: '₹25,000',
    },
    {
        id: 2,
        name: 'Diamond Necklace',
        category: 'necklace',
        weight: '12g',
        price: '₹85,000',
    },
    {
        id: 3,
        name: 'Silver Bracelet',
        category: 'bracelet',
        weight: '10g',
        price: '₹12,000',
    },
    {
        id: 4,
        name: 'Diamond Earring',
        category: 'earring',
        weight: '3g',
        price: '₹35,000',
    },
];

const filteredItems = computed(() => {
    const term = search.value.toLowerCase().trim();

    return recentItems.filter((item) => {

        const matchesSearch =
            !term ||
            item.name.toLowerCase().includes(term) ||
            item.category.toLowerCase().includes(term);

        const matchesCategory =
            selectedCategory.value === 'all' ||
            item.category === selectedCategory.value;

        return matchesSearch && matchesCategory;
    });
});

const categoryName = (category) => {
    const names = {
        ring: lang.value.ring || 'Ring',
        necklace: lang.value.necklace || 'Necklace',
        bracelet: lang.value.bracelet || 'Bracelet',
        earring: lang.value.earring || 'Earring',
    };

    return names[category] || category;
};
</script>

<template>

    <Head :title="lang.dashboard_title || 'Dashboard'" />

    <AuthenticatedLayout>

        <template #header>

            <h2 class="text-3xl font-black tracking-tight text-gray-900">
                {{ lang.dashboard_title }} - SANCHELA JEWELS
            </h2>

        </template>

        <div class="min-h-screen bg-gray-50 py-12">

            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

                <!-- Welcome -->
                <div class="rounded-2xl bg-red-600 p-8 text-white shadow-xl">

                    <h3 class="text-2xl font-bold">

                        {{ lang.welcome_user }}

                        <span class="text-yellow-300">
                            {{ user.name }}
                        </span>

                        !

                    </h3>

                    <p class="mt-1 opacity-80">
                        {{ lang.description }}
                    </p>

                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        :class="`${stat.bgColor} rounded-2xl border-2 border-gray-100 p-8 shadow-sm`"
                    >

                        <div class="flex items-center justify-between">

                            <div>

                                <p
                                    :class="`${stat.textColor} text-xs font-bold uppercase tracking-widest`"
                                >
                                    {{ lang[stat.label] }}
                                </p>

                                <p class="mt-2 text-4xl font-black text-gray-900">
                                    {{ stat.value }}
                                </p>

                            </div>

                            <span class="text-4xl">
                                {{ stat.icon }}
                            </span>

                        </div>

                    </div>

                </div>

                <!-- Search & Filtering -->
                <div class="rounded-2xl bg-white p-6 shadow-sm">

                    <div class="mb-5">

                        <h3 class="text-lg font-bold text-gray-900">
                            {{ lang.search_results }}
                        </h3>

                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                        <!-- Search -->
                        <div class="md:col-span-2">

                            <input
                                v-model="search"
                                type="text"
                                :placeholder="lang.search_jewelry"
                                class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-red-500 focus:ring-red-500"
                            />

                        </div>

                        <!-- Category -->
                        <div>

                            <select
                                v-model="selectedCategory"
                                class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-red-500 focus:ring-red-500"
                            >

                                <option value="all">
                                    {{ lang.all_categories }}
                                </option>

                                <option value="ring">
                                    {{ lang.ring }}
                                </option>

                                <option value="necklace">
                                    {{ lang.necklace }}
                                </option>

                                <option value="bracelet">
                                    {{ lang.bracelet }}
                                </option>

                                <option value="earring">
                                    {{ lang.earring }}
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <!-- Jewelry -->
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                    <div class="border-b border-gray-100 p-6 font-bold text-lg text-gray-800">

                        {{ lang.recent_items }}

                        <span class="ml-2 text-sm font-normal text-gray-500">
                            ({{ filteredItems.length }})
                        </span>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full text-left">

                            <thead class="bg-gray-50 text-xs font-bold uppercase text-gray-500">

                                <tr>

                                    <th class="p-6">
                                        {{ lang.item_name }}
                                    </th>

                                    <th class="p-6">
                                        {{ lang.category }}
                                    </th>

                                    <th class="p-6">
                                        {{ lang.weight }}
                                    </th>

                                    <th class="p-6">
                                        {{ lang.price }}
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                <tr
                                    v-for="item in filteredItems"
                                    :key="item.id"
                                    class="transition hover:bg-gray-50"
                                >

                                    <td class="p-6 font-bold text-gray-900">
                                        {{ item.name }}
                                    </td>

                                    <td class="p-6 text-gray-600">
                                        {{ categoryName(item.category) }}
                                    </td>

                                    <td class="p-6 text-gray-600">
                                        {{ item.weight }}
                                    </td>

                                    <td class="p-6 font-black text-red-600">
                                        {{ item.price }}
                                    </td>

                                </tr>

                                <tr v-if="filteredItems.length === 0">

                                    <td
                                        colspan="4"
                                        class="p-12 text-center text-gray-500"
                                    >
                                        {{ lang.no_jewelry_found }}
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </AuthenticatedLayout>

</template>