<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import ApplicationLogoNew from '@/Components/ApplicationLogoNew.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/24/outline';
import { useLanguageStore } from '@/stores/language'

const languageStore = useLanguageStore()

const props = defineProps({
    status: String,
});
</script>

<template>
    <Head :title="$t('Login')" />

    <div class="min-h-full relative flex justify-center">
        <div class="z-0 fixed inset-0">
            <img class="h-full w-full object-cover" src="@/assets/images/login-bg.jpg" alt="Background" />
        </div>

        <div class="z-10 absolute flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8 sm:w-full sm:max-w-xl">
            <div class="relative mx-5 sm:mx-auto sm:w-full">

                <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">

                    <div class="sm:mx-auto sm:w-full">
                        <ApplicationLogoNew class="mx-auto h-12 w-auto" />

                        <h3 class="font-bold text-center uppercase mt-3 text-xl">{{ $t('Your site was successfully submitted!') }}</h3>
                    </div>

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
                                <MenuButton
                                    class="inline-flex w-full justify-center items-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-500 shadow-sm hover:bg-gray-50">
                                    <span>{{ $t('Our team is here') }}</span>
                                    <ChevronDownIcon class="ml-3 h-4 w-4" />
                                </MenuButton>

                                <transition enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-48 origin-x-right rounded-md overflow-hidden bg-white border border-gray-300 shadow-sm focus:outline-none">

                                        <MenuItem v-slot="{ active }">
                                        <a target="_blank"
                                            href="https://wa.me/5547989104747?text=Ol%C3%A1%21+Preciso+de+ajuda+para+logar+no+sistema"
                                            :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm font-bold text-gray-700']">
                                            <span>{{ $t('Whatsapp') }}</span>
                                        </a>
                                        </MenuItem>

                                        <MenuItem v-slot="{ active }">
                                        <a target="_blank" href="https://t.me/maxpiress"
                                            :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm font-bold text-gray-700']">
                                            <span>{{ $t('Telegram') }}</span>
                                        </a>
                                        </MenuItem>

                                        <MenuItem v-slot="{ active }">
                                        <a target="_blank" href="https://msng.link/o/?maxpires=sk"
                                            :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm font-bold text-gray-700']">
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
</div></template>