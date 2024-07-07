<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import {
    ChevronRightIcon,
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/20/solid'

import {
    PencilSquareIcon,
    TrashIcon,
    ArrowRightOnRectangleIcon,
    Cog6ToothIcon,
    HeartIcon,
    FlagIcon,
    ShoppingCartIcon,
    EyeIcon,
    LockClosedIcon,
} from '@heroicons/vue/24/outline'
import { computed, onMounted, reactive, watch } from 'vue';

import VueMultiselect from 'vue-multiselect'
import { watchDebounced } from '@vueuse/core';

const props = defineProps({
    title: String,
    clients: Object,
    filters: Object,
});

const links = computed(() => {
    const _links = props.clients.links
    _links.shift()
    _links.pop()
    return _links
})

const filters = reactive({
    search: props.filters.search,
    full: props.filters.full,
})

watchDebounced(() => ({ ...filters }), (n, o) => {
    get()
}, {
    debounce: 400,
    deep: true,
})

const get = () => {
    Inertia.get(route('clients.index'), {
        filter: {
            search: filters.search,
            full: filters.full?.value,
        },
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const destroy = (client) => {
    Inertia.delete(route('clients.destroy', client), {
        preserveScroll: true,
        preserveState: true,
    })
}

onMounted(() => {
    setTimeout(() => {
        tippy('[data-tippy-content]');
    }, 100)
})

</script>

<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Clients')">
            <div class="rounded-md bg-white px-5 py-6 shadow sm:px-6">
                <div class="flex flex-col">

                    <div class="flex justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-10">
                                <MagnifyingGlassIcon class="h-8 w-8" />
                            </div>

                            <div>
                                <h2 class="text-xl font-bold leading-tight">{{ $t('Search clients') }}</h2>
                                <span class="block text-xs text-gray-400">{{ $t('Choose filters to start') }}</span>
                            </div>
                        </div>

                        <Link :href="route('clients.create')"
                            class="flex md:hidden max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 transition-colors">
                        <span class="px-1 text-sm font-medium text-white">{{ $t('Add client') }}</span>
                        </Link>
                    </div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-3 md:gap-y-0">

                        <div class="col-span-full md:col-span-2">
                            <label for="search" class="text-sm font-medium">{{ $t('Find by name or email:') }}</label>
                            <div class="mt-1 relative text-gray-400 focus-within:text-gray-600">
                                <div class="pointer-events-none absolute inset-y-0 left-2 flex items-center"
                                    aria-hidden="true">
                                    <MagnifyingGlassIcon class="ml-2 h-5 w-5" aria-hidden="true" />
                                </div>
                                <input v-model="filters.search" id="search" name="search"
                                    class="ml-2 w-full block border border-gray-300 rounded-md py-2 pl-8 pr-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 sm:text-sm"
                                    :placeholder="$t('Search')" type="search" />
                            </div>
                        </div>


                        <div class="col-span-1">
                            <label class="text-sm font-medium">{{ $t('Filter by type') }}</label>

                            <VueMultiselect class="mt-1 ml-2" placeholder="Select..." v-model="filters.full"
                                track-by="value" label="name" :options="[
                                    { name: 'Todos', value: '' },
                                    { name: 'Full', value: true },
                                    { name: 'Limitado', value: false },
                                ]" :multiple="false" :searchable="false" :close-on-select="true" selectLabel=""
                                deselectLabel="" :showLabels="false">

                                <template #placeholder>
                                    <span class="text-gray-500">{{ $t('Select...') }}</span>
                                </template>

                            </VueMultiselect>
                        </div>

                    </div>

                </div>
            </div>

            <div class="mt-5">

                <div class="flex flex-col">

                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Showing results:')
                                }}&nbsp;{{
                                clients.total }}&nbsp;{{ $t('clients found') }}</h2>
                        </div>

                        <div class="hidden md:flex space-x-3">
                            <Link :href="route('clients.create')"
                                class="flex max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700">
                            <span class="px-1 text-sm font-medium text-white">{{ $t('Add client') }}</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:hidden border border-gray-200 rounded-lg overflow-hidden">
                    <ul role="list" class="divide-y divide-gray-200">

                        <li v-for="(client, index) in clients.data" :key="index">
                            <a :href="route('clients.edit', client.id)"
                                class="block bg-white px-4 py-4 hover:bg-gray-50">
                                <span class="flex items-center space-x-4">
                                    <span class="flex flex-1 items-center space-x-1.5 truncate">
                                        <span class="text-sm text-gray-500">{{ client.name }}</span>

                                        <svg v-if="client.full" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke="#000" fill="#FDE047" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                        </svg>
                                    </span>
                                    <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                                </span>
                            </a>
                        </li>

                    </ul>

                    <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3"
                        aria-label="Pagination">
                        <div class="flex flex-1 justify-between">
                            <Link :href="clients.prev_page_url"
                                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                            {{ $t('Previous') }}</Link>
                            <Link :href="clients.next_page_url"
                                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                            {{ $t('Next') }}</Link>
                        </div>
                    </nav>
                </div>

                <div class="mt-5 hidden sm:block">
                    <div class="flex flex-col">

                        <ul role="list" class="divide-y divide-gray-200 bg-white min-w-full overflow-hidden overflow-x-auto align-middle border border-gray-200 sm:rounded-lg">
                            <li v-for="(client, index) in clients.data" :key="index">

                                <div class="px-6 py-5 flex justify-between items-center">
                                    <div class="flex items-center w-full">
                                        <div class="gap-x-4 pr-6 sm:w-3/5 md:w-2/5">
                                            <div class="min-w-0 flex-auto">
                                                <div class="flex space-x-1.5 items-center">
                                                    <a :href="route('clients.edit', client.id)" class="text-sm leading-6 text-blue-500 hover:text-blue-700">
                                                        {{ client.name }}
                                                    </a>

                                                    <svg v-if="client.full" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke="#000" fill="#FDE047" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                                    </svg>

                                                </div>
                                                <a :href="`mailto:${client.email}`" class="flex text-xs leading-5 text-gray-500 relative truncate cursor-pointer hover:underline">{{ client.email }}</a>
                                            </div>
                                        </div>

                                        <div class="sm:w-2/5 md:w-1/5">
                                            <div class="hidden sm:block">
                                                <p class="text-sm leading-6 text-gray-900">{{ $t('Consultant') }}: {{ client.consultant?.name ?? $t('none') }}</p>
                                                <p class="text-xs leading-5 text-gray-500">
                                                    {{ $t('Last seen') }}: {{ client.latest_login ? new Date(client.latest_login.created_at).toLocaleString() : $t('never')}}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="hidden md:flex items-center justify-between gap-x-4 md:w-2/5">
                                            <div class="hidden sm:grid grid-cols-3 grid-rows-2 gap-x-3">
                                                <span :data-tippy-content="`${client.logins_count} ${$t('Logins')}`" class="flex space-x-1 justify-end items-center w-16">
                                                    <span class="text-sm text-gray-500">{{ client.logins_count }}</span>
                                                    <LockClosedIcon class="h-4 w-4 text-gray-500" />
                                                </span>

                                                <span :data-tippy-content="`${client.orders_count} ${$t('Orders')}`" class="flex space-x-1 justify-end items-center w-16">
                                                    <span class="text-sm text-gray-500">{{ client.orders_count }}</span>
                                                    <ShoppingCartIcon class="h-4 w-4 text-gray-500" />
                                                </span>

                                                <span :data-tippy-content="`${client.views_count} ${$t('Views')}`" class="flex space-x-1 justify-end items-center w-16">
                                                    <span class="text-sm text-gray-500">{{ client.views_count }}</span>
                                                    <EyeIcon class="h-4 w-4 text-gray-500" />
                                                </span>

                                                <span :data-tippy-content="`${client.projects_count} ${$t('Projects')}`" class="flex space-x-1 justify-end items-center w-16">
                                                    <span class="text-sm text-gray-500">{{ client.projects_count }}</span>
                                                    <Cog6ToothIcon class="h-4 w-4 text-gray-500" />
                                                </span>
                                                
                                                <span :data-tippy-content="`${client.favorites_count} ${$t('Favorites')}`" class="flex space-x-1 justify-end items-center w-16">
                                                    <span class="text-sm text-gray-500">{{ client.favorites_count }}</span>
                                                    <HeartIcon class="h-4 w-4 text-gray-500" />
                                                </span>

                                                <span :data-tippy-content="`${client.interests_count} ${$t('Interests')}`" class="flex space-x-1 justify-end items-center w-16">
                                                    <span class="text-sm text-gray-500">{{ client.interests_count }}</span>
                                                    <FlagIcon class="h-4 w-4 text-gray-500" />
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex space-x-2">
                                            <Link :href="route('clients.edit', client.id)"
                                                class="text-blue-500 hover:text-blue-700">
                                            <PencilSquareIcon class="h-5 w-5" />
                                            </Link>

                                            <button @click="destroy(client.id)"
                                                class="text-red-500 hover:text-red-700">
                                                <TrashIcon class="h-5 w-5" />
                                            </button>

                                            <a :href="route('impersonate', [client.id, 'client', 'client.dashboard'])"
                                                class="text-purple-500 hover:text-purple-700">
                                                <ArrowRightOnRectangleIcon class="h-5 w-5" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <nav class="mt-6 flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                            <div class="-mt-px flex w-0 flex-1">
                                <Link :href="clients.prev_page_url"
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
                                <Link :href="clients.next_page_url"
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