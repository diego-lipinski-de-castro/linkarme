<script setup>
import ClientLayoutNew from '@/Layouts/ClientLayoutNew.vue';
import TableSortButton from '@/Components/TableSortButton.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, onMounted, reactive, ref, watch } from 'vue'
import unionBy from 'lodash/unionBy'

import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    ChevronUpIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/20/solid'

import {
    FireIcon,
    GlobeAltIcon,
    ArrowTopRightOnSquareIcon,
} from '@heroicons/vue/24/outline'

import { useTranslation } from "i18next-vue";
import { useCoinStore } from '@/stores/coin'
import AppSuspense from '../../../Layouts/AppSuspense.vue';
import { watchDebounced } from '@vueuse/core';

const coinStore = useCoinStore()
const { t } = useTranslation();

const props = defineProps({
    title: String,
    coins: Object,
    filters: Object,
    favorites: Array,
    interests: Array,
    languages: Array,
    countries: Array,
    sites: Array,
    orders: Object,
});

const links = computed(() => {
    const _links = props.orders.links
    _links.shift()
    _links.pop()
    return _links
})

const _defaultColumns = [
    { key: 'url', label: t('Site'), visible: true },
    { key: 'link', label: t('Link'), visible: true },
    { key: 'created_at', label: t('Date'), visible: false },
];

const _columns =
    localStorage.getItem('client.orders.index.columns') ?
        unionBy(JSON.parse(localStorage.getItem('client.orders.index.columns')), _defaultColumns, 'key')
        : _defaultColumns

const columns = ref(_columns)

watch(columns, (n, o) => {
    localStorage.setItem('client.orders.index.columns', JSON.stringify(columns.value))
}, {
    deep: true,
})

const sort = ref(props.filters.sort)

const filters = reactive({

})

watch(sort, (n, o) => get());

watchDebounced(() => ({ ...filters }), (n, o) => {
    get()
}, {
    deep: true,
    debounce: 400
})

const get = async () => {
    Inertia.get(route('client.orders.index'), {
        sort: sort.value,
        filter: {

        },
    }, {
        preserveState: true,
    })
}

const coinFormat = computed(() => props.coins[coinStore.coin])

onMounted(() => {
    setTimeout(() => {
        tippy('[data-tippy-content]');
    }, 100)
})
</script>

