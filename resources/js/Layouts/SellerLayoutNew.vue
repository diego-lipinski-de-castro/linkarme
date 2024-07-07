<script setup>
import { computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import Banner from '@/Components/Banner.vue';
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Disclosure, DisclosureButton, DisclosurePanel,
} from '@headlessui/vue'
import {
    BellIcon,
    ClockIcon,
    ComputerDesktopIcon,
    Bars3Icon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'
import {
    XCircleIcon,
    IdentificationIcon,
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
    { name: t('Dashboard'), href: route('seller.dashboard'), icon: ComputerDesktopIcon, current: route().current('seller.dashboard') },
    { name: t('Sites'), href: route('seller.sites.index'), icon: ClockIcon, current: route().current('seller.sites.*') },
    // { name: t('Orders'), href: route('seller.orders.index'), icon: ScaleIcon, current: route().current('seller.orders.*') },
]

const logout = () => {
    Inertia.post(route('seller.logout'))
}

const particlesOptions = {
    detectRetina: true,
    fullScreen: false,
    particles: {
        number: { value: 150, density: { enable: true, value_area: 1000 } },
        color: { value: "#ffffff" },
        opacity: {
            value: 0.2,
            random: false,
            anim: { enable: false, speed: 1, opacity_min: 0.1, sync: false }
        },
        size: {
            value: 3,
            random: true,
            anim: { enable: false, speed: 40, size_min: 0.1, sync: false }
        },
        links: {
            enable: true,
            distance: 150,
            color: "#ffffff",
            opacity: 0.2,
            width: 1,
        },
        move: {
            enable: true,
            speed: 0.5,
            direction: "none",
            random: false,
            straight: false,
            out_mode: "out",
            bounce: false,
            attract: { enable: false, rotateX: 600, rotateY: 1200 }
        }
    },
    interactivity: {
        detect_on: "canvas",
        events: {
            onhover: { enable: true, mode: "repulse" },
            onclick: { enable: true, mode: "push" },
            resize: true
        },
        modes: {
            grab: { distance: 400, line_linked: { opacity: 1 } },
            bubble: { distance: 400, size: 40, duration: 2, opacity: 8, speed: 3 },
            repulse: { distance: 200, duration: 0.4 },
            push: { particles_nb: 4 },
            remove: { particles_nb: 2 }
        }
    },
};

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


await coinStore.setCoin(coinStore.coin)
await i18nextPromise
</script>

<template>
    <div>

        <Head :title="title" />

        <Banner />

        <!-- min-h-full h-max -->
        <div class="flex flex-col h-screen">

            <div class="relative bg-gradient-to-b from-blue-700 via-blue-500 to-blue-400 pb-32">
                <div class="absolute inset-0">
                    <vue-particles id="tsparticles" :options="particlesOptions" class="w-full h-full" />
                </div>

                <Disclosure as="nav" class="relative z-10 border-b border-white border-opacity-50 lg:border-none"
                    v-slot="{ open }">
                    <div class="mx-auto max-w-7xl px-2 sm:px-4 lg:px-8">
                        <div
                            class="relative flex h-16 items-center justify-between lg:border-b lg:border-blue-400 lg:border-opacity-25">
                            <Link :href="route('dashboard')" class="flex items-center px-2 lg:px-0">
                            <div class="flex space-x-2 flex-shrink-0">
                                <img class="block h-8" src="@/assets/images/header-logo.png" alt="Linkarme" />

                                <div class="flex items-end h-6">
                                    <img class="block h-2" src="@/assets/images/linking-slogan.png" alt="Slogan" />
                                </div>
                            </div>
                            </Link>
                            <!-- <div class="flex flex-1 justify-center px-2 lg:ml-6 lg:justify-end">
                                                    <div class="w-full max-w-lg lg:max-w-xs">
                                                        <label for="search" class="sr-only">Search</label>
                                                        <div class="relative text-gray-400 focus-within:text-gray-600">
                                                            <div
                                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                                <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
                                                            </div>
                                                            <input id="search"
                                                                class="block w-full rounded-md border border-transparent bg-white py-2 pl-10 pr-3 leading-5 text-gray-900 placeholder-gray-500 focus:border-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 sm:text-sm"
                                                                placeholder="Search" type="search" name="search" />
                                                        </div>
                                                    </div>
                                                </div> -->

                            <div class="flex flex-1 justify-center items-center px-2 lg:ml-6 lg:justify-end">
                                <Menu as="div" class="relative">
                                    <div>
                                        <MenuButton
                                            class="flex max-w-xs items-center rounded-full bg-white bg-opacity-[15%] text-sm focus:outline-none lg:rounded-md lg:p-2">
                                            <span class="text-sm font-medium text-white">
                                                {{ coinStore.coin }}
                                            </span>
                                        </MenuButton>
                                    </div>
                                    <transition enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95">
                                        <MenuItems
                                            class="absolute right-0 z-10 mt-2 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">

                                            <MenuItem v-slot="{ active }">
                                            <button @click="setCoin('USD')"
                                                :class="[active ? 'bg-gray-100' : '', 'w-full block px-4 py-2 text-sm text-gray-700 text-left']">
                                                USD
                                            </button>
                                            </MenuItem>

                                            <MenuItem v-slot="{ active }">
                                            <button @click="setCoin('BRL')"
                                                :class="[active ? 'bg-gray-100' : '', 'w-full block px-4 py-2 text-sm text-gray-700 text-left']">
                                                BRL
                                            </button>
                                            </MenuItem>

                                            <!-- <MenuItem v-slot="{ active }">
                                            <button @click="setCoin('EUR')"
                                                :class="[active ? 'bg-gray-100' : '', 'w-full block px-4 py-2 text-sm text-gray-700 text-left']">
                                                EUR
                                            </button>
                                            </MenuItem> -->

                                        </MenuItems>
                                    </transition>
                                </Menu>
                                
                                <Menu as="div" class="relative ml-3">
                                    <div>
                                        <MenuButton
                                            class="flex max-w-xs items-center rounded-full text-sm focus:outline-none lg:rounded-md lg:p-2">
                                            <span class="block w-8 h-8">
                                                <img v-if="languageStore.language == 'en'"
                                                    src="data:image/png;base64,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" />
                                                
                                                <img v-if="languageStore.language == 'pt'"
                                                    src="data:image/png;base64,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" />
                                                
                                                <img v-if="languageStore.language == 'es'"
                                                    src="data:image/png;base64,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" />
                                            </span>
                                        </MenuButton>
                                    </div>
                                    <transition enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95">
                                        <MenuItems
                                            class="absolute right-0 z-10 mt-2 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">

                                            <MenuItem v-slot="{ active }">
                                                <button @click="languageStore.loadLanguage('en')"
                                                    :class="[active ? 'bg-gray-100' : '', 'block px-2 py-2 w-12 h-12']">
                                                    <img
                                                        src="data:image/png;base64,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">
                                                </button>
                                            </MenuItem>

                                            <MenuItem v-slot="{ active }">
                                                <button @click="languageStore.loadLanguage('pt')"
                                                    :class="[active ? 'bg-gray-100' : '', 'block px-2 py-2 w-12 h-12']">
                                                    <img
                                                        src="data:image/png;base64,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">
                                                </button>
                                            </MenuItem>

                                            <MenuItem v-slot="{ active }">
                                                <button @click="languageStore.loadLanguage('es')"
                                                    :class="[active ? 'bg-gray-100' : '', 'block px-2 py-2 w-12 h-12']">
                                                    <img
                                                        src="data:image/png;base64,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">
                                                </button>
                                            </MenuItem>

                                        </MenuItems>
                                    </transition>
                                </Menu>
                            </div>

                            <div class="flex lg:hidden">
                                <DisclosureButton
                                    class="inline-flex items-center justify-center rounded-md bg-blue-600 p-2 text-blue-200 hover:bg-blue-500 hover:bg-opacity-75 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                                    <span class="sr-only">Open main menu</span>
                                    <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                                    <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
                                </DisclosureButton>
                            </div>

                            <div class="hidden lg:ml-4 lg:block">
                                <div class="flex items-center">
                                    <!-- <button type="button"
                                                            class="flex-shrink-0 rounded-full bg-blue-600 p-1 text-blue-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                                                            <span class="sr-only">View notifications</span>
                                                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                                                        </button> -->

                                    <!-- Profile dropdown -->
                                    <Menu as="div" class="relative flex-shrink-0">
                                        <div>
                                            <MenuButton
                                                class="flex rounded-full bg-blue-600 text-sm text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                                                <span class="sr-only">Open user menu</span>
                                                <img class="h-8 w-8 rounded-full" :src="$page.props.user.profile_photo_url"
                                                    :alt="$page.props.user.name" />
                                            </MenuButton>
                                        </div>
                                        <transition enter-active-class="transition ease-out duration-100"
                                            enter-from-class="transform opacity-0 scale-95"
                                            enter-to-class="transform opacity-100 scale-100"
                                            leave-active-class="transition ease-in duration-75"
                                            leave-from-class="transform opacity-100 scale-100"
                                            leave-to-class="transform opacity-0 scale-95">
                                            <MenuItems
                                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md overflow-hidden bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">

                                                <span class="flex flex-col px-4 py-4">
                                                    <span class="text-xs text-gray-500">{{ $t('Signed in as') }}</span>
                                                    <span class="text-sm font-bold">{{ $page.props.user.name }}</span>
                                                </span>

                                                <hr>

                                                <MenuItem v-slot="{ active }">
                                                <Link :href="route('seller.profile.show')"
                                                    :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 mt-2 py-2 px-4 text-sm text-gray-700']">
                                                <IdentificationIcon class="h-5 w-5" />
                                                <span>{{ $t('Account settings') }}</span>
                                                </Link>
                                                </MenuItem>

                                                <hr>

                                                <MenuItem>
                                                    <a v-if="user.is_impersonated" :href="route('impersonate.leave', 'sellers.index')"
                                                        class="bg-gray-100 flex px-4 py-4 text-sm text-gray-700 w-full space-x-2">
                                                        <XCircleIcon class="h-5 w-5" />
                                                        <span>{{ $t('Sign out') }}</span>
                                                    </a>

                                                    <button v-else @click="logout"
                                                        class="bg-gray-100 flex px-4 py-4 text-sm text-gray-700 w-full space-x-2">
                                                        <XCircleIcon class="h-5 w-5" />
                                                        <span>{{ $t('Sign out') }}</span>
                                                    </button>
                                                </MenuItem>
                                            </MenuItems>
                                        </transition>
                                    </Menu>
                                </div>
                            </div>
                        </div>
                    </div>

                    <DisclosurePanel class="lg:hidden">
                        <div class="space-y-1 px-2 pt-2 pb-3">
                            <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href"
                                :class="[item.current ? 'bg-blue-900 text-white' : 'text-white hover:bg-blue-700 hover:bg-opacity-50', 'transition-colors block rounded-md py-2 px-3 text-base font-medium']"
                                :aria-current="item.current ? 'page' : undefined">
                                {{ item.name }}
                            </DisclosureButton>
                        </div>
                        <div class="border-t border-blue-700 pt-4 pb-3">
                            <div class="flex items-center px-5">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full" :src="$page.props.user.profile_photo_url" alt="" />
                                </div>
                                <div class="ml-3">
                                    <div class="text-base font-medium text-white">{{ $page.props.user.name }}</div>
                                    <div class="text-sm font-medium text-blue-300">{{ $page.props.user.email }}</div>
                                </div>
                                <button type="button"
                                    class="ml-auto flex-shrink-0 rounded-full bg-blue-600 p-1 text-blue-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                                    <span class="sr-only">View notifications</span>
                                    <BellIcon class="h-6 w-6" aria-hidden="true" />
                                </button>
                            </div>
                            <div class="mt-3 space-y-1 px-2">
                                <DisclosureButton as="a" :href="route('profile.show')"
                                    class="block rounded-md py-2 px-3 text-base font-medium text-white hover:bg-blue-500 hover:bg-opacity-75">
                                    {{ $t('Profile') }}
                                </DisclosureButton>

                                <DisclosureButton as="button" @click="logout"
                                    class="block rounded-md py-2 px-3 text-base font-medium text-white hover:bg-blue-500 hover:bg-opacity-75">
                                    {{ $t('Logout') }}
                                </DisclosureButton>
                            </div>
                        </div>
                    </DisclosurePanel>
                </Disclosure>
                <header class="relative py-10">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="z-10 flex space-x-4">
                            <Link v-for="item in navigation" :key="item.name" :href="item.href"
                                :class="[item.current ? 'bg-blue-900 text-white' : 'text-white hover:bg-blue-700 hover:bg-opacity-50', 'transition-colors rounded-md py-2 px-3 text-sm font-medium']"
                                :aria-current="item.current ? 'page' : undefined">{{ item.name }}</Link>
                        </div>
                    </div>
                </header>
            </div>

            <main class="-mt-32 mb-auto relative">
                <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>

            <footer class="bg-blue-900">
                <div class="flex justify-end items-center space-x-12 mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <span class="text-white">&#169; {{ new Date().getFullYear() }} Linking. {{ $t('All rights reserved.')
                    }}</span>

                    <img class="h-10" src="@/assets/images/footer-logo.png" alt="Slogan">
                </div>
            </footer>
        </div>

    </div>
</template>
