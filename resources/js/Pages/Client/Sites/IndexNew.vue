<script setup>
import ClientLayoutNew from '@/Layouts/ClientLayoutNew.vue';
import TableSortButton from '@/Components/TableSortButton.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, onMounted, reactive, ref, watch } from 'vue'
import unionBy from 'lodash/unionBy'
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Switch, SwitchGroup, SwitchLabel,
} from '@headlessui/vue'

import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    ChevronUpIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/20/solid'

import {
    GlobeAltIcon
} from '@heroicons/vue/24/outline'

import { debounce } from 'debounce';

import { useTranslation } from "i18next-vue";
import { useCoinStore } from '@/stores/coin'
import AppSuspense from '../../../Layouts/AppSuspense.vue';

const coinStore = useCoinStore()
const { t } = useTranslation();

const props = defineProps({
    title: String,
    sites: Object,
    coins: Object,
    favorites: Array,
    countries: Array,
    languages: Array,
    categories: Array,
    filters: Object,
});

const links = computed(() => {
    const _links = props.sites.links
    _links.shift()
    _links.pop()
    return _links
})

const _defaultColumns = [
    { key: 'sale', label: t('Price'), visible: true },
    { key: 'url', label: t('Domain'), visible: true },
    { key: 'da', label: t('DA'), visible: true },
    { key: 'dr', label: t('DR'), visible: true },
    { key: 'gambling', label: t('Gambling'), visible: true },
    { key: 'sponsor', label: t('Sponsor'), visible: true },
    { key: 'cripto', label: t('Cripto'), visible: false },
    { key: 'ssl', label: t('SSL'), visible: false },
    { key: 'category', label: t('Category'), visible: false },
    { key: 'obs', label: t('Obs'), visible: true },
    { key: 'example', label: t('Example'), visible: false },
    { key: 'inserted_at', label: t('Upload date'), visible: false },
];

const _columns =
    localStorage.getItem('client.sites.index.columns') ?
        unionBy(JSON.parse(localStorage.getItem('client.sites.index.columns')), _defaultColumns, 'key')
        : _defaultColumns

const columns = ref(_columns)

watch(columns, (n, o) => {
    localStorage.setItem('client.sites.index.columns', JSON.stringify(columns.value))
}, {
    deep: true,
})

const sort = ref(props.filters.sort)

const filters = reactive({
    url: props.filters.filter.url,
    sale: { from: props.filters.filter.sale.from, to:  props.filters.filter.sale.to },
    da: { from: props.filters.filter.da.from, to: props.filters.filter.da.to },
    dr: { from: props.filters.filter.dr.from, to: props.filters.filter.dr.to },
    gambling: props.filters.filter.gambling,
    sponsor: props.filters.filter.sponsor,
    favorites: props.filters.filter.favorites,
    recommended: props.filters.filter.recommended,
    new: props.filters.filter.new,
    language_id: props.filters.filter.language_id,
})

watch(sort, (n, o) => get());

watch(() => ({ ...filters }), debounce((n, o) => {

    console.log(n);

    if (n.new !== o.new) {
        sort.value = n.new === true ? 'new' : 'url';
        return;
    }

    if (n.recommended !== o.recommended) {
        sort.value = n.recommended === true ? 'recommended' : 'url';
        return;
    }

    get()
}, 400), {
    deep: true,
})

const get = async () => {
    Inertia.get(route('client.sites.index'), {
        sort: sort.value,
        filter: {
            url: filters.url,
            sale: filters.sale,
            da: filters.da,
            dr: filters.dr,
            gambling: filters.gambling,
            sponsor: filters.sponsor,
            ...(filters.favorites && {
                favorites: filters.favorites,
            }),
            ...(filters.recommended && {
                recommended: filters.recommended,
            }),
            ...(filters.new && {
                new: filters.new,
            }),
            language_id: filters.language_id,
        },
    }, {
        preserveState: true,
    })
}

