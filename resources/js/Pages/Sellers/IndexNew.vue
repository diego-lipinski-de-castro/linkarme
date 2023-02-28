<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
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

import {
    PencilSquareIcon,
    TrashIcon
} from '@heroicons/vue/24/outline'
import AppSuspense from '../../Layouts/AppSuspense.vue';

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

const destroy = (seller) => {
    Inertia.delete(route('sellers.destroy', seller), {
        preserveScroll: true,
        preserveState: true,
    })
}

</script>
        
<template>

    <AppSuspense>
        <AppLayoutNew :title="$t('Sellers')">
            <div class="rounded-md bg-white px-5 py-6 shadow sm:px-6">
                <div class="flex flex-col min-h-48">

                    <div class="flex justify-between items-center space-x-2">
                        <h2 class="text-xl font-bold leading-tight">{{ $t('Sellers') }}</h2>

                        <Link :href="route('sellers.create')" class="flex max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 transition-colors">
                            <span class="px-1 text-sm font-medium text-white">{{ $t('Add seller') }}</span>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div class="sm:hidden border border-gray-200 rounded-lg overflow-hidden">
                    <ul role="list" class="divide-y divide-gray-200">

                        <li v-for="(seller, index) in sellers.data" :key="index">
                            <Link :href="route('sellers.edit', seller.id)"
                                class="block bg-white px-4 py-4 hover:bg-gray-50">
                                <span class="flex items-center space-x-4">
                                    <span class="flex flex-1 space-x-2 truncate">
                                        <span class="text-sm text-gray-500">
                                            {{ seller.name }}
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
                                            {{ $t('Email') }}
                                        </th>

                                        <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(seller, index) in sellers.data" :key="index" class="bg-white">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            <Link :href="route('sellers.edit', seller.id)"
                                                class="text-gray-500 hover:text-gray-900">
                                                {{ seller.name }}
                                            </Link>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ seller.email ?? '-' }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            <div class="flex space-x-2">
                                                <Link :href="route('sellers.edit', seller.id)"
                                                    class="text-blue-500 hover:text-blue-700">
                                                <PencilSquareIcon class="h-5 w-5" />
                                                </Link>

                                                <button @click="destroy(seller.id)"
                                                    class="text-red-500 hover:text-red-700">
                                                    <TrashIcon class="h-5 w-5" />
                                                </button>
                                            </div>
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
        </AppLayoutNew>
    </AppSuspense>
</template>
        