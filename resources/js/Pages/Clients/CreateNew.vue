<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue'
import {
    UserCircleIcon,
} from '@heroicons/vue/24/outline'
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/20/solid';
import Checkbox from '@/Components/Checkbox.vue';

const form = useForm({
    first_name: '',
    last_name: '',
    name: '',
    birthday: '',
    email: '',
    phone: '',
    password: '',

    locale: 'en',
    coin: 'USD',
    full: false,
});

const passwordInput = ref(null);
const showPassword = ref(false)

const generatePassword = () => {
    form.password = Math.random().toString(36).slice(-8);
}

const submit = () => {
    form.post(route('clients.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password');
                passwordInput.value.focus();
            }
        },
    });
};

</script>

<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Add client')">
            <div class="max-w-[1600px] mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-10">
                        <UserCircleIcon class="h-8 w-8" />
                    </div>

                    <div>
                        <span class="block font-bold">{{ $t('Add client') }}</span>
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 py-5">
                    <div class="ml-12 mr-16">
                        <form @submit.prevent="submit" class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
                            <!-- First Name -->
                            <div>
                                <InputLabel for="first_name" :value="$t('First name')" />
                                <TextInput id="first_name" v-model="form.first_name" type="text" class="mt-1 block w-full"
                                    autocomplete="off" />
                                <InputError :message="form.errors.first_name" class="mt-2" />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <InputLabel for="last_name" :value="$t('Last name')" />
                                <TextInput id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full"
                                    autocomplete="off" />
                                <InputError :message="form.errors.last_name" class="mt-2" />
                            </div>


                            <!-- Name -->
                            <div>
                                <InputLabel for="name" :value="$t('Username (optional)')" />
                                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full"
                                    autocomplete="off" />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>

                            <!-- Birthday -->
                            <div>
                                <InputLabel for="birthday" :value="$t('Birthday')" />
                                <TextInput id="birthday" v-model="form.birthday" type="text" class="mt-1 block w-full"
                                    autocomplete="off" />
                                <InputError :message="form.errors.birthday" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="email" :value="$t('Email')" />
                                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" autocomplete="nope-1" />
                                <InputError :message="form.errors.email" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="phone" :value="$t('Phone')" />
                                <TextInput id="phone" v-model="form.phone" type="phone" class="mt-1 block w-full" autocomplete="nope-2"/>
                                <InputError :message="form.errors.phone" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="password" value="Password" />

                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <TextInput id="password" ref="passwordInput" v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        class="block w-full rounded-none rounded-l-md" autocomplete="new-password" />

                                    <button @click="showPassword = !showPassword" type="button"
                                        class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <EyeIcon v-if="!showPassword" class="h-6 w-6" />
                                        <EyeSlashIcon v-else class="h-6 w-6" />
                                    </button>

                                    <button @click="generatePassword" type="button"
                                        class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">{{
                                            $t('Generate') }}</button>
                                </div>

                                <InputError :message="form.errors.password" class="mt-2" />
                            </div>

                            <div>
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

                            <div>
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
                                <InputLabel for="full" :value="$t('Full')" />
                                <Checkbox id="full" v-model:checked="form.full" name="full" />
                                <InputError class="mt-2" :message="form.errors.full" />
                            </div>

                            <div class="self-end mb-1 flex">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Save
                                </PrimaryButton>

                                <ActionMessage :on="form.recentlySuccessful" class="self-end ml-3">
                                    Saved.
                                </ActionMessage>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayoutNew>
    </AppSuspense>
</template>
