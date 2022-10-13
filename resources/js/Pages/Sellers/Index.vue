<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TableSortButton from '@/Components/TableSortButton.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, ref, watch } from 'vue'
import {
    CheckCircleIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
} from '@heroicons/vue/20/solid'

import { debounce } from 'debounce';

const props = defineProps({
    title: String,
    sellers: Object,
});

const links = computed(() => {
    const _links = props.sellers.links
    _links.shift()
    _links.pop()
    return _links
})

</script>
        
<template>

    <AppLayout :title="$t('Sellers')">
        <template #uppermenu>
        </template>

        <template #header>
            <div
                class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t('Sellers') }}
                </h2>

                <div class="flex space-x-3">
                    <Link :href="route('sellers.create')"
                        class="flex max-w-xs items-center rounded-md bg-indigo-500 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 p-2 hover:bg-indigo-700">
                        <span class="px-1 text-sm font-medium text-white">{{ $t('Add seller') }}</span>
                    </Link>
                </div>
            </div>
        </template>

        <template #submenu>
        </template>

        <template #submenu-mobile>
        </template>

        <div>
            <div class="sm:hidden border border-gray-200 rounded-lg overflow-hidden">
                <ul role="list" class="divide-y divide-gray-200">

                    <li v-for="(seller, index) in sellers.data" :key="index">
                        <a :href="route('sellers.edit', seller.id)" class="block bg-white px-4 py-4 hover:bg-gray-50">
                            <span class="flex items-center space-x-4">
                                <span class="flex flex-1 space-x-2 truncate">
                                    <span class="text-sm text-gray-500">
                                        {{ seller.name }}
                                    </span>
                                </span>
                                <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                            </span>
                        </a>
                    </li>

                </ul>

                <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3"
                    aria-label="Pagination">
                    <div class="flex flex-1 justify-between">
                        <Link :href="sellers.prev_page_url"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                        {{ $t('Previous') }}</Link>
                        <Link :href="sellers.next_page_url"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                        {{ $t('Next') }}</Link>
                    </div>
                </nav>
            </div>

            <div class="hidden sm:block">
                <div class="flex flex-col">
                    <div
                        class="min-w-full overflow-hidden overflow-x-auto align-middle border border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">
                                        {{ $t('Name') }}
                                    </th>

                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">
                                        {{ $t('E-mail') }}
                                    </th>

                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">
                                        
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="(seller, index) in sellers.data" :key="index" class="bg-white">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <a :href="route('sellers.edit', seller.id)"
                                            class="text-gray-500 hover:text-gray-900">
                                            {{ seller.name }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ seller.email ?? '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <nav class="mt-6 flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                        <div class="-mt-px flex w-0 flex-1">
                            <Link :href="sellers.prev_page_url"
                                class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                            <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                            {{ $t('Previous') }}
                            </Link>
                        </div>
                        <div class="hidden md:-mt-px md:flex">
                            <Link v-for="(link, index) in links" :key="index" :href="link.url"
                                :class="[link.active ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'inline-flex items-center border-t-2  px-4 pt-4 text-sm font-medium ']">
                            {{ link.label }}
                            </Link>
                        </div>
                        <div class="-mt-px flex w-0 flex-1 justify-end">
                            <Link :href="sellers.next_page_url"
                                class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                            {{ $t('Next') }}
                            <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                            </Link>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
        