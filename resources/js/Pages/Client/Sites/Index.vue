<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { computed, ref } from 'vue'
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
import { ArrowLongLeftIcon, ArrowLongRightIcon } from '@heroicons/vue/20/solid'

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

const props = defineProps({
    title: String,
    sites: Object,
    favorites: Array,
    countries: Array,
    languages: Array,
    categories: Array,
});

const links = computed(() => {
    const _links = props.sites.links
    _links.shift()
    _links.pop()
    return _links
})

</script>
    
<template>
    <ClientLayout title="Sites">
        <template #header>
            <div class="px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Sites
                </h2>
            </div>
        </template>

        <template #submenu>
            <div class="space-y-1 px-2">
                <a v-for="item in [1,2,3,4,5]" :key="item" href="#"
                    class="group flex items-center rounded-md px-2 py-2 text-sm font-medium leading-6 text-indigo-100 hover:bg-indigo-600 hover:text-white">
                    1
                </a>
            </div>
        </template>

        <template #submenu-mobile>
            <div class="space-y-1 px-2">
                <Link v-for="item in [1,2,3,4,5]" :key="item" href="#"
                    class="group flex items-center rounded-md px-2 py-2 text-base font-medium text-indigo-100 hover:bg-indigo-600 hover:text-white">
                1
                </Link>
            </div>
        </template>

        <div>
            <div class="shadow sm:hidden">
                <ul role="list" class="mt-2 divide-y divide-gray-200 overflow-hidden shadow sm:hidden">

                    <li v-for="(site, index) in sites.data" :key="index">
                        <Link :href="route('client.sites.edit', site.id)"
                            class="block bg-white px-4 py-4 hover:bg-gray-50">
                        <span class="flex items-center space-x-4">
                            <span class="flex flex-1 space-x-2 truncate">
                                <span class="text-sm text-gray-500">
                                    {{ site.url }}
                                </span>
                            </span>
                            <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                        </span>
                        </Link>
                    </li>

                </ul>

                <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3"
                    aria-label="Pagination">
                    <div class="flex flex-1 justify-between">
                        <a href="#"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">Previous</a>
                        <a href="#"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">Next</a>
                    </div>
                </nav>
            </div>

            <!-- Activity table (small breakpoint and up) -->
            <div class="hidden sm:block">
                <div class="mt-2 flex flex-col">
                    <div class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">Domínio</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">País</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">Linguagem</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">DA</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">DR</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">TF</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">Categoria</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">SSL</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">Cassino</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">Publi</th>
                                    <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">Cripto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="(site, index) in sites.data" :key="index" class="bg-white">
                                    <td class="w-full max-w-0 whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                        <div class="flex">
                                            <Link :href="route('client.sites.edit', site.id)"
                                                class="group inline-flex space-x-2 truncate text-sm">
                                            <p class="truncate text-gray-500 group-hover:text-gray-900">{{
                                            site.url }}</p>
                                            </Link>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.country?.name ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.language?.name ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.da ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.dr ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.tf ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.category?.name ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.ssl ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.gambling ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.sponsor ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.cripto ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <nav class="mt-6 flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                        <div class="-mt-px flex w-0 flex-1">
                            <Link :href="sites.prev_page_url"
                                class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                            <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                            Previous
                            </Link>
                        </div>
                        <div class="hidden md:-mt-px md:flex">
                            <Link v-for="(link, index) in links" :key="index" :href="link.url"
                                :class="[link.active ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'inline-flex items-center border-t-2  px-4 pt-4 text-sm font-medium ']">
                            {{ link.label }}
                            </Link>
                        </div>
                        <div class="-mt-px flex w-0 flex-1 justify-end">
                            <Link :href="sites.next_page_url"
                                class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                            Next
                            <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                            </Link>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </ClientLayout>
</template>
    