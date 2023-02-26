<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import ApplicationLogoNew from '@/Components/ApplicationLogoNew.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head :title="$t('Log in')" />

    <div class="min-h-full relative">
        <div class="z-0 absolute inset-0">
            <img class="h-full w-full object-cover" src="@/assets/images/login-bg.jpg" alt="Background" />
        </div>

        <div class="z-10 absolute inset-0 flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="mx-5 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                    
                    <div class="sm:mx-auto sm:w-full sm:max-w-md">
                        <ApplicationLogoNew class="mx-auto h-12 w-auto" />
                    </div>

                    <form @submit.prevent="submit" class="mt-8 space-y-6">
                        
                        <div v-if="status" class="font-medium text-sm text-green-600">
                            {{ status }}
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">{{ $t('Email') }}</label>
                            <div class="mt-1">
                                <input v-model="form.email" id="email" name="email" type="email" autocomplete="email" required
                                    class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">{{ $t('Password') }}</label>
                            <div class="mt-1">
                                <input v-model="form.password" id="password" name="password" type="password" autocomplete="current-password"
                                    required
                                    class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-sm">
                                <Link v-if="canResetPassword" :href="route('client.password.request')" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">{{ $t('Forgot your password?') }}</Link>
                            </div>
                        </div>

                        <div>
                            <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                                class="flex w-full justify-center rounded-md border border-transparent bg-zinc-900 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition-colors">{{ $t('Login') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>