const toggleFavorite = async (site) => {
    Inertia.post(route('client.sites.favorite', site), null, {
        preserveScroll: true,
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
        <ClientLayoutNew title="Sites">
            <div>
                <div class="rounded-md bg-white px-5 py-6 shadow sm:px-6">
                    <div class="flex flex-col min-h-48">

                        <div class="flex items-center space-x-2">
                            <div class="w-10">
                                <MagnifyingGlassIcon class="h-8 w-8"/>
                            </div>

                            <div>
                                <h2 class="text-xl font-bold leading-tight">{{ $t('Search websites') }}</h2>
                                <span class="block text-xs text-gray-400">{{ $t('Choose filters to start') }}</span>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-7 gap-x-6">
                            <div class="col-span-2 flex flex-col">
                                <span class="flex items-center space-x-2 text-sm font-medium">
                                    <span class="block h-2 w-2 bg-green-500 rounded-full"></span>
                                    <span>{{ $t('Price range') }}</span>
                                </span>

                                <div class="mt-4 flex space-x-4">
                                    <div class="flex items-center">
                                        <label for="from_sale" class="text-xs text-gray-500 self-start text-right">{{ $t('From') }}</label>
                                        <input v-model="filters.sale.from" v-money3="coinFormat" id="from_sale" name="from_sale" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>

                                    <div class="flex items-center">
                                        <label for="to_sale" class="text-xs text-gray-500 self-start text-right">{{ $t('To') }}</label>
                                        <input v-model="filters.sale.to" v-money3="coinFormat" id="to_sale" name="to_sale" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-2 flex flex-col">
                                <span class="flex items-center space-x-2 text-sm font-medium">
                                    <span class="block h-2 w-2 bg-yellow-500 rounded-full"></span>
                                    <span>{{ $t('Domain authority range') }}</span>
                                </span>

                                <div class="mt-4 flex space-x-4">
                                    <div class="flex items-center">
                                        <label for="from_da" class="text-xs text-gray-500 self-start text-right">{{ $t('From') }}</label>
                                        <input v-model="filters.da.from" id="from_da" name="from_da" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>

                                    <div class="flex items-center">
                                        <label for="to_da" class="text-xs text-gray-500 self-start text-right">{{ $t('To') }}</label>
                                        <input v-model="filters.da.to" id="to_da" name="to_da" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-2 flex flex-col">
                                <span class="flex items-center space-x-2 text-sm font-medium">
                                    <span class="block h-2 w-2 bg-purple-500 rounded-full"></span>
                                    <span>{{ $t('Domain rating range') }}</span>
                                </span>

                                <div class="mt-4 flex space-x-4">
                                    <div class="flex items-center">
                                        <label for="from_dr" class="text-xs text-gray-500 self-start text-right">{{ $t('From') }}</label>
                                        <input v-model="filters.dr.from" id="from_dr" name="from_dr" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>

                                    <div class="flex items-center">
                                        <label for="to_dr" class="text-xs text-gray-500 self-start text-right">{{ $t('To') }}</label>
                                        <input v-model="filters.dr.to" id="to_dr" name="to_dr" type="text"
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
                                    <!-- <select v-model="filters.language_id" class="w-full border-0 rounded-md">
                                        <option :value="null">{{ $t('All') }}</option>
                                        <option v-for="(language, index) in languages" :key="index" :value="language.id">
                                            {{ language.name }}
                                        </option>
                                    </select> -->

                                    <div class="flex flex-col space-y-2">
                                        <div v-for="(language, index) in languages" :key="index" class="relative flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input :value="language.id" v-model="filters.language_id" :id="`language-${language.id}`" :name="`language-${language.id}`" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
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

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                <SwitchGroup as="div" class="col-span-1 px-4 flex justify-end items-center">
                                    <SwitchLabel as="span" class="flex h-full">
                                        <span class="text-sm font-medium self-center">Favorites</span>
                                    </SwitchLabel>

                                    <Switch v-model="filters.favorites"
                                        :class="[filters.favorites ? 'bg-blue-600' : 'bg-gray-200', 'ml-2 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']">
                                        <span aria-hidden="true"
                                            :class="[filters.favorites ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                                    </Switch>
                                </SwitchGroup>

                                <SwitchGroup as="div" class="col-span-1 px-4 flex justify-end items-center">
                                    <SwitchLabel as="span" class="flex h-full">
                                        <span class="text-sm font-medium self-center">Recommended</span>
                                    </SwitchLabel>

                                    <Switch v-model="filters.recommended"
                                        :class="[filters.recommended ? 'bg-blue-600' : 'bg-gray-200', 'ml-2 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']">
                                        <span aria-hidden="true"
                                            :class="[filters.recommended ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                                    </Switch>
                                </SwitchGroup>

                                <SwitchGroup as="div" class="col-span-1 px-4 flex justify-end items-center">
                                    <SwitchLabel as="span" class="flex h-full">
                                        <span class="text-sm font-medium self-center">Gambling</span>
                                    </SwitchLabel>

                                    <Switch v-model="filters.gambling"
                                        :class="[filters.gambling ? 'bg-blue-600' : 'bg-gray-200', 'ml-2 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']">
                                        <span aria-hidden="true"
                                            :class="[filters.gambling ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                                    </Switch>
                                </SwitchGroup>

                                <SwitchGroup as="div" class="col-span-1 px-4 flex justify-end items-center">
                                    <SwitchLabel as="span" class="flex h-full">
                                        <span class="text-sm font-medium self-center">Sponsor</span>
                                    </SwitchLabel>

                                    <Switch v-model="filters.sponsor"
                                        :class="[filters.sponsor ? 'bg-blue-600' : 'bg-gray-200', 'ml-2 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']">
                                        <span aria-hidden="true"
                                            :class="[filters.sponsor ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                                    </Switch>
                                </SwitchGroup>

                                <SwitchGroup as="div" class="col-span-1 px-4 flex justify-end items-center">
                                    <SwitchLabel as="span" class="flex h-full">
                                        <span class="text-sm font-medium self-center">New</span>
                                    </SwitchLabel>

                                    <Switch v-model="filters.new"
                                        :class="[filters.new ? 'bg-blue-600' : 'bg-gray-200', 'ml-2 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']">
                                        <span aria-hidden="true"
                                            :class="[filters.new ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                                    </Switch>
                                </SwitchGroup>
                            </div>

                            <div class="w-full">
                                <label for="search" class="text-sm font-medium">{{ $t('...or just find by name:') }}</label>
                                <div class="mt-1 relative  text-gray-400 focus-within:text-gray-600">
                                    <div class="pointer-events-none absolute inset-y-0 left-2 flex items-center" aria-hidden="true">
                                        <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
                                    </div>
                                    <input v-model="filters.url" id="search" name="search"
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
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{
                                $t('Showing results:')
                            }}&nbsp;{{ sites.total }}&nbsp;{{ $t('sites found') }}</h2>
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
                                            <div class="flex group">
                                                <span class="block">{{ $t('Price') }}</span>
                                                <TableSortButton column='sale' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>

                                        <th v-show="columns[1].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Domain') }}</span>
                                                <TableSortButton column='url' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[2].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('DA') }}</span>
                                                <TableSortButton column='da' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>

                                        <th v-show="columns[3].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('DR') }}</span>
                                                <TableSortButton column='dr' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[4].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Gambling') }}</th>
                                        <th v-show="columns[5].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Sponsor') }}</th>
                                        <th v-show="columns[6].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Cripto') }}</th>
                                        <th v-show="columns[7].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('SSL') }}</th>
                                        <th v-show="columns[8].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Category') }}</th>
                                        <!-- <th v-show="columns[9].visible"
                                    class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                    scope="col">{{$t('Banners')}}</th>
                                <th v-show="columns[10].visible"
                                    class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                    scope="col">Links {{$t('menu')}}</th> -->
                                        <th v-show="columns[9].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Obs') }}</th>
                                        <th v-show="columns[10].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Example') }}</span>
                                                <TableSortButton column='example' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[11].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Upload date') }}</span>
                                                <TableSortButton column='inserted_at' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(site, index) in sites.data" :key="index" class="bg-white">
                                        <td v-show="columns[0].visible" class="whitespace-nowrap px-4 py-4 text-sm">
                                            <span class="relative flex space-x-2 items-center">
                                                <span>
                                                    {{ site.sale_coin != coinStore.coin ? '~ ' : null }}
                                                    {{
                                                        $filters.currency((Math.round((site.sale /
                                                            coinStore.ratios[site.sale_coin]) / 5) * 5) / 100,
                                                            coins[coinStore.coin])
                                                    }}
                                                </span>

                                                <!-- <span class="text-red-500">{{ $filters.currency(site.sale / 100, coins[site.sale_coin]) }} - {{ site.sale }} - {{ site.sale_coin }}</span> -->
                                            </span>
                                        </td>
                                        <td v-show="columns[1].visible" class="whitespace-nowrap px-4 py-4 text-sm">
                                            <Link :href="route('client.sites.show', site.id)"
                                                class="text-gray-500 hover:text-gray-900">
                                            {{ site.url }}
                                            </Link>
                                        </td>
                                        <td v-show="columns[2].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.da ?? '-' }}
                                        </td>
                                        <td v-show="columns[3].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.dr ?? '-' }}
                                        </td>
                                        <td v-show="columns[4].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.gambling ? 'Yes' : 'No') }}
                                        </td>
                                        <td v-show="columns[5].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.sponsor ? 'Yes' : 'No') }}
                                        </td>
                                        <td v-show="columns[6].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.cripto ? 'Yes' : 'No') }}
                                        </td>
                                        <td v-show="columns[7].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.ssl ? 'Yes' : 'No') }}
                                        </td>
                                        <td v-show="columns[8].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            <span :data-tippy-content="site.category?.subtitle">{{ site.category?.title ?? '-' }}</span>
                                        </td>
                                        <!-- <td v-show="columns[9].visible"
                                    class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                    {{ site.banner ? 'Sim' : 'Não' }}
                                </td>
                                <td v-show="columns[10].visible"
                                    class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                    {{ site.menu ? 'Sim' : 'Não' }}
                                </td> -->
                                        <td v-show="columns[9].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.obs ?? '-' }}
                                        </td>
                                        <td v-show="columns[10].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            -
                                        </td>
                                        <td v-show="columns[11].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.formatted_inserted_at }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            <button @click="toggleFavorite(site.id)">
                                                <svg v-if="favorites.includes(site.id)"
                                                    xmlns="http://www.w3.org/2000/svg" class="text-red-500 h-6 w-6"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                        clip-rule="evenodd" />
                                                </svg>

                                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            </button>
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
                                <Link :href="sites.next_page_url"
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
