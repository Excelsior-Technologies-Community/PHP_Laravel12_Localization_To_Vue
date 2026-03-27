<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})

const page = usePage()

const lang = computed(() => {
    return page.props.language || {
        welcome_message: "Welcome to Sanchela Jewels",
        description: "Manage your jewelry collection easily.",
        login: "Login",
        register: "Register",
        start_now: "Start Now"
    }
})

const locale = computed(() => page.props.locale || 'en')
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gradient-to-br from-gray-950 via-gray-900 to-gray-800 flex flex-col text-white overflow-hidden">
        
        <div class="absolute top-0 left-0 w-96 h-96 bg-red-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
        <div class="absolute top-40 right-0 w-96 h-96 bg-yellow-400 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 2s"></div>

        <header class="relative z-20 bg-gray-900/50 backdrop-blur-md border-b border-gray-800">
            <div class="max-w-7xl mx-auto flex justify-between items-center p-6">

                <div class="flex flex-col">
                    <div class="text-3xl font-black tracking-wider bg-gradient-to-r from-red-500 via-yellow-400 to-red-600 bg-clip-text text-transparent">
                        SANCHELA
                    </div>
                    <div class="text-xs font-light text-gray-400 tracking-widest">JEWELS</div>
                </div>

                <div class="flex items-center gap-8">
                    <div class="flex border border-gray-700 rounded-lg overflow-hidden bg-gray-800/50">
                        <a href="/language/en"
                            class="px-4 py-2 text-sm font-semibold transition-all duration-300"
                            :class="locale==='en' ? 'bg-red-600 text-white shadow-lg' : 'text-gray-400 hover:text-white'">
                            EN
                        </a>
                        <a href="/language/gu"
                            class="px-4 py-2 text-sm font-semibold transition-all duration-300"
                            :class="locale==='gu' ? 'bg-red-600 text-white shadow-lg' : 'text-gray-400 hover:text-white'">
                            GU
                        </a>
                    </div>

                    <div v-if="canLogin" class="flex items-center gap-6">
                        <Link v-if="page.props.auth?.user" :href="route('dashboard')" class="font-semibold text-red-400 hover:text-red-300 transition">
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link :href="route('login')" class="font-semibold text-gray-300 hover:text-white transition">
                                {{ lang.login }}
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" 
                                class="px-6 py-2 rounded-lg font-bold bg-red-600 hover:bg-red-700 transition transform hover:scale-105 shadow-lg shadow-red-900/20">
                                {{ lang.register }}
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <main class="relative z-10 flex-1 flex items-center justify-center px-6 py-20">
            <div class="max-w-4xl text-center">
                <h1 class="text-6xl md:text-7xl font-black mb-8 leading-tight tracking-tighter">
                    <span class="block mb-3">{{ lang.welcome_message }}</span>
                    <span class="block bg-gradient-to-r from-red-500 via-yellow-500 to-red-600 bg-clip-text text-transparent">
                        Premium Jewelry Management
                    </span>
                </h1>

                <p class="text-xl md:text-2xl text-gray-400 mb-12 max-w-2xl mx-auto leading-relaxed">
                    {{ lang.description }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link :href="route('login')" class="px-10 py-4 rounded-xl font-black text-lg bg-red-600 hover:bg-red-700 transition shadow-2xl shadow-red-600/30 transform hover:-translate-y-1">
                        {{ lang.start_now }}
                    </Link>
                </div>
            </div>
        </main>

        <footer class="py-6 border-t border-gray-900 text-center text-gray-600 text-xs">
            Laravel v{{ laravelVersion }} | PHP v{{ phpVersion }} | © 2026 Sanchela Jewels
        </footer>
    </div>
</template>