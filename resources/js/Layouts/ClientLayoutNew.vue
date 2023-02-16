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
    Disclosure, DisclosureButton, DisclosurePanel,
} from '@headlessui/vue'
import {
    BellIcon,
    ClockIcon,
    ScaleIcon,
    ComputerDesktopIcon,
    Bars3Icon,
    XMarkIcon,
    MagnifyingGlassIcon
} from '@heroicons/vue/24/outline'
import {
    ChevronDownIcon,
    ChevronRightIcon,
    XCircleIcon,
    IdentificationIcon,
    HeartIcon,
    FlagIcon,
    Cog6ToothIcon,
    CurrencyDollarIcon,
    BugAntIcon,
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

await languageStore.loadLanguage(user.value.locale)
await coinStore.setCoin(user.value.coin)
await i18nextPromise
</script>

<template>
    <div class="min-h-full h-max bg-gray-100">

        <Head :title="title" />

        <Banner />

        <div class="min-h-full h-max ">

            <div class="relative bg-gradient-to-b from-blue-700 via-blue-500 to-blue-400 pb-32">
                <!-- <div class="absolute inset-0">
                    <img class="h-full object-cover" src="@/assets/images/header-bg.png" alt="Background" />
                </div> -->

                <Disclosure as="nav" class="border-b border-white border-opacity-50 lg:border-none"
                    v-slot="{ open }">
                    <div class="mx-auto max-w-7xl px-2 sm:px-4 lg:px-8">
                        <div
                            class="relative flex h-16 items-center justify-between lg:border-b lg:border-blue-400 lg:border-opacity-25">
                            <div class="flex items-center px-2 lg:px-0">
                                <div class="flex space-x-2 flex-shrink-0">
                                    <img class="block h-8"
                                        src="@/assets/images/header-logo.png"
                                        alt="Linkarme" />

                                    <div class="flex items-end h-6">
                                        <img class="block h-2"
                                            src="@/assets/images/linking-slogan.png"
                                            alt="Slogan" />
                                    </div>
                                </div>
                            </div>
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

                            <div class="flex flex-1 justify-center px-2 lg:ml-6 lg:justify-end">
                                <button class="justify-center rounded-md border border-transparent bg-blue-900 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-900 hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    {{ $t('Talk to our experts') }}
                                </button>
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
                                    <Menu as="div" class="relative ml-3 flex-shrink-0">
                                        <div>
                                            <MenuButton
                                                class="flex rounded-full bg-blue-600 text-sm text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600">
                                                <span class="sr-only">Open user menu</span>
                                                <img class="h-8 w-8 rounded-full" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
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
                                                    <Link :href="route('client.profile.show')" :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 mt-2 py-2 px-4 text-sm text-gray-700']">
                                                        <IdentificationIcon class="h-5 w-5"/>
                                                        <span>{{ $t('Account settings') }}</span>
                                                    </Link>
                                                </MenuItem>

                                                <MenuItem v-slot="{ active }">
                                                    <Link href="#" :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm text-gray-700']">
                                                        <HeartIcon class="h-5 w-5"/>
                                                        <span>{{ $t('Favorites') }}</span>
                                                    </Link>
                                                </MenuItem>

                                                <MenuItem v-slot="{ active }">
                                                    <Link href="#" :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm text-gray-700']">
                                                        <FlagIcon class="h-5 w-5"/>
                                                        <span>{{ $t('Interest list') }}</span>
                                                    </Link>
                                                </MenuItem>

                                                <MenuItem v-slot="{ active }">
                                                    <Link href="#" :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm text-gray-700']">
                                                        <Cog6ToothIcon class="h-5 w-5"/>
                                                        <span>{{ $t('Your projects') }}</span>
                                                    </Link>
                                                </MenuItem>

                                                <MenuItem v-slot="{ active }">
                                                    <Link href="#" :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm text-gray-700']">
                                                        <CurrencyDollarIcon class="h-5 w-5"/>
                                                        <span>{{ $t('Orders') }}</span>
                                                    </Link>
                                                </MenuItem>

                                                <MenuItem v-slot="{ active }">
                                                    <Link href="#" :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 mb-2 py-2 px-4 text-sm text-gray-700']">
                                                        <div class="w-5">
                                                            <BugAntIcon class="h-5 w-5"/>
                                                        </div>
                                                        <span>{{ $t('Bug report and suggestions') }}</span>
                                                    </Link>
                                                </MenuItem>

                                                <hr>

                                                <MenuItem>
                                                    <button @click="logout" class="bg-gray-100 flex px-4 py-4 text-sm text-gray-700 w-full space-x-2">
                                                        <XCircleIcon class="h-5 w-5"/>
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
                            <DisclosureButton 
                                v-for="item in navigation"
                                :key="item.name" as="a" 
                                :href="item.href"
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
                                <DisclosureButton
                                    as="a"
                                    :href="route('client.profile.show')"
                                    class="block rounded-md py-2 px-3 text-base font-medium text-white hover:bg-blue-500 hover:bg-opacity-75">
                                    {{ $t('Profile') }}
                                </DisclosureButton>

                                <DisclosureButton 
                                    as="button"
                                    @click="logout"
                                    class="block rounded-md py-2 px-3 text-base font-medium text-white hover:bg-blue-500 hover:bg-opacity-75">
                                    {{ $t('Logout') }}
                                </DisclosureButton>
                            </div>
                        </div>
                    </DisclosurePanel>
                </Disclosure>
                <header class="py-10">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="flex space-x-4">
                            <a v-for="item in navigation" :key="item.name" :href="item.href"
                                :class="[item.current ? 'bg-blue-900 text-white' : 'text-white hover:bg-blue-700 hover:bg-opacity-50', 'transition-colors rounded-md py-2 px-3 text-sm font-medium']"
                                :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
                        </div>
                    </div>
                </header>
            </div>

            <main class="-mt-32 relative">
                <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>

            <footer class="bg-blue-900">
                <div class="flex justify-end items-center space-x-12 mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <span class="text-white">&#169; {{ new Date().getFullYear() }} Linking. {{ $t('All rights reserved.') }}</span>

                    <img class="h-10" src="@/assets/images/footer-logo.png" alt="Slogan">
                </div>
            </footer>
        </div>

    </div>
</template>
