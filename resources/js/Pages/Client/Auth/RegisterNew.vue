<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import ApplicationLogoNew from '@/Components/ApplicationLogoNew.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/24/outline';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    first_name: '',
    last_name: '',
    name: '',
    birthday: '',
    phone: '',
    email: '',
    password: '',
    password_confirmation: '',
    locale: 'en',
    coin: 'USD',
    notify_updates_via_email: null,
});

const submit = () => {
    form.transform(data => ({
        ...data,
    })).post(route('client.register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head :title="$t('Register')" />

    <div class="min-h-full relative flex justify-center">
        <div class="z-0 fixed inset-0">
            <img class="h-full w-full object-cover" src="@/assets/images/login-bg.jpg" alt="Background" />
        </div>

        <div class="z-10 absolute flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="mx-5 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                    
                    <div class="sm:mx-auto sm:w-full sm:max-w-md">
                        <ApplicationLogoNew class="mx-auto h-12 w-auto" />
                    </div>

                    <form @submit.prevent="submit" class="mt-8 space-y-6">
                        
                        <div v-if="status" class="font-medium text-sm text-green-600">
                            {{ status }}
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">{{ $t('First name') }}</label>
                                <div class="mt-1">
                                    <input v-model="form.first_name" id="first_name" name="first_name" type="text" autocomplete="first-name" required
                                        class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                                </div>
                                <InputError class="mt-2" :message="form.errors.first_name" />
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">{{ $t('Last name') }}</label>
                                <div class="mt-1">
                                    <input v-model="form.last_name" id="last_name" name="last_name" type="text" autocomplete="last-name" required
                                        class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                                </div>
                                <InputError class="mt-2" :message="form.errors.last_name" />
                            </div>

                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">{{ $t('Username') }}</label>
                                <div class="mt-1">
                                    <input v-model="form.name" id="name" name="name" type="text" autocomplete="name" required
                                        class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                                </div>
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="col-span-2">
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
                                    <input v-model="form.password" id="password" name="password" type="password" autocomplete="new-password"
                                        required
                                        class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                                </div>
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ $t('Confirm password') }}</label>
                                <div class="mt-1">
                                    <input v-model="form.password_confirmation" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                        required
                                        class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm" />
                                </div>
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="locale" :value="$t('Locale')" />
                                <div class="mt-1">

                                    <select v-model="form.locale" id="locale" name="locale"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="en">{{ $t('English') }}</option>
                                        <option value="es">{{ $t('Espanhol') }}</option>
                                        <option value="pt">{{ $t('Português') }}</option>
                                    </select>

                                </div>

                                <InputError class="mt-2" :message="form.errors.locale" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="coin" :value="$t('Coin')" />
                                <div class="mt-1">

                                    <select v-model="form.coin" id="coin" name="coin"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="BRL">BRL</option>
                                        <option value="GBP">GBP</option>
                                    </select>

                                </div>

                                <InputError class="mt-2" :message="form.errors.locale" />
                            </div>

                            <div class="col-span-2">
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

                            <div class="flex items-center justify-between">
                                <div class="text-sm">
                                    <Link :href="route('client.login')" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">{{ $t('Already registered?') }}</Link>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                                class="flex w-full justify-center rounded-md border border-transparent bg-zinc-900 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition-colors">{{ $t('Register') }}</button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300" />
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="bg-white px-2 text-gray-500">{{ $t('Need some help?') }}</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <Menu as="div" class="relative flex-shrink-0">
                                <MenuButton class="inline-flex w-full justify-center items-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-500 shadow-sm hover:bg-gray-50">
                                    <span>{{ $t('Our team is here') }}</span>
                                    <ChevronDownIcon class="ml-3 h-4 w-4"/>
                                </MenuButton>

                                <transition enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md overflow-hidden bg-white border border-gray-300 shadow-sm focus:outline-none">

                                        <MenuItem v-slot="{ active }">
                                            <a target="_blank" href="https://wa.me/5547989104747?text=Ol%C3%A1%21+Preciso+de+ajuda+para+logar+no+sistema" :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm font-bold text-gray-700']">
                                                <span>{{ $t('Whatsapp') }}</span>
                                            </a>
                                        </MenuItem>

                                        <MenuItem v-slot="{ active }">
                                            <a target="_blank" href="https://t.me/maxpiress" :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm font-bold text-gray-700']">
                                                <span>{{ $t('Telegram') }}</span>
                                            </a>
                                        </MenuItem>

                                        <MenuItem v-slot="{ active }">
                                            <a target="_blank" href="https://msng.link/o/?maxpires=sk" :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm font-bold text-gray-700']">
                                                <span>{{ $t('Skype') }}</span>
                                            </a>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>