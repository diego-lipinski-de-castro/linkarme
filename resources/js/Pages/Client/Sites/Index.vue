<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import TableSortButton from '@/Components/TableSortButton.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, onMounted, ref, watch } from 'vue'
import unionBy from 'lodash/unionBy'
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
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
    { key: 'cripto', label: t('Cripto'), visible: true },
    { key: 'ssl', label: t('SSL'), visible: true },
    { key: 'category', label: t('Category'), visible: true },
    // { key: 'banner', label: t('Banners'), visible: true },
    // { key: 'menu', label: t('Links menu'), visible: true },
    { key: 'obs', label: t('Obs'), visible: true },
    { key: 'example', label: t('Example'), visible: true },
    { key: 'inserted_at', label: t('Upload date'), visible: true },
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

const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});

const sort = ref(params.sort ?? 'url')

const filters = ref({
    url: params["filter[url]"] ?? null,
    sale: { from: params["filter[sale][from]"] ?? props.filters.sale.from, to: params["filter[sale][to]"] ?? props.filters.sale.to },
    da: { from: params["filter[da][from]"] ?? props.filters.da.from, to: params["filter[da][to]"] ?? props.filters.da.to },
    dr: { from: params["filter[dr][from]"] ?? props.filters.dr.from, to: params["filter[dr][to]"] ?? props.filters.dr.to },
    // traffic: { from: params["filter[traffic][from]"] ?? null, to: params["filter[traffic][to]"] ?? null },
    favorites: params["filter[favorites]"] == 'true',
    gambling: params["filter[gambling]"] == 'true',
    sponsor: params["filter[sponsor]"] == 'true',
    cripto: params["filter[cripto]"] == 'true',
    // ssl: params["filter[ssl]"] == 'true',
    // banner: params["filter[banner]"] == 'true',
    menu: params["filter[menu]"] == 'true',
    new: params["filter[new]"] == 'true',
    favorites: params["filter[favorites]"] == 'true',
    // country_id: params["filter[country_id]"] ?? null,
    language_id: params["filter[language_id]"] ?? null,
})

watch(sort, (n, o) => get());

watch(filters, debounce((n, o) => {
    get()
}, 400), {
    deep: true,
})

