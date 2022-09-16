<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
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
    CogIcon,
    CreditCardIcon,
    DocumentChartBarIcon,
    HomeIcon,
    QuestionMarkCircleIcon,
    ScaleIcon,
    ShieldCheckIcon,
    UserGroupIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'
import {
    BanknotesIcon,
    BuildingOfficeIcon,
    CheckCircleIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/20/solid'

defineProps({
    title: String,
});

const navigation = [
    { name: 'Dashboard', href: route('client.dashboard'), icon: HomeIcon, current: route().current('client.dashboard') },
    { name: 'Sites', href: route('client.sites.index'), icon: ClockIcon, current: route().current('client.sites.index') },
    { name: 'Pedidos', href: route('client.orders.index'), icon: ScaleIcon, current: route().current('client.orders.index') },
]

const sidebarOpen = ref(false)

const logout = () => {
    Inertia.post(route('client.logout'));
};
</script>
    
<template>
    <div class="min-h-full h-full bg-gray-100">

        <Head :title="title" />

        <Banner />

        <div class="min-h-full h-full">
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
                                    <ApplicationLogo class="block h-8 w-auto" />
                                </div>
                                <nav class="mt-5 h-full flex-shrink-0 overflow-y-auto"
                                    aria-label="Sidebar">
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
                                        <slot name="submenu-mobile"/>
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
                    <nav class="mt-5 flex flex-1 flex-col overflow-y-auto"
                        aria-label="Sidebar">
                        <div class="space-y-1 px-2">
                            <a v-for="item in navigation" :key="item.name" :href="item.href"
                                :class="[item.current ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:text-white hover:bg-indigo-600', 'group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md']"
                                :aria-current="item.current ? 'page' : undefined">
                                <component :is="item.icon" class="mr-4 h-6 w-6 flex-shrink-0 text-indigo-200"
                                    aria-hidden="true" />
                                {{ item.name }}
                            </a>
                        </div>
                        <div class="mt-6 pt-6 border-t border-indigo-800">
                            <slot name="submenu"/>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="flex flex-1 flex-col lg:pl-64">
                <div class="flex h-16 flex-shrink-0 border-b border-gray-200 bg-white lg:border-none">
                    <button type="button"
                        class="border-r border-gray-200 px-4 text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden"
                        @click="sidebarOpen = true">
                        <span class="sr-only">Open sidebar</span>
                        <Bars3CenterLeftIcon class="h-6 w-6" aria-hidden="true" />
                    </button>

                    <div class="flex flex-1 justify-between px-4 sm:px-6 lg:mx-auto lg:px-8">
                        <div class="flex flex-1">
                            <form class="flex w-full md:ml-0" action="#" method="GET">
                                <label for="search-field" class="sr-only">Search</label>
                                <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center"
                                        aria-hidden="true">
                                        <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
                                    </div>
                                    <input id="search-field" name="search-field"
                                        class="block h-full w-full border-transparent py-2 pl-8 pr-3 text-gray-900 placeholder-gray-500 focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm"
                                        placeholder="Search transactions" type="search" />
                                </div>
                            </form>
                        </div>
                        <div class="ml-4 flex items-center md:ml-6">
                            <button type="button"
                                class="rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="sr-only">View notifications</span>
                                <BellIcon class="h-6 w-6" aria-hidden="true" />
                            </button>

                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton
                                        class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 lg:rounded-md lg:p-2 lg:hover:bg-gray-50">
                                        <img class="h-8 w-8 rounded-full"
                                            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                            alt="" />
                                        <span class="ml-3 hidden text-sm font-medium text-gray-700 lg:block"><span
                                                class="sr-only">Open user menu for </span>{{ $page.props.user.name }}</span>
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
                                        
                                        <!-- <MenuItem v-slot="{ active }">
                                        <a href="#"
                                            :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Your
                                            Profile</a>
                                        </MenuItem>

                                        <MenuItem v-slot="{ active }">
                                        <a href="#"
                                            :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Settings</a>
                                        </MenuItem> -->

                                        <MenuItem v-slot="{ active }">
                                            <form method="POST" @submit.prevent="logout">
                                                <button
                                                    :class="[active ? 'bg-gray-100' : '', 'w-full block px-4 py-2 text-sm text-gray-700 text-left']">Sair</button>
                                            </form>
                                        </MenuItem>

                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                </div>

                <main class="flex-1 pb-8">
                    <slot name="header"/>

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
    