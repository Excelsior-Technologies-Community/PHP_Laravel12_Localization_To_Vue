<script setup>
import { computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const page = usePage();

const lang = computed(() => page.props.language || {});

const user = computed(() => page.props.auth?.user || {});

const form = useForm({
    locale: user.value.preferred_locale || page.props.locale || 'en',
});

const submit = () => {
    form.post(route('language.preference.update'));
};
</script>

<template>

    <Head :title="lang.language_settings || 'Language Settings'" />

    <AuthenticatedLayout>

        <template #header>

            <h2 class="text-2xl font-bold text-gray-900">
                {{ lang.language_settings || 'Language Settings' }}
            </h2>

        </template>

        <div class="py-12">

            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">

                <div class="overflow-hidden rounded-2xl bg-white p-8 shadow-sm">

                    <div class="mb-8">

                        <h3 class="text-xl font-bold text-gray-900">
                            {{ lang.language_preference || 'Language Preference' }}
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ lang.select_language || 'Select your preferred language' }}
                        </p>

                    </div>

                    <form @submit.prevent="submit">

                        <div class="grid gap-5 md:grid-cols-2">

                            <!-- English -->
                            <label
                                class="cursor-pointer rounded-xl border-2 p-6 transition"
                                :class="form.locale === 'en'
                                    ? 'border-red-500 bg-red-50'
                                    : 'border-gray-200 hover:border-gray-300'"
                            >

                                <input
                                    type="radio"
                                    value="en"
                                    v-model="form.locale"
                                    class="sr-only"
                                />

                                <div class="flex items-center justify-between">

                                    <div>

                                        <div class="text-lg font-bold">
                                            🇬🇧 English
                                        </div>

                                        <div class="mt-1 text-sm text-gray-500">
                                            English
                                        </div>

                                    </div>

                                    <div
                                        v-if="form.locale === 'en'"
                                        class="text-red-600 font-bold"
                                    >
                                        ✓
                                    </div>

                                </div>

                            </label>

                            <!-- Gujarati -->
                            <label
                                class="cursor-pointer rounded-xl border-2 p-6 transition"
                                :class="form.locale === 'gu'
                                    ? 'border-red-500 bg-red-50'
                                    : 'border-gray-200 hover:border-gray-300'"
                            >

                                <input
                                    type="radio"
                                    value="gu"
                                    v-model="form.locale"
                                    class="sr-only"
                                />

                                <div class="flex items-center justify-between">

                                    <div>

                                        <div class="text-lg font-bold">
                                            🇮🇳 ગુજરાતી
                                        </div>

                                        <div class="mt-1 text-sm text-gray-500">
                                            Gujarati
                                        </div>

                                    </div>

                                    <div
                                        v-if="form.locale === 'gu'"
                                        class="text-red-600 font-bold"
                                    >
                                        ✓
                                    </div>

                                </div>

                            </label>

                        </div>

                        <InputError
                            class="mt-3"
                            :message="form.errors.locale"
                        />

                        <div class="mt-8 flex justify-end">

                            <PrimaryButton
                                :disabled="form.processing"
                                :class="{ 'opacity-25': form.processing }"
                            >
                                {{ lang.save_language || 'Save Language' }}
                            </PrimaryButton>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </AuthenticatedLayout>

</template>