<template>
    <AppSuspense>
        <ClientLayoutNew :title="$t('Orders')">
            <div>
                <div class="grid grid-cols-1 md:grid-cols-3 space-x-6 min-h-48">

                    <div class="col-span-1 rounded-md bg-white py-6 shadow">

                        <div>
                            <div class="flex items-center space-x-2 px-5">
                                <div class="w-10">
                                    <FireIcon class="h-8 w-8"/>
                                </div>

                                <div>
                                    <span class="block font-bold">{{ $t('Your top 5 requests') }}</span>
                                    <span class="block text-xs text-gray-400">{{ $t('These are our sites that you used the most') }}</span>
                                </div>
                            </div>

                            <div class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle border-gray-300 border-opacity-50">
                                <table class="w-full">
                                    <thead v-if="sites.length > 0">
                                        <tr>
                                            <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col">{{ $t('Domain') }}
                                            </th>
                                            <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col">{{ $t('Requests') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-300 divide-opacity-50">
                                        <tr v-if="sites.length == 0">
                                            <td colspan="3" class=" px-6 py-4 text-sm text-gray-500 italic text-center">
                                                {{ $t('You have not ordered anything yet') }}
                                            </td>
                                        </tr>

                                        <tr v-else v-for="(site, index) in sites" :key="index">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                <Link :href="route('client.sites.show', site.id)" class="flex items-center space-x-4 text-gray-500 hover:text-gray-900">
                                                    <span>{{ site.url }}</span>
                                                </Link>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                {{ site.orders_count ?? '-' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div class="col-span-1 md:col-span-2 flex flex-col rounded-md bg-white px-5 py-6 shadow sm:px-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-10">
                                <MagnifyingGlassIcon class="h-8 w-8"/>
                            </div>

                            <div>
                                <h2 class="text-xl font-bold leading-tight">{{ $t('Search orders') }}</h2>
                                <span class="block text-xs text-gray-400">{{ $t('Choose filters to start') }}</span>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                            
                            <div class="col-span-1 flex flex-col">
                                <span class="flex items-center space-x-2 text-sm font-medium">
                                    <span class="block h-2 w-2 bg-green-500 rounded-full"></span>
                                    <span>{{ $t('Price range') }}</span>
                                </span>

                                <div class="mt-4 flex space-x-4">
                                    <div class="flex items-center">
                                        <label for="from_sale" class="text-xs text-gray-500 self-start text-right">{{ $t('From') }}</label>
                                        <input v-money3="coinFormat" id="from_sale" name="from_sale" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>

                                    <div class="flex items-center">
                                        <label for="to_sale" class="text-xs text-gray-500 self-start text-right">{{ $t('To') }}</label>
                                        <input v-money3="coinFormat" id="to_sale" name="to_sale" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>
                                </div>
                            </div>


                            <div class="col-span-1 flex flex-col">
                                <span class="flex items-center space-x-2 text-sm font-medium">
                                    <GlobeAltIcon class="h-5 w-5"/>
                                    <span>{{ $t('Language') }}</span>
                                </span>

                                <div class="mt-4 flex space-x-4">
                                    <div class="flex flex-col space-y-2">
                                        <div v-for="(language, index) in languages" :key="index" class="relative flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input :value="language.id" :id="`language-${language.id}`" :name="`language-${language.id}`" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label :for="`language-${language.id}`" class="font-medium text-gray-700">{{ language.name }}</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-24">
                            <div class="w-full">
                                <label for="search" class="text-sm font-medium">{{ $t('By name:') }}</label>
                                <div class="mt-1 relative  text-gray-400 focus-within:text-gray-600">
                                    <div class="pointer-events-none absolute inset-y-0 left-2 flex items-center" aria-hidden="true">
                                        <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
                                    </div>
                                    <input  id="search" name="search"
                                        class="block border border-gray-300 rounded-md py-2 pl-8 pr-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 sm:text-sm"
                                        :placeholder="$t('Search')" type="search" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-5 hidden sm:block">
                    <div class="flex flex-col">

                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Showing results for your orders:') }}&nbsp;{{ '379' }}&nbsp;{{ $t('of') }}&nbsp;{{' 512' }}&nbsp;{{ $t('total') }}</h2>
                        </div>

                        <hr class="my-5">

                        <div class="flex flex-col">
                            <span class="font-bold text-sm whitespace-nowrap">{{ $t('Column filter:') }}</span>
                            <div class="mt-2 flex flex-wrap">
                                <div v-for="(column, index) in columns" :key="index" class="px-4 py-2 relative flex">
                                    <div class="flex items-center h-5">
                                        <input v-model="column.visible" :value="column.key" :id="column.key"
                                            :name="column.key" type="checkbox"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" />
                                    </div>

                                    <div class="ml-2 text-sm">
                                        <label :for="column.key" class="font-medium text-gray-700 whitespace-nowrap">{{
                                            column.label
                                        }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle border border-gray-200 sm:rounded-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th v-show="columns[0].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            {{ $t('Site') }}
                                        </th>

                                        <th v-show="columns[1].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Link') }}
                                        </th>

                                        <th v-show="columns[2].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Date') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(order, index) in orders.data" :key="index" class="bg-white">
                                        <td v-show="columns[0].visible" class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ order.site?.url }}
                                        </td>

                                        <td v-show="columns[1].visible" class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            <a :href="order.url" target="_blank" :title="order.url" class="text-blue-500 hover:text-blue-700 flex space-x-1">
                                                <span>{{ $t('Open') }}</span>
                                                <ArrowTopRightOnSquareIcon class="size-4"/>
                                            </a>
                                        </td>
                                    
                                        <td v-show="columns[2].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ new Date(order.created_at).toLocaleString() }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <nav class="mt-6 flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                            <div class="-mt-px flex w-0 flex-1">
                                <Link :href="orders.prev_page_url"
                                    class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                                {{ $t('Previous') }}
                                </Link>
                            </div>
                            <div class="hidden md:-mt-px md:flex">
                                <Link v-for="(link, index) in links" :key="index" :href="link.url"
                                    :class="[link.active ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'inline-flex items-center border-t-2  px-4 pt-4 text-sm font-medium ']">
                                {{ link.label }}
                                </Link>
                            </div>
                            <div class="-mt-px flex w-0 flex-1 justify-end">
                                <Link :href="orders.next_page_url"
                                    class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                {{ $t('Next') }}
                                <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                                </Link>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </ClientLayoutNew>
    </AppSuspense>
</template>
