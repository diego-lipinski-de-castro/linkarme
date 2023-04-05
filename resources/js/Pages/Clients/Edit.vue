<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, ref, watch } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import {
    EyeIcon,
    EyeSlashIcon,
} from '@heroicons/vue/20/solid'

const { client } = defineProps({
    client: Object,
})

const showPassword = ref(false)

const form = useForm({
    name: client.name,
    email: client.email,
    password: client.password,
    locale: client.locale ?? 'en',
    coin: client.coin ?? 'USD',
    full: client.full,
    notify_updates_via_email: client.notify_updates_via_email,
})

const submit = () => {
    form.put(route('clients.update', client.id));
}

const generatePassword = () => {
    form.password = Math.random().toString(36).slice(-8);
}

</script>
        
<template>
    <AppSuspense><AppLayout :title="$t('Edit client')">
        <template #uppermenu>
            
        </template>

        <template #header>
            <div
                class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t('Edit client') }}
                </h2>
            </div>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form @submit.prevent="submit" class="flex flex-col space-y-6 p-6 bg-white border-b border-gray-200">
                
                <div>
                    <InputLabel for="name" :value="$t('Name')" />
                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus/>
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" :value="$t('Email')" />
                    <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required/>
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" :value="$t('Password')" />
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <div class="w-full relative flex items-stretch flex-grow focus-within:z-10">
                            <TextInput id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'" class="block w-full rounded-none rounded-l-md"/>
                        </div>

                        <button @click="showPassword = !showPassword" type="button" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                            <EyeIcon v-if="!showPassword" class="h-6 w-6"/>
                            <EyeSlashIcon v-else class="h-6 w-6"/>
                        </button>

                        <button @click="generatePassword" type="button" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">{{ $t('Generate') }}</button>
                    </div>
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel for="locale" :value="$t('Locale')"/>
                    <div class="mt-1">

                        <select v-model="form.locale" id="locale" name="locale" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="en">Inglês</option>
                            <option value="es">Espanhol</option>
                            <option value="pt">Português</option>
                        </select>

                    </div>

                    <InputError class="mt-2" :message="form.errors.locale"/>
                </div>

                <div>
                    <InputLabel for="coin" :value="$t('Coin')"/>
                    <div class="mt-1">

                        <select v-model="form.coin" id="coin" name="coin" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="BRL">BRL</option>
                            <option value="EUR">EUR</option>
                            <option value="USD">USD</option>
                        </select>

                    </div>

                    <InputError class="mt-2" :message="form.errors.locale"/>
                </div>

                <div>
                    <InputLabel for="full" :value="$t('Full')"/>
                    <Checkbox id="full" v-model:checked="form.full" name="full" />
                    <InputError class="mt-2" :message="form.errors.full" />
                </div>

                <div>
                    <InputLabel for="notify_updates_via_email" :value="$t('E-mail notifications')"/>
                    <div class="mt-1">

                        <select v-model="form.notify_updates_via_email" id="notify_updates_via_email" name="notify_updates_via_email" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option :value="null">Disabled</option>
                            <option value="DAILY">Daily</option>
                            <option value="WEEKLY">Weekly</option>
                        </select>

                    </div>

                    <InputError class="mt-2" :message="form.errors.locale"/>
                </div>

                <div class="flex justify-end">
                    <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                        class="flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">{{ $t('Save') }}</button>
                </div>
            </form>
        </div>
    </AppLayout></AppSuspense>
</template>
        