const get = async () => {
    Inertia.get(route('client.sites.index'), {
        sort: sort.value,
        filter: {
            url: filters.value.url,
            sale: {
                from: filters.value.sale.from * 100,
                to: filters.value.sale.to * 100,
            },
            da: filters.value.da,
            dr: filters.value.dr,
            gambling: filters.value.gambling,
            sponsor: filters.value.sponsor,
            new: filters.value.new,
            ...(filters.value.favorites && {
                favorites: filters.value.favorites,
            }),
            ...(filters.value.language_id !== null && {
                language_id: filters.value.language_id,
            }),
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
</script>
    
<template>
    <AppSuspense>
        <ClientLayout title="Sites">
            <template #uppermenu>
                <div class="flex w-full md:ml-0">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" aria-hidden="true">
                            <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
                        </div>
                        <input v-model="filters.url" id="search" name="search"
                            class="block h-full w-full border-transparent py-2 pl-8 pr-3 text-gray-900 placeholder-gray-500 focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm"
                            :placeholder="$t('Search sites')" type="search" />
                    </div>
                </div>
            </template>

            <template #header>
                <div
                    class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Sites
                    </h2>

                    <Menu as="div" class="relative ml-3">
                        <div class="hidden sm:block">
                            <MenuButton
                                class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 p-2 hover:bg-gray-50">
                                <span class="ml-1 text-sm font-medium text-gray-700">Columns</span>
                                <ChevronDownIcon class="ml-1 h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
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

                                <div v-for="(column, index) in columns" :key="index" class="px-4 py-2 relative flex">
                                    <div class="flex items-center h-5">
                                        <input v-model="column.visible" :value="column.key" :id="column.key"
                                            :name="column.key" type="checkbox"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                                    </div>

                                    <div class="ml-3 text-sm">
                                        <label :for="column.key" class="font-medium text-gray-700">{{ column.label
                                        }}</label>
                                    </div>
                                </div>

                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </template>

            <template #submenu>
                <div class="mb-12 px-4">
                    <span class="block text-sm font-medium text-white">{{ $t('Language') }}</span>

                    <div class="ml-2 flex items-center mt-4">
                        <select v-model="filters.language_id" class="w-full border-0 rounded-md">
                            <option :value="null">{{ $t('All') }}</option>
                            <option v-for="(language, index) in languages" :key="index" :value="language.id">
                                {{ language.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-12 px-4">
                    <span class="block text-sm font-medium text-white">Price range</span>

                    <div class="ml-2 flex items-center mt-4">
                        <label for="from_sale" class="w-24 text-sm font-medium text-white">from</label>

                        <ChevronUpIcon class="cursor-pointer h-10 w-10 text-green-500" @click="filters.sale.from++"/>
                        <input v-model="filters.sale.from" id="from_sale" name="from_sale" type="text"
                            class="mx-2 w-[7rem] bg-transparent text-sm font-medium text-white border-0 border-b border-white border-opacity-50 focus:border-white focus:ring-0" />
                        <ChevronDownIcon class="cursor-pointer h-10 w-10 text-red-500" @click="filters.sale.from--"/>
                    </div>

                    <div class="ml-2 flex items-center mt-2">
                        <label for="to_sale" class="w-24 text-sm font-medium text-white">to</label>
                        <ChevronUpIcon class="cursor-pointer h-10 w-10 text-green-500" @click="filters.sale.to++"/>
                        <input v-model="filters.sale.to" id="to_sale" name="to_sale" type="text"
                            class="mx-2 w-[7rem] bg-transparent text-sm font-medium text-white border-0 border-b border-white border-opacity-50 focus:border-white focus:ring-0" />
                        <ChevronDownIcon class="cursor-pointer h-10 w-10 text-red-500" @click="filters.sale.to--"/>
                    </div>
                </div>

                <div class="mb-12 px-4">
                    <span class="block text-sm font-medium text-white">DA range</span>

                    <div class="ml-2 flex items-center mt-4">
                        <label for="from_da" class="w-24 text-sm font-medium text-white">from</label>

                        <ChevronUpIcon class="cursor-pointer h-10 w-10 text-green-500" @click="filters.da.from++"/>
                        <input v-model="filters.da.from" id="from_da" name="from_da" type="number"
                            class="mx-2 w-[7rem] bg-transparent text-sm font-medium text-white border-0 border-b border-white border-opacity-50 focus:border-white focus:ring-0" />
                        <ChevronDownIcon class="cursor-pointer h-10 w-10 text-red-500" @click="filters.da.from--"/>
                    </div>

                    <div class="ml-2 flex items-center mt-2">
                        <label for="to_da" class="w-24 text-sm font-medium text-white">to</label>

                        <ChevronUpIcon class="cursor-pointer h-10 w-10 text-green-500" @click="filters.da.to++"/>
                        <input v-model="filters.da.to" id="to_da" name="to_da" type="number"
                            class="mx-2 w-[7rem] bg-transparent text-sm font-medium text-white border-0 border-b border-white border-opacity-50 focus:border-white focus:ring-0" />
                        <ChevronDownIcon class="cursor-pointer h-10 w-10 text-red-500" @click="filters.da.to--"/>
                    </div>
                </div>

                <div class="mb-12 px-4">
                    <span class="block text-sm font-medium text-white">DR range</span>

                    <div class="ml-2 flex items-center mt-4">
                        <label for="from_dr" class="w-24 text-sm font-medium text-white">from</label>

                        <ChevronDownIcon class="cursor-pointer h-10 w-10 text-green-500" @click="filters.dr.from++"/>
                        <input v-model="filters.dr.from" id="from_dr" name="from_dr" type="number"
                            class="mx-2 w-[7rem] bg-transparent text-sm font-medium text-white border-0 border-b border-white border-opacity-50 focus:border-white focus:ring-0" />
                        <ChevronDownIcon class="cursor-pointer h-10 w-10 text-red-500" @click="filters.dr.from--"/>
                    </div>

                    <div class="ml-2 flex items-center mt-2">
                        <label for="to_dr" class="w-24 text-sm font-medium text-white">to</label>

                        <ChevronDownIcon class="cursor-pointer h-10 w-10 text-green-500" @click="filters.dr.to++"/>
                        <input v-model="filters.dr.to" id="to_dr" name="to_dr" type="number"
                            class="mx-2 w-[7rem] bg-transparent text-sm font-medium text-white border-0 border-b border-white border-opacity-50 focus:border-white focus:ring-0" />
                        <ChevronDownIcon class="cursor-pointer h-10 w-10 text-red-500" @click="filters.dr.to--"/>
                    </div>
                </div>

                <!-- <div class="mb-12 px-4">
                <span class="block text-sm font-medium text-white">Traffic range</span>

                <div class="ml-2 flex items-center mt-4">
                    <label for="from_traffic" class="w-24 text-sm font-medium text-white">from</label>
                    <input v-model="filters.traffic.from" id="from_traffic" name="from_traffic" type="number"
                        class="w-24 bg-transparent text-sm font-medium text-white border-0 border-b border-white focus:border-white focus:ring-0" />
                </div>

                <div class="ml-2 flex items-center mt-2">
                    <label for="to_traffic" class="w-24 text-sm font-medium text-white">to</label>
                    <input v-model="filters.traffic.to" id="to_traffic" name="to_traffic" type="number"
                        class="w-24 bg-transparent text-sm font-medium text-white border-0 border-b border-white focus:border-white focus:ring-0" />
                </div>
            </div> -->

                <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                    <SwitchLabel as="span">
                        <span class="text-sm font-medium text-white">Favorites</span>
                    </SwitchLabel>

                    <Switch v-model="filters.favorites"
                        :class="[filters.favorites ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                        <span aria-hidden="true"
                            :class="[filters.favorites ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                    </Switch>
                </SwitchGroup>

                <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                    <SwitchLabel as="span">
                        <span class="text-sm font-medium text-white">Gambling</span>
                    </SwitchLabel>

                    <Switch v-model="filters.gambling"
                        :class="[filters.gambling ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                        <span aria-hidden="true"
                            :class="[filters.gambling ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                    </Switch>
                </SwitchGroup>

                <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                    <SwitchLabel as="span">
                        <span class="text-sm font-medium text-white">Sponsor</span>
                    </SwitchLabel>

                    <Switch v-model="filters.sponsor"
                        :class="[filters.sponsor ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                        <span aria-hidden="true"
                            :class="[filters.sponsor ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                    </Switch>
                </SwitchGroup>

                <!-- <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                <SwitchLabel as="span">
                    <span class="text-sm font-medium text-white">Cripto</span>
                </SwitchLabel>

                <Switch v-model="filters.cripto"
                    :class="[filters.cripto ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                    <span aria-hidden="true"
                        :class="[filters.cripto ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                </Switch>
            </SwitchGroup>

            <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                <SwitchLabel as="span">
                    <span class="text-sm font-medium text-white">SSL</span>
                </SwitchLabel>

                <Switch v-model="filters.ssl"
                    :class="[filters.ssl ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                    <span aria-hidden="true"
                        :class="[filters.ssl ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                </Switch>
            </SwitchGroup>

            <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                <SwitchLabel as="span">
                    <span class="text-sm font-medium text-white">Banners</span>
                </SwitchLabel>

                <Switch v-model="filters.banner"
                    :class="[filters.banner ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                    <span aria-hidden="true"
                        :class="[filters.banner ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                </Switch>
            </SwitchGroup>

            <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                <SwitchLabel as="span">
                    <span class="text-sm font-medium text-white">Links menu</span>
                </SwitchLabel>

                <Switch v-model="filters.menu"
                    :class="[filters.menu ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                    <span aria-hidden="true"
                        :class="[filters.menu ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                </Switch>
            </SwitchGroup> -->

                <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                    <SwitchLabel as="span">
                        <span class="text-sm font-medium text-white">New</span>
                    </SwitchLabel>

                    <Switch v-model="filters.new"
                        :class="[filters.new ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                        <span aria-hidden="true"
                            :class="[filters.new ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                    </Switch>
                </SwitchGroup>

            </template>

            <template #submenu-mobile>
                <div class="mb-12 px-4">
                    <span class="block text-sm font-medium text-white">{{ $t('Language') }}</span>

                    <div class="ml-2 flex items-center mt-4">
                        <select v-model="filters.language_id" class="w-full border-0 rounded-md">
                            <option :value="null">{{ $t('All') }}</option>
                            <option v-for="(language, index) in languages" :key="index" :value="language.id">
                                {{ language.name }}
                            </option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-12 px-4">
                    <span class="block text-sm font-medium text-white">Price range</span>

                    <div class="ml-2 flex items-center mt-4">
                        <label for="from_sale" class="w-24 text-sm font-medium text-white">from</label>
                        <input v-model="filters.sale.from" id="from_sale" name="from_sale" type="text"
                            class="w-24 bg-transparent text-sm font-medium text-white border-0 border-b border-white focus:border-white focus:ring-0" />
                    </div>

                    <div class="ml-2 flex items-center mt-2">
                        <label for="to_sale" class="w-24 text-sm font-medium text-white">to</label>
                        <input v-model="filters.sale.to" id="to_sale" name="to_sale" type="text"
                            class="w-24 bg-transparent text-sm font-medium text-white border-0 border-b border-white focus:border-white focus:ring-0" />
                    </div>
                </div>

                <div class="mb-12 px-4">
                    <span class="block text-sm font-medium text-white">DA range</span>

                    <div class="ml-2 flex items-center mt-4">
                        <label for="from_da" class="w-24 text-sm font-medium text-white">from</label>
                        <input v-model="filters.da.from" id="from_da" name="from_da" type="number"
                            class="w-24 bg-transparent text-sm font-medium text-white border-0 border-b border-white focus:border-white focus:ring-0" />
                    </div>

                    <div class="ml-2 flex items-center mt-2">
                        <label for="to_da" class="w-24 text-sm font-medium text-white">to</label>
                        <input v-model="filters.da.to" id="to_da" name="to_da" type="number"
                            class="w-24 bg-transparent text-sm font-medium text-white border-0 border-b border-white focus:border-white focus:ring-0" />
                    </div>
                </div>

                <div class="mb-12 px-4">
                    <span class="block text-sm font-medium text-white">DR range</span>

                    <div class="ml-2 flex items-center mt-4">
                        <label for="from_dr" class="w-24 text-sm font-medium text-white">from</label>
                        <input v-model="filters.dr.from" id="from_dr" name="from_dr" type="number"
                            class="w-24 bg-transparent text-sm font-medium text-white border-0 border-b border-white focus:border-white focus:ring-0" />
                    </div>

                    <div class="ml-2 flex items-center mt-2">
                        <label for="to_dr" class="w-24 text-sm font-medium text-white">to</label>
                        <input v-model="filters.dr.to" id="to_dr" name="to_dr" type="number"
                            class="w-24 bg-transparent text-sm font-medium text-white border-0 border-b border-white focus:border-white focus:ring-0" />
                    </div>
                </div>

                <!-- <div class="mb-12 px-4">
                <span class="block text-sm font-medium text-white">Traffic range</span>

                <div class="ml-2 flex items-center mt-4">
                    <label for="from_traffic" class="w-24 text-sm font-medium text-white">from</label>
                    <input v-model="filters.traffic.from" id="from_traffic" name="from_traffic" type="number"
                        class="w-24 bg-transparent text-sm font-medium text-white border-0 border-b border-white focus:border-white focus:ring-0" />
                </div>

                <div class="ml-2 flex items-center mt-2">
                    <label for="to_traffic" class="w-24 text-sm font-medium text-white">to</label>
                    <input v-model="filters.traffic.to" id="to_traffic" name="to_traffic" type="number"
                        class="w-24 bg-transparent text-sm font-medium text-white border-0 border-b border-white focus:border-white focus:ring-0" />
                </div>
            </div> -->

                <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                    <SwitchLabel as="span">
                        <span class="text-sm font-medium text-white">Favorites</span>
                    </SwitchLabel>

                    <Switch v-model="filters.favorites"
                        :class="[filters.favorites ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                        <span aria-hidden="true"
                            :class="[filters.favorites ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                    </Switch>
                </SwitchGroup>

                <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                    <SwitchLabel as="span">
                        <span class="text-sm font-medium text-white">Gambling</span>
                    </SwitchLabel>

                    <Switch v-model="filters.gambling"
                        :class="[filters.gambling ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                        <span aria-hidden="true"
                            :class="[filters.gambling ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                    </Switch>
                </SwitchGroup>

                <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                    <SwitchLabel as="span">
                        <span class="text-sm font-medium text-white">Sponsor</span>
                    </SwitchLabel>

                    <Switch v-model="filters.sponsor"
                        :class="[filters.sponsor ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                        <span aria-hidden="true"
                            :class="[filters.sponsor ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                    </Switch>
                </SwitchGroup>

                <!-- <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                <SwitchLabel as="span">
                    <span class="text-sm font-medium text-white">Cripto</span>
                </SwitchLabel>

                <Switch v-model="filters.cripto"
                    :class="[filters.cripto ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                    <span aria-hidden="true"
                        :class="[filters.cripto ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                </Switch>
            </SwitchGroup>

            <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                <SwitchLabel as="span">
                    <span class="text-sm font-medium text-white">SSL</span>
                </SwitchLabel>

                <Switch v-model="filters.ssl"
                    :class="[filters.ssl ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                    <span aria-hidden="true"
                        :class="[filters.ssl ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                </Switch>
            </SwitchGroup>

            <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                <SwitchLabel as="span">
                    <span class="text-sm font-medium text-white">Banners</span>
                </SwitchLabel>

                <Switch v-model="filters.banner"
                    :class="[filters.banner ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                    <span aria-hidden="true"
                        :class="[filters.banner ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                </Switch>
            </SwitchGroup>

            <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                <SwitchLabel as="span">
                    <span class="text-sm font-medium text-white">Links menu</span>
                </SwitchLabel>

                <Switch v-model="filters.menu"
                    :class="[filters.menu ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                    <span aria-hidden="true"
                        :class="[filters.menu ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                </Switch>
            </SwitchGroup> -->

                <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                    <SwitchLabel as="span">
                        <span class="text-sm font-medium text-white">New</span>
                    </SwitchLabel>

                    <Switch v-model="filters.new"
                        :class="[filters.new ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                        <span aria-hidden="true"
                            :class="[filters.new ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                    </Switch>
                </SwitchGroup>

            </template>

            <div>
                <div class="sm:hidden border border-gray-200 rounded-lg overflow-hidden">
                    <ul role="list" class="divide-y divide-gray-200">

                        <li v-for="(site, index) in sites.data" :key="index">
                            <Link :href="route('client.sites.show', site.id)"
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
                            <Link :href="sites.prev_page_url"
                                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                            {{$t('Previous')}}</Link>
                            <Link :href="sites.next_page_url"
                                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                            {{$t('Next')}}</Link>
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
                                        <th class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"></th>
                                        <th v-show="columns[0].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{$t('Price')}}</span>
                                                <TableSortButton column='sale' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>

                                        <th v-show="columns[1].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{$t('Domain')}}</span>
                                                <TableSortButton column='url' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[2].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{$t('DA')}}</span>
                                                <TableSortButton column='da' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>

                                        <th v-show="columns[3].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{$t('DR')}}</span>
                                                <TableSortButton column='dr' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[4].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{$t('Gambling')}}</th>
                                        <th v-show="columns[5].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{$t('Sponsor')}}</th>
                                        <th v-show="columns[6].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{$t('Cripto')}}</th>
                                        <th v-show="columns[7].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{$t('SSL')}}</th>
                                        <th v-show="columns[8].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{$t('Category')}}</th>
                                        <!-- <th v-show="columns[9].visible"
                                        class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{$t('Banners')}}</th>
                                    <th v-show="columns[10].visible"
                                        class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">Links {{$t('menu')}}</th> -->
                                        <th v-show="columns[9].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{$t('Obs')}}</th>
                                        <th v-show="columns[10].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{$t('Example')}}</span>
                                                <TableSortButton column='example' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[11].visible"
                                            class="whitespace-nowrap bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{$t('Upload date')}}</span>
                                                <TableSortButton column='inserted_at' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(site, index) in sites.data" :key="index" class="bg-white">
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
                                        <td v-show="columns[0].visible" class="whitespace-nowrap px-4 py-4 text-sm">
                                            <span class="relative flex space-x-2 items-center">
                                                <span> {{ site.sale_coin != coinStore.coin ? '~' : null }} {{
                                                $filters.currency((site.sale / coinStore.ratios[site.sale_coin]) /
                                                100, coins[coinStore.coin]) }}</span>
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
                                            <div class="flex justify-center">
                                                <span
                                                    :class="['block h-2 w-2 rounded-full', site.gambling ? 'bg-green-300' : 'bg-red-300']"></span>
                                            </div>
                                        </td>
                                        <td v-show="columns[5].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            <div class="flex justify-center">
                                                <span
                                                    :class="['block h-2 w-2 rounded-full', site.sponsor ? 'bg-green-300' : 'bg-red-300']"></span>
                                            </div>
                                        </td>
                                        <td v-show="columns[6].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            <div class="flex justify-center">
                                                <span
                                                    :class="['block h-2 w-2 rounded-full', site.cripto ? 'bg-green-300' : 'bg-red-300']"></span>
                                            </div>
                                        </td>
                                        <td v-show="columns[7].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            <div class="flex justify-center">
                                                <span
                                                    :class="['block h-2 w-2 rounded-full', site.ssl ? 'bg-green-300' : 'bg-red-300']"></span>
                                            </div>
                                        </td>
                                        <td v-show="columns[8].visible"
                                            class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.category?.name ?? '-' }}
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
                                    :class="[link.active ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'inline-flex items-center border-t-2  px-4 pt-4 text-sm font-medium ']">
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
        </ClientLayout>
    </AppSuspense>
</template>
    