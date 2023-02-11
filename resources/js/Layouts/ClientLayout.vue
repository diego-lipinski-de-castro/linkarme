<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import {
    Bars3CenterLeftIcon,
    BellIcon,
    ClockIcon,
    ScaleIcon,
    ComputerDesktopIcon
} from '@heroicons/vue/24/outline'
import {
    ChevronDownIcon,
    ChevronRightIcon,
} from '@heroicons/vue/20/solid'
import { useTranslation } from "i18next-vue";
import { useLanguageStore } from '@/stores/language'
import { useCoinStore } from '@/stores/coin'
import { i18nextPromise } from "@/i18n.js";

const languageStore = useLanguageStore()
const coinStore = useCoinStore()
const { t } = useTranslation();
const user = computed(() => usePage().props.value.user)

defineProps({
    title: String,
})

const navigation = [
    { name: t('Dashboard'), href: route('client.dashboard'), icon: ComputerDesktopIcon, current: route().current('client.dashboard') },
    { name: t('Sites'), href: route('client.sites.index'), icon: ClockIcon, current: route().current('client.sites.index') },
    { name: t('Orders'), href: route('client.orders.index'), icon: ScaleIcon, current: route().current('client.orders.index') },
]

const sidebarOpen = ref(false)

const logout = () => {
    Inertia.post(route('client.logout'))
}

const setCoin = async (coin) => {
    await coinStore.setCoin(coin);

    [...document.querySelectorAll('*')].forEach(node => {
        if (node._tippy) {
            node._tippy.destroy();
        }
    });

    tippy('[data-tippy-content]', {
        interactive: true,
    });
}

await languageStore.loadLanguage(user.value.locale)
await coinStore.setCoin(user.value.coin)
await i18nextPromise
</script>
    
<template>
    <div class="min-h-full h-max bg-gray-100">

        <Head :title="title" />

        <Banner />

        <div class="min-h-full h-max">

            <TransitionRoot as="template" :show="sidebarOpen">
                <Dialog as="div" class="relative z-40 lg:hidden" @close="sidebarOpen = false">
                    <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                        enter-from="opacity-0" enter-to="opacity-100"
                        leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                    </TransitionChild>

                    <div class="fixed inset-0 z-40 flex">
                        <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                            enter-from="-translate-x-full" enter-to="translate-x-0"
                            leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                            leave-to="-translate-x-full">
                            <DialogPanel class="relative flex w-full max-w-xs flex-1 flex-col bg-indigo-700 pt-5 pb-4">

                                <div class="flex flex-shrink-0 items-center px-4">
                                    <ApplicationLogo white class="block h-8 w-auto" />
                                </div>
                                <nav class="mt-5 h-full flex-shrink-0 overflow-y-auto" aria-label="Sidebar">
                                    <div class="space-y-1 px-2">
                                        <Link v-for="item in navigation" :key="item.name" :href="item.href"
                                            :class="[item.current ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:text-white hover:bg-indigo-600', 'group flex items-center px-2 py-2 text-base font-medium rounded-md']"
                                            :aria-current="item.current ? 'page' : undefined">
                                        <component :is="item.icon" class="mr-4 h-6 w-6 flex-shrink-0 text-indigo-200"
                                            aria-hidden="true" />
                                        {{ item.name }}
                                        </Link>
                                    </div>

                                    <div class="mt-6 pt-6 border-t border-indigo-800">
                                        <slot name="submenu-mobile" />
                                    </div>
                                </nav>
                            </DialogPanel>
                        </TransitionChild>
                        <div class="w-14 flex-shrink-0" aria-hidden="true">

                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

            <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
                <div class="flex flex-grow flex-col overflow-y-auto bg-indigo-700 pt-5 pb-4">
                    <div class="flex flex-shrink-0 items-center px-4">
                        <ApplicationLogo white class="block h-8 w-auto" />
                    </div>
                    <nav class="sidebar mt-5 flex flex-1 flex-col overflow-y-auto" aria-label="Sidebar">
                        <div class="space-y-1 px-2">
                            <Link v-for="item in navigation" :key="item.name" :href="item.href"
                                :class="[item.current ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:text-white hover:bg-indigo-600', 'group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md']"
                                :aria-current="item.current ? 'page' : undefined">
                                <component :is="item.icon" class="mr-4 h-6 w-6 flex-shrink-0 text-indigo-200"
                                    aria-hidden="true" />
                                {{ item.name }}
                            </Link>
                        </div>
                        <div class="mt-6 pt-6 border-t border-indigo-800">
                            <slot name="submenu" />
                        </div>
                    </nav>
                </div>
            </div>

            <div class="flex flex-1 flex-col lg:pl-64">
                <div class="flex h-16 flex-shrink-0 border-b border-gray-200 bg-white lg:border-none">
                    <button type="button"
                        class="border-r border-gray-200 px-4 text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden"
                        @click="sidebarOpen = true">
                        <Bars3CenterLeftIcon class="h-6 w-6" aria-hidden="true" />
                    </button>

                    <div class="flex flex-1 justify-between px-4 sm:px-6 lg:mx-auto lg:px-8">
                        <div class="flex flex-1">
                            <slot name="uppermenu" />
                        </div>
                        <div class="ml-4 flex items-center md:ml-6">

                            <div class="relative ml-3">
                                <Link :href="route('client.notifications')" :class="[
                                    route().current('client.notifications') ? 'text-gray-700' : 'text-gray-400 hover:text-gray-500',
                                    'block rounded-full bg-white p-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2'
                                ]">
                                    <span class="sr-only">View notifications</span>
                                    <BellIcon class="h-6 w-6" aria-hidden="true" />
                                </Link>
                            </div>

                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton
                                        class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 lg:rounded-md lg:p-2 lg:hover:bg-gray-50">
                                        <img class="h-8 w-8 rounded-full" :src="$page.props.user.profile_photo_url"
                                            :alt="$page.props.user.name" />
                                        <span class="ml-3 hidden text-sm font-medium text-gray-700 lg:block">{{
                                        $page.props.user.name }}</span>
                                        <ChevronDownIcon
                                            class="ml-1 hidden h-5 w-5 flex-shrink-0 text-gray-400 lg:block"
                                            aria-hidden="true" />
                                    </MenuButton>
                                </div>
                                <transition enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">

                                        <MenuItem v-slot="{ active }">
                                            <Link :href="route('client.profile.show')" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">{{ $t('Profile') }}</Link>
                                        </MenuItem>

                                        <!-- <MenuItem v-slot="{ active }">
                                        <a href="#"
                                            :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Settings</a>
                                        </MenuItem> -->

                                        <MenuItem v-slot="{ active }">
                                            <form method="POST" @submit.prevent="logout">
                                                <button
                                                    :class="[active ? 'bg-gray-100' : '', 'w-full block px-4 py-2 text-sm text-gray-700 text-left']">{{
                                                    $t('Logout') }}</button>
                                            </form>
                                        </MenuItem>

                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                </div>

                <main class="flex-1 pb-8">
                    <slot name="header" />

                    <div class="mt-8">
                        <div class="mx-auto px-4 sm:px-6 lg:px-8">
                            <slot />
                        </div>
                    </div>
                </main>
            </div>
        </div>

    </div>
</template>
    