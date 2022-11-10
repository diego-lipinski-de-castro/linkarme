<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
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
    CogIcon,
    CreditCardIcon,
    DocumentChartBarIcon,
    QuestionMarkCircleIcon,
    ScaleIcon,
    ShieldCheckIcon,
    UserGroupIcon,
    XMarkIcon,
    ComputerDesktopIcon
} from '@heroicons/vue/24/outline'
import {
    BanknotesIcon,
    BuildingOfficeIcon,
    CheckCircleIcon,
    ChevronDownIcon,
    ChevronRightIcon,
} from '@heroicons/vue/20/solid'
import { ref } from 'vue';
import { useTranslation } from "i18next-vue";
import { useLanguageStore } from '@/stores/language'
import { useCoinStore } from '@/stores/coin'
import { i18nextPromise } from "@/i18n.js";

const languageStore = useLanguageStore()
const coinStore = useCoinStore()
const { t } = useTranslation();

defineProps({
    title: String,
});

const navigation = [
    // { name: t('Dashboard'), href: route('dashboard'), icon: ComputerDesktopIcon, current: route().current('dashboard') },
    { name: t('Sites'), href: route('sites.index'), icon: ClockIcon, current: route().current('sites.index') },
    // { name: t('Orders'), href: route('orders.index'), icon: ScaleIcon, current: route().current('orders.index') },
    { name: t('Clients'), href: route('clients.index'), icon: ClockIcon, current: route().current('clients.index') },
    { name: t('Sellers'), href: route('sellers.index'), icon: ClockIcon, current: route().current('sellers.index') },
    { name: t('Categories'), href: route('categories.index'), icon: ClockIcon, current: route().current('categories.index') },
    { name: t('Languages'), href: route('languages.index'), icon: ClockIcon, current: route().current('languages.index') },
    { name: t('Countries'), href: route('countries.index'), icon: ClockIcon, current: route().current('countries.index') },
]

const sidebarOpen = ref(false)

