<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);

const page = usePage();

const locale = computed(() => page.props.locale || 'en');

const lang = computed(() => page.props.language || {});

const user = computed(() => page.props.auth?.user || {});

const availableLocales = computed(
    () => page.props.availableLocales || []
);

const switchLanguage = (language) => {
    window.location.href = route(
        'language.switch',
        language
    );
};
</script>

<template>

    <div>

        <div class="min-h-screen bg-gray-100">

            <nav class="border-b border-gray-100 bg-white">

                <div
                    class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"
                >

                    <div class="flex h-16 justify-between">

                        <div class="flex">

                            <!-- Logo -->

                            <div
                                class="flex shrink-0 items-center"
                            >

                                <Link
                                    :href="route('dashboard')"
                                >

                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />

                                </Link>

                            </div>

                            <!-- Navigation -->

                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >

                                <NavLink
                                    :href="route('dashboard')"
                                    :active="
                                        route().current('dashboard')
                                    "
                                >
                                    {{
                                        lang.dashboard_btn ||
                                        'Dashboard'
                                    }}
                                </NavLink>

                                <NavLink
                                    :href="
                                        route(
                                            'localization.analytics'
                                        )
                                    "
                                    :active="
                                        route().current(
                                            'localization.analytics'
                                        )
                                    "
                                >
                                    {{
                                        lang.localization_analytics ||
                                        'Localization Analytics'
                                    }}
                                </NavLink>

                            </div>

                        </div>

                        <!-- Right Side -->

                        <div
                            class="hidden sm:ms-6 sm:flex sm:items-center gap-4"
                        >

                            <!-- Dynamic Language Switcher -->

                            <div
                                class="flex overflow-hidden rounded-lg border border-gray-200"
                            >

                                <button
                                    v-for="language in availableLocales"
                                    :key="language.code"
                                    type="button"
                                    @click="
                                        switchLanguage(
                                            language.code
                                        )
                                    "
                                    :title="
                                        language.name
                                    "
                                    :class="[
                                        'px-3 py-2 text-sm font-semibold transition',
                                        locale === language.code
                                            ? 'bg-red-600 text-white'
                                            : 'bg-white text-gray-600 hover:bg-gray-100'
                                    ]"
                                >

                                    {{ language.flag }}
                                    {{ language.code.toUpperCase() }}

                                </button>

                            </div>

                            <!-- User Dropdown -->

                            <div class="relative ms-3">

                                <Dropdown
                                    align="right"
                                    width="48"
                                >

                                    <template #trigger>

                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition hover:text-gray-700 focus:outline-none"
                                        >

                                            {{
                                                user.name ||
                                                'User'
                                            }}

                                            <svg
                                                class="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >

                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />

                                            </svg>

                                        </button>

                                    </template>

                                    <template #content>

                                        <DropdownLink
                                            :href="
                                                route(
                                                    'profile.edit'
                                                )
                                            "
                                        >
                                            {{
                                                lang.profile ||
                                                'Profile'
                                            }}
                                        </DropdownLink>

                                        <DropdownLink
                                            :href="
                                                route(
                                                    'language.settings'
                                                )
                                            "
                                        >
                                            {{
                                                lang.language_settings ||
                                                'Language Settings'
                                            }}
                                        </DropdownLink>

                                        <DropdownLink
                                            :href="
                                                route(
                                                    'localization.analytics'
                                                )
                                            "
                                        >
                                            {{
                                                lang.localization_analytics ||
                                                'Localization Analytics'
                                            }}
                                        </DropdownLink>

                                        <DropdownLink
                                            :href="
                                                route('logout')
                                            "
                                            method="post"
                                            as="button"
                                        >
                                            {{
                                                lang.logout ||
                                                'Log Out'
                                            }}
                                        </DropdownLink>

                                    </template>

                                </Dropdown>

                            </div>

                        </div>

                        <!-- Hamburger -->

                        <div
                            class="-me-2 flex items-center sm:hidden"
                        >

                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500 focus:outline-none"
                            >

                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >

                                    <path
                                        :class="{
                                            hidden:
                                                showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />

                                    <path
                                        :class="{
                                            hidden:
                                                !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />

                                </svg>

                            </button>

                        </div>

                    </div>

                </div>

                <!-- Mobile Navigation -->

                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >

                    <div class="space-y-1 pb-3 pt-2">

                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="
                                route().current('dashboard')
                            "
                        >
                            {{
                                lang.dashboard_btn ||
                                'Dashboard'
                            }}
                        </ResponsiveNavLink>

                        <ResponsiveNavLink
                            :href="
                                route(
                                    'localization.analytics'
                                )
                            "
                            :active="
                                route().current(
                                    'localization.analytics'
                                )
                            "
                        >
                            {{
                                lang.localization_analytics ||
                                'Localization Analytics'
                            }}
                        </ResponsiveNavLink>

                    </div>

                    <!-- Mobile Languages -->

                    <div
                        class="border-t border-gray-200 px-4 py-4"
                    >

                        <p
                            class="mb-3 text-xs font-bold uppercase text-gray-500"
                        >
                            {{ lang.language || 'Language' }}
                        </p>

                        <div
                            class="grid grid-cols-2 gap-2"
                        >

                            <button
                                v-for="language in availableLocales"
                                :key="language.code"
                                @click="
                                    switchLanguage(
                                        language.code
                                    )
                                "
                                :class="[
                                    'rounded-lg border px-3 py-2 text-sm font-semibold',
                                    locale === language.code
                                        ? 'border-red-500 bg-red-50 text-red-600'
                                        : 'border-gray-200 bg-white text-gray-600'
                                ]"
                            >

                                {{ language.flag }}
                                {{ language.native_name }}

                            </button>

                        </div>

                    </div>

                    <!-- Mobile User -->

                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >

                        <div class="px-4">

                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ user.name || 'User' }}
                            </div>

                            <div
                                class="text-sm font-medium text-gray-500"
                            >
                                {{ user.email || '' }}
                            </div>

                        </div>

                        <div
                            class="mt-3 space-y-1"
                        >

                            <ResponsiveNavLink
                                :href="
                                    route(
                                        'language.settings'
                                    )
                                "
                            >
                                {{
                                    lang.language_settings ||
                                    'Language Settings'
                                }}
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="
                                    route('profile.edit')
                                "
                            >
                                {{
                                    lang.profile ||
                                    'Profile'
                                }}
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="
                                    route('logout')
                                "
                                method="post"
                                as="button"
                            >
                                {{
                                    lang.logout ||
                                    'Log Out'
                                }}
                            </ResponsiveNavLink>

                        </div>

                    </div>

                </div>

            </nav>

            <!-- Header -->

            <header
                v-if="$slots.header"
                class="bg-white shadow"
            >

                <div
                    class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8"
                >

                    <slot name="header" />

                </div>

            </header>

            <!-- Content -->

            <main>

                <slot />

            </main>

        </div>

    </div>

</template>