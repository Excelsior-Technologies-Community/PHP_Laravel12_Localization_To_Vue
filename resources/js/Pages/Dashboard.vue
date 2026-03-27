<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const lang = computed(() => page.props.language || {})
const user = computed(() => page.props.auth?.user || {})

const stats = [
    { label: 'gold_stock', value: 24, icon: '👑', color: 'bg-yellow-500', textColor: 'text-yellow-700', bgColor: 'bg-yellow-50' },
    { label: 'silver_stock', value: 12, icon: '💎', color: 'bg-gray-400', textColor: 'text-gray-700', bgColor: 'bg-gray-50' },
    { label: 'diamond_stock', value: 8, icon: '✨', color: 'bg-blue-400', textColor: 'text-blue-700', bgColor: 'bg-blue-50' },
]

const recentItems = [
    { id: 1, name: 'Gold Ring', category: 'Ring', weight: '5g', price: '₹25,000' },
    { id: 2, name: 'Diamond Necklace', category: 'Necklace', weight: '12g', price: '₹85,000' },
]
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">
                {{ lang.dashboard_title }} - SANCHELA JEWELS
            </h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="bg-red-600 rounded-2xl p-8 text-white shadow-xl">
                    <h3 class="text-2xl font-bold">
                        {{ lang.welcome_user }} <span class="text-yellow-300">{{ user.name }}</span>!
                    </h3>
                    <p class="opacity-80 mt-1">Manage your premium jewelry inventory today.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="stat in stats" :class="`${stat.bgColor} p-8 rounded-2xl border-2 border-gray-100 shadow-sm`">
                        <div class="flex justify-between items-center">
                            <div>
                                <p :class="`${stat.textColor} text-xs font-bold uppercase tracking-widest`">{{ lang[stat.label] }}</p>
                                <p class="text-4xl font-black mt-2 text-gray-900">{{ stat.value }}</p>
                            </div>
                            <span class="text-4xl">{{ stat.icon }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 font-bold text-lg text-gray-800">
                        {{ lang.recent_items }}
                    </div>
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                            <tr>
                                <th class="p-6">Item Name</th>
                                <th class="p-6">Category</th>
                                <th class="p-6">Weight</th>
                                <th class="p-4">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="item in recentItems" class="hover:bg-gray-50 transition">
                                <td class="p-6 font-bold text-gray-900">{{ item.name }}</td>
                                <td class="p-6 text-gray-600">{{ item.category }}</td>
                                <td class="p-6 text-gray-600">{{ item.weight }}</td>
                                <td class="p-6 font-black text-red-600">{{ item.price }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>