const logout = () => {
    Inertia.post(route('logout'));
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

                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton
                                        class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 lg:rounded-md lg:p-2 lg:hover:bg-gray-50">
                                        <span class="text-sm font-medium text-gray-700">
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

                                        <MenuItem v-slot="{ active }">
                                        <button @click="setCoin('EUR')"
                                            :class="[active ? 'bg-gray-100' : '', 'w-full block px-4 py-2 text-sm text-gray-700 text-left']">
                                            EUR
                                        </button>
                                        </MenuItem>

                                    </MenuItems>
                                </transition>
                            </Menu>

                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton
                                        class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 lg:rounded-md lg:p-2 lg:hover:bg-gray-50">
                                        <span class="block w-8 h-8">
                                            <img v-if="languageStore.language == 'en'"
                                                src="data:image/png;base64,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" />
                                            
                                            <img v-if="languageStore.language == 'pt'"
                                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAABmJLR0QA/wD/AP+gvaeTAAANQUlEQVR4nO2de3BU1R3Hv+fuK+83IUgDKHkpiIEKY4XyEB9EsmQ3FEQtCj4qLUrBqtR22jLt1ClWx0rLyKDloVMVwmSTbBJFKwQBW8vwtEBCQsAkSICQZJMNkH3cX//YrCaQ7H3vBrqffzKZnPM7J7/HOb9z7rnnAmHChAkTJkyYMGHChPl/g4W6A4GwFltTXRw/nuO5bJ5RNgOyGZBKQDSARPh+AkAXgDYGdBFwjoATAKo5oMbjNhz8aH7RhZD9EwIMKgOY7eYouPX3Ecffw4jNIGAslPeRQOwrMNpJHL8jqtv0adH8ostq9FcNQm6AVatWcfvHHb4bjBYSsABAnMZNdgAoBaOiSJexsmh+kVfj9gISMgNM37goIjax7Qki9gKAm0PUjXoCXuNN3Rs+evCj7lB0IOgGMNvNUfByPwOx5wkYFuz2+4MBZ4nYa8zgWWc32y8Fue3gkV9SYCZiaxgwKpjtSqAJjFaUW0q3BavBoBjAYrOM8gB/BZAfjPYUQ6yMGTzP2c32Bq2b0twAZpvFQsAG+NLG64kOBjxtt5Zs1bIRzQyQV5ln0rmMr4LYMq3aCAYMWO8xdS/TapLWxADWYmuym+PLQewuLeQHG2K01+Myztk+v6hVbdmqG2BO6ZybiOc+JuB2tWWHmOM6Rg+UWkob1RSqqgHM2+bmkM77CYB0NeUOIho4jr+/rKCsRi2Bqhkg354/HB79XgAj1ZI5KCF2hic2uXJu8ddqiOPUEGIttibDo/8UN7ryAYDRcMbxlQ9snZekhjjFBsirzDO5Ob4cwK0q9Oe6gAG3GQxue15lnkmpLMUG0LmMr98o2Y5E7ta5jK8qFaJoDsgvKfgRiBUp7YQc4g08lox2wsQR/lYXi1aXKqOpZIjY3IpCW7Hc+rINYLFZRnmBQwTEy5Uhlykp3Vgy2ol4Aw8AcHoYNp+OwcfNEcHuCgC08zyXK3dSlu02HmBNsJUfb+CxMqcDK3M6vlU+AMToCUszOvHb2xxIMvIBJGhCAuP4N+VWlhUBPfs7NrmNyuFqrx+IkEUDoznlllK75GpSK5jt5ijy6I4hSCmnf6yfkiJtK2ZfqzHYc8OpSLdhjNTHnZJ7R279UgRJ+VNSurF2Qptk5QPAxCQX1k5oxay0Kxr0rF9uvmRwPyO1kqQIyKvMM+m6TfUAbpLakBTkev1ABCsaGHC2sz3hlqrFm0RbXVKP9C7jk9BY+Uq8fiCCFQ0EDItNbHtcSh3RBli1ahXX8wBdEwbKcNQiaJkSsRdB4kcWndiC0SvipjNiy+X1KjBTUrrxuzEdyIr1aCG+D8Mjvbhv6BV0eTjUOfVaNJGUcSJrR+2HNaLWBaJ7wLy6hWAkv1v9IGWsJ2KobRiKPYeycKRuBC46onGhzXeEKDWxA0nxXbgjswGTc2uQNeJcQFn+aJiU1K3J3MB5dQsBfC6mrKhQmbd1XuQVg/usmgsvsXk9EUPV/hxsKJ2GxnPiNiDTh7biSUsVpk2oARNwGo3WDR2RbkOamJRU1BA0+uHs2cSwSHm/fF6/PKsTj4y4hAhdYOV8cyERK9c8hG2fTUJHV6ToNjq6IlG1/1bsO3YLxud8jdiogSdfIwdMSnIhM8aDrxxGXPaq8ojE5Gb4V+3W47VCBUXFHnH8Pcr7JC3D+aouHUv/9BiOn5KfdB2rH44lf1yMA9XCx5DUzpQ4jp8hqpyYQoyYKGEDITXDOVgzEitefwRtndGCZYXo6IrEyjUP4VDNCMGyamZKBMwUU04w3qzF1lQ3o2YxZftD7Fjv52xLPH76ymK0O6PkNDcgcdGX8dbLmzE8VdzBBhXmBp7pvUPtZntLoEKCEeDi+PGQoXw5eT0Rw+/ftqqufMAXCa9sNINI3L+iQjRwvJfLFSokmIZyPJdNEtNPqV7v59BpK+6ZPhPmBw2IivTlBzxP6HR60NnlRqfTjfMtl3Hm7CWcae5C1yVp64ajJ4dj98FsTJ1QLbqOf26QEw2M57IB/DNQGUED9LyZIgolezhEDCk3Lcb4ieJPMJ5vuYKjNW04WtOG/1a34cTJDvAU2FneKZkmyQCA/HUDY5QtVEbQAAzIEtOYXK/309g6DSOypR0fTU2JQGrKMMyY7Dvl3trejc//3YxdXzTj8LFW8Py1xmhoTkZtYxoy05sl91FqNBCg3AAA0gL9Ua2dy5auGRDOUwKTlGCCZdZIWGaNxMW2btgqT6Psk0Z0dLr6lNt7KFOWAQCJ0UAsoO4AcQaIHegPSr2+Nw1nI1FXdgrtDhe6XV643D6ZRgMHk1GHmBg9hiRHIm1IJFKHRGBoSuCFWXKiCU89mo0fz83Ax1VN+LCkHs3nfQvTI7XKD+6JigZGA+rOjywDqL1fDwDb7KfQdL5ddPn4OCNyMuKRk5GAsTkJyB2bDIP+Wm+MiNDBMmskHpyZjveLT+IDWz0utAvqRRQiokEVA8T0/kVNr+/NRYe0RZejw4UvD1zAlwd8b6DGRBsweWIqpv4gDRNzh8Bo6KsMo4HDoocyMWvG97D+vSOq9RsIGA2qGKAPLp7Bq+6mKACAKdyCcXa5sb3qDLZXnUF8nBHWvJEonD0KcTGGPuXSUiPx0rN3Ap3K2rsaLzE4PdL/CTH5lLP3L/9pNWLpgSTVTx0kxzuFC4nE0eHCpi21WPDMTry16ThaWvvu7zid6r4mvKfFhKUHErGn5ZqTioJmFmOAa4Q4PQxr62Lxh2Pxqu2lpySo7JIALl32YEvZKSx8dhc+LKmHpyd02x0OVeQ73BxWV8dhdXUcHO5+9aCNAfyoGQ3jMlV976EPl694se7daix5aS9ONzrR1SltIdYfAbz+O4ipYoCACbNa0TA5V7V3Hgak7lQHnnlxLxL0W2TLEOH13+HbxAyIoMZ6Lr4QRGk0ZKafR3raRVl1pTA06TxGDj0mq64or+8FAwS9StAAHDHRrqkkGhgjPFmwS1IdOTxt3Sm5jiSv7wWJ0J2gNJ7jJY8NcqNh2oQa3HbLGanNiWbs6CZMyRV8StgHqV7fGxKhO2EDuIwHAEjO/OVEA2OE3z5dioQY9a9riIu5jF89YRd8SO9Hrtf3gjfy3EGhQqJWDmab5YiS105j9ITHRzlFP289VDMCL/zlYXi8oo8tBcSg9+K15R/gjixxNw/saTFh3ckYuYr3c7DcWjJBqJC4h/LADiU9kRoNudkNeOMX7yMxtktJswB8nr962RZRylfB67+FidSZ2FMRigzgR8rccHtGI9a+vBljRsufE8aObsL6X2/EhJzTgmWVjPX9QYxE6SxkB7MmJbmwNKNT8HkrEcPug9l4p2QaGpqTRckekXYRT1mq8MPxJwTHfIebw7qTMaopHgAY4IhwG4aJOZglevco32b5O4AnFPXsKqTODbWNadh7KBNHatPR0h6LC22+zcYhiZ1ISejEuMxGTMk9gYz0wEcT/ag01l8Lo7fLLaU/EVNUym7oe1DZAP65YV+rSVQ0ZKY3y36S1RstvL435NOVKESb/s7DuZ8DqJfVIwG02mHtD7XH+n6oqygo3SO2sOg8r6qqijIX5LgZMFtevwLj4hn2tZpw0mnA7fFuRAqcG5WKw83hzdpYfNAQjW5eu3uqCHi59tbq/WLLSxr8eFP3BgDfSO6VBLSIhiB4vZ8m3tS9WUoFSSudun/UeTMX5DAG3C+tX9JQKxqC5fV+GLHfVMyxfyGljuTpn9N73yJAOLFWASXREESvBwAw4GSnI369jHrSMdsseQRUyqkrF7HrBq0znIEgnsuvmFtcIbWe7LjML7aWgtEcufXlILRu0CyvF4KYrbzQViinquy31JjB8xx5dFMBJMiVIZWB1g2h8voe2nQc/3O5lRXNTOZiaz4xKlMqRw7+w2EAQuP1PgiM5pZbSmXfm6FYcfklBW9e73eDyoWIvVFRaHteiQzFbuM1ul4CICn1ukHYHeXR/1KpEFWGjnu3zouPMLh3AbhDDXmDHmJH3R79VDUucg1fWymdJqb3TlbrYm/VZq5yc/kZjuMfAKD5jeMhpIEnNlPNW9VVTR3KCspq3Dx3F4ipe/x4EEDAMR2jKZWFNlHnpMSieu62fW7xWbdHPwM31sS8Gwb3FLXvjQY0MAAAbJ9f1OpMaJ/GiK2GjCMtgwgCozWRbsO9FfkVbVo0oP0HHIqtBcRoI67DDzgQsacqCm2a3ouq+fLRXmgr5XluPIiVad2WahCz6RiN1Vr5QJC3EHq2LtYgdJ+tEqKeeG6ZnF1NuQR1A8VeaCuPdBvGELACxLQ7BCqdJgDLne0JY4KpfCCEH3LLq8wz6V3GRSD2IgGjQ9SNOgL+HOU2bCqaX+QSLq4+If+UIQDMtlm+zxg9BmKPAhB3+komDHAQUEaM3q0oKP0MLLRZ2qAwgJ/pGxdFRMd1zASjmYzRDADjoHyY5AEcBrATjD5ztiXukHKvp9YMKgNcjdluTmFu/XgeyAKjHOr5nC18798m4Lt3mJ0A2uF7n+0cgBMgVk0cX2PkuYO2Qpv2r96ECRMmTJgwYcKECRMmjEj+B3zlwJQA96ZIAAAAAElFTkSuQmCC" />
                                            
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
                                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAABmJLR0QA/wD/AP+gvaeTAAANQUlEQVR4nO2de3BU1R3Hv+fuK+83IUgDKHkpiIEKY4XyEB9EsmQ3FEQtCj4qLUrBqtR22jLt1ClWx0rLyKDloVMVwmSTbBJFKwQBW8vwtEBCQsAkSICQZJMNkH3cX//YrCaQ7H3vBrqffzKZnPM7J7/HOb9z7rnnAmHChAkTJkyYMGHChPl/g4W6A4GwFltTXRw/nuO5bJ5RNgOyGZBKQDSARPh+AkAXgDYGdBFwjoATAKo5oMbjNhz8aH7RhZD9EwIMKgOY7eYouPX3Ecffw4jNIGAslPeRQOwrMNpJHL8jqtv0adH8ostq9FcNQm6AVatWcfvHHb4bjBYSsABAnMZNdgAoBaOiSJexsmh+kVfj9gISMgNM37goIjax7Qki9gKAm0PUjXoCXuNN3Rs+evCj7lB0IOgGMNvNUfByPwOx5wkYFuz2+4MBZ4nYa8zgWWc32y8Fue3gkV9SYCZiaxgwKpjtSqAJjFaUW0q3BavBoBjAYrOM8gB/BZAfjPYUQ6yMGTzP2c32Bq2b0twAZpvFQsAG+NLG64kOBjxtt5Zs1bIRzQyQV5ln0rmMr4LYMq3aCAYMWO8xdS/TapLWxADWYmuym+PLQewuLeQHG2K01+Myztk+v6hVbdmqG2BO6ZybiOc+JuB2tWWHmOM6Rg+UWkob1RSqqgHM2+bmkM77CYB0NeUOIho4jr+/rKCsRi2Bqhkg354/HB79XgAj1ZI5KCF2hic2uXJu8ddqiOPUEGIttibDo/8UN7ryAYDRcMbxlQ9snZekhjjFBsirzDO5Ob4cwK0q9Oe6gAG3GQxue15lnkmpLMUG0LmMr98o2Y5E7ta5jK8qFaJoDsgvKfgRiBUp7YQc4g08lox2wsQR/lYXi1aXKqOpZIjY3IpCW7Hc+rINYLFZRnmBQwTEy5Uhlykp3Vgy2ol4Aw8AcHoYNp+OwcfNEcHuCgC08zyXK3dSlu02HmBNsJUfb+CxMqcDK3M6vlU+AMToCUszOvHb2xxIMvIBJGhCAuP4N+VWlhUBPfs7NrmNyuFqrx+IkEUDoznlllK75GpSK5jt5ijy6I4hSCmnf6yfkiJtK2ZfqzHYc8OpSLdhjNTHnZJ7R279UgRJ+VNSurF2Qptk5QPAxCQX1k5oxay0Kxr0rF9uvmRwPyO1kqQIyKvMM+m6TfUAbpLakBTkev1ABCsaGHC2sz3hlqrFm0RbXVKP9C7jk9BY+Uq8fiCCFQ0EDItNbHtcSh3RBli1ahXX8wBdEwbKcNQiaJkSsRdB4kcWndiC0SvipjNiy+X1KjBTUrrxuzEdyIr1aCG+D8Mjvbhv6BV0eTjUOfVaNJGUcSJrR+2HNaLWBaJ7wLy6hWAkv1v9IGWsJ2KobRiKPYeycKRuBC46onGhzXeEKDWxA0nxXbgjswGTc2uQNeJcQFn+aJiU1K3J3MB5dQsBfC6mrKhQmbd1XuQVg/usmgsvsXk9EUPV/hxsKJ2GxnPiNiDTh7biSUsVpk2oARNwGo3WDR2RbkOamJRU1BA0+uHs2cSwSHm/fF6/PKsTj4y4hAhdYOV8cyERK9c8hG2fTUJHV6ToNjq6IlG1/1bsO3YLxud8jdiogSdfIwdMSnIhM8aDrxxGXPaq8ojE5Gb4V+3W47VCBUXFHnH8Pcr7JC3D+aouHUv/9BiOn5KfdB2rH44lf1yMA9XCx5DUzpQ4jp8hqpyYQoyYKGEDITXDOVgzEitefwRtndGCZYXo6IrEyjUP4VDNCMGyamZKBMwUU04w3qzF1lQ3o2YxZftD7Fjv52xLPH76ymK0O6PkNDcgcdGX8dbLmzE8VdzBBhXmBp7pvUPtZntLoEKCEeDi+PGQoXw5eT0Rw+/ftqqufMAXCa9sNINI3L+iQjRwvJfLFSokmIZyPJdNEtNPqV7v59BpK+6ZPhPmBw2IivTlBzxP6HR60NnlRqfTjfMtl3Hm7CWcae5C1yVp64ajJ4dj98FsTJ1QLbqOf26QEw2M57IB/DNQGUED9LyZIgolezhEDCk3Lcb4ieJPMJ5vuYKjNW04WtOG/1a34cTJDvAU2FneKZkmyQCA/HUDY5QtVEbQAAzIEtOYXK/309g6DSOypR0fTU2JQGrKMMyY7Dvl3trejc//3YxdXzTj8LFW8Py1xmhoTkZtYxoy05sl91FqNBCg3AAA0gL9Ua2dy5auGRDOUwKTlGCCZdZIWGaNxMW2btgqT6Psk0Z0dLr6lNt7KFOWAQCJ0UAsoO4AcQaIHegPSr2+Nw1nI1FXdgrtDhe6XV643D6ZRgMHk1GHmBg9hiRHIm1IJFKHRGBoSuCFWXKiCU89mo0fz83Ax1VN+LCkHs3nfQvTI7XKD+6JigZGA+rOjywDqL1fDwDb7KfQdL5ddPn4OCNyMuKRk5GAsTkJyB2bDIP+Wm+MiNDBMmskHpyZjveLT+IDWz0utAvqRRQiokEVA8T0/kVNr+/NRYe0RZejw4UvD1zAlwd8b6DGRBsweWIqpv4gDRNzh8Bo6KsMo4HDoocyMWvG97D+vSOq9RsIGA2qGKAPLp7Bq+6mKACAKdyCcXa5sb3qDLZXnUF8nBHWvJEonD0KcTGGPuXSUiPx0rN3Ap3K2rsaLzE4PdL/CTH5lLP3L/9pNWLpgSTVTx0kxzuFC4nE0eHCpi21WPDMTry16ThaWvvu7zid6r4mvKfFhKUHErGn5ZqTioJmFmOAa4Q4PQxr62Lxh2Pxqu2lpySo7JIALl32YEvZKSx8dhc+LKmHpyd02x0OVeQ73BxWV8dhdXUcHO5+9aCNAfyoGQ3jMlV976EPl694se7daix5aS9ONzrR1SltIdYfAbz+O4ipYoCACbNa0TA5V7V3Hgak7lQHnnlxLxL0W2TLEOH13+HbxAyIoMZ6Lr4QRGk0ZKafR3raRVl1pTA06TxGDj0mq64or+8FAwS9StAAHDHRrqkkGhgjPFmwS1IdOTxt3Sm5jiSv7wWJ0J2gNJ7jJY8NcqNh2oQa3HbLGanNiWbs6CZMyRV8StgHqV7fGxKhO2EDuIwHAEjO/OVEA2OE3z5dioQY9a9riIu5jF89YRd8SO9Hrtf3gjfy3EGhQqJWDmab5YiS105j9ITHRzlFP289VDMCL/zlYXi8oo8tBcSg9+K15R/gjixxNw/saTFh3ckYuYr3c7DcWjJBqJC4h/LADiU9kRoNudkNeOMX7yMxtktJswB8nr962RZRylfB67+FidSZ2FMRigzgR8rccHtGI9a+vBljRsufE8aObsL6X2/EhJzTgmWVjPX9QYxE6SxkB7MmJbmwNKNT8HkrEcPug9l4p2QaGpqTRckekXYRT1mq8MPxJwTHfIebw7qTMaopHgAY4IhwG4aJOZglevco32b5O4AnFPXsKqTODbWNadh7KBNHatPR0h6LC22+zcYhiZ1ISejEuMxGTMk9gYz0wEcT/ag01l8Lo7fLLaU/EVNUym7oe1DZAP65YV+rSVQ0ZKY3y36S1RstvL435NOVKESb/s7DuZ8DqJfVIwG02mHtD7XH+n6oqygo3SO2sOg8r6qqijIX5LgZMFtevwLj4hn2tZpw0mnA7fFuRAqcG5WKw83hzdpYfNAQjW5eu3uqCHi59tbq/WLLSxr8eFP3BgDfSO6VBLSIhiB4vZ8m3tS9WUoFSSudun/UeTMX5DAG3C+tX9JQKxqC5fV+GLHfVMyxfyGljuTpn9N73yJAOLFWASXREESvBwAw4GSnI369jHrSMdsseQRUyqkrF7HrBq0znIEgnsuvmFtcIbWe7LjML7aWgtEcufXlILRu0CyvF4KYrbzQViinquy31JjB8xx5dFMBJMiVIZWB1g2h8voe2nQc/3O5lRXNTOZiaz4xKlMqRw7+w2EAQuP1PgiM5pZbSmXfm6FYcfklBW9e73eDyoWIvVFRaHteiQzFbuM1ul4CICn1ukHYHeXR/1KpEFWGjnu3zouPMLh3AbhDDXmDHmJH3R79VDUucg1fWymdJqb3TlbrYm/VZq5yc/kZjuMfAKD5jeMhpIEnNlPNW9VVTR3KCspq3Dx3F4ipe/x4EEDAMR2jKZWFNlHnpMSieu62fW7xWbdHPwM31sS8Gwb3FLXvjQY0MAAAbJ9f1OpMaJ/GiK2GjCMtgwgCozWRbsO9FfkVbVo0oP0HHIqtBcRoI67DDzgQsacqCm2a3ouq+fLRXmgr5XluPIiVad2WahCz6RiN1Vr5QJC3EHq2LtYgdJ+tEqKeeG6ZnF1NuQR1A8VeaCuPdBvGELACxLQ7BCqdJgDLne0JY4KpfCCEH3LLq8wz6V3GRSD2IgGjQ9SNOgL+HOU2bCqaX+QSLq4+If+UIQDMtlm+zxg9BmKPAhB3+komDHAQUEaM3q0oKP0MLLRZ2qAwgJ/pGxdFRMd1zASjmYzRDADjoHyY5AEcBrATjD5ztiXukHKvp9YMKgNcjdluTmFu/XgeyAKjHOr5nC18798m4Lt3mJ0A2uF7n+0cgBMgVk0cX2PkuYO2Qpv2r96ECRMmTJgwYcKECRMmjEj+B3zlwJQA96ZIAAAAAElFTkSuQmCC">
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

                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton
                                        class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 lg:rounded-md lg:p-2 lg:hover:bg-gray-50">
                                        <img class="h-8 w-8 rounded-full" :src="$page.props.user.profile_photo_url"
                                            :alt="$page.props.user.name" />
                                        <span class="ml-3 hidden text-sm font-medium text-gray-700 lg:block">{{
                                        $page.props.user.name
                                        }}</span>
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
        