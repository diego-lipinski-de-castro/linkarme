<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TableSortButton from '@/Components/TableSortButton.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, onMounted, ref, watch } from 'vue'
import unionBy from 'lodash/unionBy'
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
    Switch, SwitchGroup, SwitchLabel,
} from '@headlessui/vue'

import { ArrowLongLeftIcon, ArrowLongRightIcon, CloudArrowDownIcon, CloudArrowUpIcon } from '@heroicons/vue/20/solid'

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
    PencilSquareIcon,
} from '@heroicons/vue/24/outline'
import {
    BanknotesIcon,
    BuildingOfficeIcon,
    CheckCircleIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/20/solid'

import { debounce } from 'debounce';
import { trans } from 'laravel-vue-i18n';
import { useCoinStore } from '@/stores/coin'

import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

const FilePond = vueFilePond(FilePondPluginFileValidateType);

const coinStore = useCoinStore()

const props = defineProps({
    title: String,
    sites: Object,
    coins: Object,
    countries: Array,
    languages: Array,
    categories: Array,
    importFailures: Object,
    importDiff: Number,
});

const links = computed(() => {
    const _links = props.sites.links
    _links.shift()
    _links.pop()
    return _links
})

const _defaultColumns = [
    { key: 'sale', label: trans('Price'), visible: true },
    { key: 'url', label: trans('Domain'), visible: true },
    { key: 'da', label: trans('DA'), visible: true },
    { key: 'dr', label: trans('DR'), visible: true },
    { key: 'gambling', label: trans('Gambling'), visible: true },
    { key: 'sponsor', label: trans('Sponsor'), visible: true },
    { key: 'cripto', label: trans('Cripto'), visible: true },
    { key: 'ssl', label: trans('SSL'), visible: true },
    { key: 'category', label: trans('Category'), visible: true },
    // { key: 'banner', label: trans('Banners'), visible: true },
    // { key: 'menu', label: trans('Links menu'), visible: true },
    { key: 'obs', label: trans('Obs'), visible: true },
    { key: 'example', label: trans('Example'), visible: true },
    { key: 'inserted_at', label: trans('Upload data'), visible: true },
];

const _columns =
    localStorage.getItem('sites.index.columns') ?
        unionBy(JSON.parse(localStorage.getItem('sites.index.columns')), _defaultColumns, 'key')
        : _defaultColumns

const columns = ref(_columns)

watch(columns, (n, o) => {
    console.log(n, o)
    localStorage.setItem('sites.index.columns', JSON.stringify(columns.value))
}, {
    deep: true,
})

const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});

const sort = ref(params.sort ?? 'url')

const filters = ref({
    url: params["filter[url]"] ?? null,
    da: { from: params["filter[da][from]"] ?? null, to: params["filter[da][to]"] ?? null },
    dr: { from: params["filter[dr][from]"] ?? null, to: params["filter[dr][to]"] ?? null },
    // traffic: { from: params["filter[traffic][from]"] ?? null, to: params["filter[traffic][to]"] ?? null },
    gambling: params["filter[gambling]"] == 'true',
    sponsor: params["filter[sponsor]"] == 'true',
    cripto: params["filter[cripto]"] == 'true',
    // ssl: params["filter[ssl]"] == 'true',
    // banner: params["filter[banner]"] == 'true',
    menu: params["filter[menu]"] == 'true',
    new: params["filter[new]"] == 'true',
})

watch(sort, (n, o) => get());

watch(filters, debounce((n, o) => {
    get()
}, 400), {
    deep: true,
})

const get = async () => {
    Inertia.get(route('sites.index'), {
        sort: sort.value,
        filter: {
            url: filters.value.url,
            da: filters.value.da,
            dr: filters.value.dr,
            gambling: filters.value.gambling,
            sponsor: filters.value.sponsor,
            cripto: filters.value.cripto,
            ssl: filters.value.ssl,
            banner: filters.value.banner,
            menu: filters.value.menu,
            new: filters.value.new,
        },
    }, {
        preserveState: true,
    })
}

const importFilepond = ref(null)
const openImportDialog = ref(false)
const importFinished = ref(false)

const uploadSites = (fieldName, file, metadata, load, error, progress, abort) => {
    Inertia.post(route('sites.import'), {
        file: file,
    }, {
        forceFormData: true,
        preserveScroll: true,
        preserveState: true,
        onError(err) {
            abort()
        },
        onSuccess(res) {
            load()
        },
        onProgress(p) {
            progress(true, p.loaded, p.total);
        },
        onFinish() {
            importFinished.value = true
            // setTimeout(() => {
            //     importFilepond.value.removeFiles()
            // }, 600)
        }
    })
}

