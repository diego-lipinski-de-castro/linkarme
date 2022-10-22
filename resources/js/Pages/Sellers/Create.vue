<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, ref, watch } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {
    EyeIcon,
    EyeSlashIcon,
} from '@heroicons/vue/20/solid'


const showPassword = ref(false)

const form = useForm({
    name: '',
    email: '',
    password: '',
    phone: '',
});

const submit = () => {
    form.post(route('sellers.store'));
}

const generatePassword = () => {
    form.password = Math.random().toString(36).slice(-8);
}

</script>
        
<template>
    <AppSuspense>
        <AppLayout title="Adicionar vendedor">
            <template #uppermenu>

            </template>

            <template #header>
                <div
                    class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Adicionar vendedor
                    </h2>
                </div>
            </template>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form @submit.prevent="submit" class="flex flex-col space-y-6 p-6 bg-white border-b border-gray-200">

                    <div>
                        <InputLabel for="name" :value="$t('Name')" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                            autofocus autocomplete="name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" :value="$t('Email')" />
                        <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required
                            autofocus autocomplete="email" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" :value="$t('Password')" />
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <div class="w-full relative flex items-stretch flex-grow focus-within:z-10">
                                <TextInput id="password" v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="block w-full rounded-none rounded-l-md" required
                                    autocomplete="current-password" />
                            </div>

                            <button @click="showPassword = !showPassword" type="button"
                                class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                <EyeIcon v-if="!showPassword" class="h-6 w-6" />
                                <EyeSlashIcon v-else class="h-6 w-6" />
                            </button>

                            <button @click="generatePassword" type="button"
                                class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">{{
                                $t('Generate') }}</button>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <InputLabel for="phone" :value="$t('Phone')" />
                        <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" required
                            autofocus autocomplete="phone" />
                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>

                    <div class="flex justify-end">
                        <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                            class="flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">{{
                            $t('Save') }}</button>
                    </div>
                </form>
            </div>
        </AppLayout>
    </AppSuspense>
</template>
        