watch(openImportDialog, (n, o) => {
    if (!n) {
        setTimeout(() => {
            importFinished.value = false
            Inertia.reload({
                only: ['importFailures', 'importDiff'],
            })
        }, 300)
    }
})

onMounted(() => {
    tippy('[data-tippy-content]', {
        interactive: true,
    });
})
</script>
        
<template>

    <TransitionRoot as="template" :show="openImportDialog">
        <Dialog as="div" class="relative z-10" @close="openImportDialog = false">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel
                            class="relative transform overflow-scroll rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 max-h-96 sm:p-6">

                            <h3 class="text-center font-medium text-gray-900">Importar sites</h3>

                            <div class="mt-4 mx-auto w-96">
                                <file-pond ref="importFilepond" :server="{ process: uploadSites }" :instantUpload="true"
                                    :allowRevert="false" accepted-file-types="text/csv" :dropOnPage="true"
                                    :dropOnElement="false" :dropValidation="true" />
                            </div>

                            <div v-if="importFinished" class="mt-8">
                                <span class="block text-center">{{ importDiff }} sites adicionados.</span>

                                <div v-if="importFailures.length > 0" class="mt-4">
                                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                        Linha</th>
                                                    <th scope="col"
                                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l">
                                                        Coluna</th>
                                                    <th scope="col"
                                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l">
                                                        Valor</th>
                                                    <th scope="col"
                                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l">
                                                        Erro</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                <tr v-for="(failure, index) in importFailures" :key="index">
                                                    <td
                                                        class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">
                                                        {{failure.row }}</td>
                                                    <td
                                                        class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 border-l">
                                                        {{ failure.attribute }}</td>
                                                    <td
                                                        class="whitespace-nowrap px-2 py-2 text-sm text-gray-900 border-l">
                                                        {{
                                                        failure.values[failure.attribute] ?? '-' }}</td>
                                                    <td
                                                        class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 border-l">
                                                        {{
                                                        failure.errors[0]}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <AppLayout title="Sites">
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

                <div class="flex space-x-3">
                    <Link :href="route('sites.create')"
                        class="flex max-w-xs items-center rounded-md bg-indigo-500 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 p-2 hover:bg-indigo-700">
                    <span class="px-1 text-sm font-medium text-white">{{ $t('Add site') }}</span>
                    </Link>

                    <button @click="openImportDialog = true"
                        class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 p-2 hover:bg-gray-50">
                        <span class="ml-1 text-sm font-medium text-gray-700">{{ $t('Import') }}</span>
                        <CloudArrowUpIcon class="ml-2 h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                    </button>

                    <a :href="route('sites.export')"
                        class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 p-2 hover:bg-gray-50">
                        <span class="ml-1 text-sm font-medium text-gray-700">{{ $t('Export') }}</span>
                        <CloudArrowDownIcon class="ml-2 h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                    </a>

                    <Menu as="div" class="relative">
                        <div class="hidden sm:block">
                            <MenuButton
                                class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 p-2 hover:bg-gray-50">
                                <span class="ml-1 text-sm font-medium text-gray-700">{{ $t('Columns') }}</span>
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
            </div>
        </template>

        <template #submenu>
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
                    <span class="text-sm font-medium text-white">Cassino</span>
                </SwitchLabel>

                <Switch v-model="filters.gambling"
                    :class="[filters.gambling ? 'bg-pink-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2']">
                    <span aria-hidden="true"
                        :class="[filters.gambling ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                </Switch>
            </SwitchGroup>

            <SwitchGroup as="div" class="my-6 px-4 flex justify-between items-center">
                <SwitchLabel as="span">
                    <span class="text-sm font-medium text-white">Publi</span>
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
                        <a :href="route('sites.edit', site.id)" class="block bg-white px-4 py-4 hover:bg-gray-50">
                            <span class="flex items-center space-x-4">
                                <span class="flex flex-1 space-x-2 truncate">
                                    <span class="text-sm text-gray-500">
                                        {{ site.url }}
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
                        <Link :href="sites.prev_page_url"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                        {{ $t('Previous') }}</Link>
                        <Link :href="sites.next_page_url"
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
                                    <th v-show="columns[0].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">
                                        <div class="flex group">
                                            <span class="block">{{ $t('Price') }}</span>
                                            <TableSortButton column='sale' :current="sort"
                                                @onClick='(column) => sort = column' />
                                        </div>
                                    </th>

                                    <th v-show="columns[1].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">
                                        <div class="flex group">
                                            <span class="block">{{ $t('Domain') }}</span>
                                            <TableSortButton column='url' :current="sort"
                                                @onClick='(column) => sort = column' />
                                        </div>
                                    </th>
                                    <th v-show="columns[2].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">
                                        <div class="flex group">
                                            <span class="block">{{ $t('DA') }}</span>
                                            <TableSortButton column='da' :current="sort"
                                                @onClick='(column) => sort = column' />
                                        </div>
                                    </th>

                                    <th v-show="columns[3].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">
                                        <div class="flex group">
                                            <span class="block">{{ $t('DR') }}</span>
                                            <TableSortButton column='dr' :current="sort"
                                                @onClick='(column) => sort = column' />
                                        </div>
                                    </th>
                                    <th v-show="columns[4].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Gambling') }}</th>
                                    <th v-show="columns[5].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Sponsor') }}</th>
                                    <th v-show="columns[6].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Cripto') }}</th>
                                    <th v-show="columns[7].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('SSL') }}</th>
                                    <th v-show="columns[8].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Category') }}</th>
                                    <!-- <th v-show="columns[9].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Banners') }}</th>
                                        <th v-show="columns[10].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Links') }} menu</th> -->
                                    <th v-show="columns[9].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Obs') }}</th>
                                    <th v-show="columns[10].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">
                                        <div class="flex group">
                                            <span class="block">{{ $t('Example') }}</span>
                                            <TableSortButton column='example' :current="sort"
                                                @onClick='(column) => sort = column' />
                                        </div>
                                    </th>
                                    <th v-show="columns[11].visible"
                                        class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">
                                        <div class="flex group">
                                            <span class="block">{{ $t('Upload data') }}</span>
                                            <TableSortButton column='inserted_at' :current="sort"
                                                @onClick='(column) => sort = column' />
                                        </div>
                                    </th>

                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="(site, index) in sites.data" :key="index" class="bg-white">
                                    <td v-show="columns[0].visible" class="whitespace-nowrap px-6 py-4 text-sm">
                                        
                                        <span :data-tippy-content="site.sale_coin != coinStore.coin ? `${$filters.currency(site.sale / 100, coins[site.sale_coin])}` : null" class="relative flex space-x-2 items-center">
                                            <span v-if="site.sale_coin != coinStore.coin"  class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                            <span> {{ site.sale_coin != coinStore.coin ? '~' : null }} {{ $filters.currency((site.sale / coinStore.ratios[site.sale_coin]) / 100, coins[coinStore.coin]) }}</span>
                                        </span>
                                    </td>
                                    <td v-show="columns[1].visible" class="whitespace-nowrap px-6 py-4 text-sm">
                                        <a :href="route('sites.edit', site.id)"
                                            class="text-gray-500 hover:text-gray-900">
                                            {{ site.url }}
                                        </a>
                                    </td>
                                    <td v-show="columns[2].visible"
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.da ?? '-' }}
                                    </td>
                                    <td v-show="columns[3].visible"
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.dr ?? '-' }}
                                    </td>
                                    <td v-show="columns[4].visible"
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.gambling ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td v-show="columns[5].visible"
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.sponsor ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td v-show="columns[6].visible"
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.cripto ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td v-show="columns[7].visible"
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <div class="flex justify-center">
                                            <span
                                                :class="['block h-2 w-2 rounded-full', site.ssl ? 'bg-green-300' : 'bg-red-300']"></span>
                                        </div>
                                    </td>
                                    <td v-show="columns[8].visible"
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.category?.name ?? '-' }}
                                    </td>
                                    <!-- <td v-show="columns[9].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ site.banner ? 'Sim' : 'Não' }}
                                        </td>
                                        <td v-show="columns[10].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ site.menu ? 'Sim' : 'Não' }}
                                        </td> -->
                                    <td v-show="columns[9].visible"
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.obs ?? '-' }}
                                    </td>
                                    <td v-show="columns[10].visible"
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        -
                                    </td>
                                    <td v-show="columns[11].visible"
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.formatted_inserted_at }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <div class="flex space-x-2">
                                            <Link :href="route('sites.edit', site.id)"
                                                class="text-blue-500 hover:text-blue-700">
                                            <PencilSquareIcon class="h-5 w-5" />
                                            </Link>
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
    </AppLayout>
</template>
        