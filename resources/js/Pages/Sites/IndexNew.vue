<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import TableSortButton from '@/Components/TableSortButton.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, onMounted, reactive, ref, watch } from 'vue'
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

import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
    CloudArrowDownIcon,
    CloudArrowUpIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/20/solid'

import {
    PencilSquareIcon,
    GlobeAltIcon,
    PlusCircleIcon,
    PlusIcon,
    InformationCircleIcon,
    TrashIcon,
    ArrowPathIcon,
} from '@heroicons/vue/24/outline'

import { debounce } from 'debounce';
import { useTranslation } from "i18next-vue";
import { useCoinStore } from '@/stores/coin'

import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import AppSuspense from '../../Layouts/AppSuspense.vue';

const FilePond = vueFilePond(FilePondPluginFileValidateType);

const coinStore = useCoinStore()
const { t } = useTranslation();

const props = defineProps({
    title: String,
    sites: Object,
    coins: Object,

    countries: Array,
    languages: Array,
    categories: Array,
    sellers: Array,

    importFailures: Object,
    importDiff: Number,

    pendingCount: Number,
    offersCount: Number,

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
    { key: 'gambling', label: t('Accepts gambling'), visible: true },
    { key: 'sponsor', label: t('Marked as sponsored'), visible: true },
    { key: 'cripto', label: t('Cripto'), visible: false },
    { key: 'ssl', label: t('SSL'), visible: false },
    { key: 'category', label: t('Category'), visible: false },
    { key: 'obs', label: t('Obs'), visible: true },
    { key: 'example', label: t('Example'), visible: false },
    { key: 'inserted_at', label: t('Upload date'), visible: false },
    { key: 'last_updated_at', label: t('Updated date'), visible: true },
];

const _columns =
    localStorage.getItem('sites.index.columns') ?
        unionBy(JSON.parse(localStorage.getItem('sites.index.columns')), _defaultColumns, 'key')
        : _defaultColumns

const columns = ref(_columns)

watch(columns, (n, o) => {
    localStorage.setItem('sites.index.columns', JSON.stringify(columns.value))
}, {
    deep: true,
})

const sort = ref(props.filters.sort)

const filters = reactive({
    // sellers filter
    url: props.filters.filter.url,
    sale: { from: props.filters.filter.sale.from, to: props.filters.filter.sale.to },
    da: { from: props.filters.filter.da.from, to: props.filters.filter.da.to },
    dr: { from: props.filters.filter.dr.from, to: props.filters.filter.dr.to },
    gambling: props.filters.filter.gambling,
    sponsor: props.filters.filter.sponsor,
    // recommended: props.filters.filter.recommended,
    // new: props.filters.filter.new,
    language_id: props.filters.filter.language_id,
    country_id: props.filters.filter.country_id,
    category_id: props.filters.filter.category_id,
    seller_id: props.filters.filter.seller_id,
})

watch(sort, (n, o) => get());

watch(() => ({ ...filters }), debounce((n, o) => {

    // if (n.new !== o.new) {
    //     sort.value = n.new === true ? 'new' : 'url';
    //     return;
    // }

    // if (n.recommended !== o.recommended) {
    //     sort.value = n.recommended === true ? 'recommended' : 'url';
    //     return;
    // }

    get()
}, 400), {
    deep: true,
})

const get = async () => {
    Inertia.get(route('sites.index'), {
        sort: sort.value,
        filter: {
            url: filters.url,
            sale: filters.sale,
            da: filters.da,
            dr: filters.dr,
            gambling: filters.gambling,
            sponsor: filters.sponsor,
            // ...(filters.recommended && {
            //     recommended: filters.recommended,
            // }),
            // ...(filters.new && {
            //     new: filters.new,
            // }),
            language_id: filters.language_id,
            country_id: filters.country_id,
            category_id: filters.category_id,
            seller_id: filters.seller_id,
        },
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const toggle = async (id) => {
    Inertia.post(route('sites.toggle', id), null, {
        preserveScroll: true,
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

const coinFormat = computed(() => props.coins[coinStore.coin])

onMounted(() => {
    setTimeout(() => {
        tippy('[data-tippy-content]');
    }, 500)
})


const expanded = ref([])

</script>
        
<template>
    <AppSuspense>
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
                                                            {{ failure.row }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 border-l">
                                                            {{ failure.attribute }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-2 py-2 text-sm text-gray-900 border-l">
                                                            {{
                                                                failure.values[failure.attribute] ?? '-'
                                                            }}</td>
                                                        <td
                                                            class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 border-l">
                                                            {{
                                                                failure.errors[0]
                                                            }}</td>
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

        <AppLayoutNew :title="$t('Sites')">
            <div>
                <div class="rounded-md bg-white px-5 py-6 shadow sm:px-6">
                    <div class="flex flex-col min-h-48">

                        <div class="flex items-center space-x-2">
                            <div class="w-10">
                                <MagnifyingGlassIcon class="h-8 w-8" />
                            </div>

                            <div>
                                <h2 class="text-xl font-bold leading-tight">{{ $t('Search websites') }}</h2>
                                <span class="block text-xs text-gray-400">{{ $t('Choose filters to start') }}</span>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-0">
                            <div class="col-span-1 flex flex-col">
                                <span class="flex items-center space-x-2 text-sm font-medium">
                                    <span class="block h-2 w-2 bg-green-500 rounded-full"></span>
                                    <span>{{ $t('Price range') }}</span>
                                </span>

                                <div class="mt-4 flex space-x-4">
                                    <div class="flex items-center">
                                        <label for="from_sale" class="text-xs text-gray-500 self-start text-right">{{
                                            $t('From') }}</label>
                                        <input v-model="filters.sale.from" v-money3="coinFormat" id="from_sale"
                                            name="from_sale" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>

                                    <div class="flex items-center">
                                        <label for="to_sale" class="text-xs text-gray-500 self-start text-right">{{ $t('To')
                                        }}</label>
                                        <input v-model="filters.sale.to" v-money3="coinFormat" id="to_sale" name="to_sale"
                                            type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-1 flex flex-col">
                                <span class="flex items-center space-x-2 text-sm font-medium">
                                    <span class="block h-2 w-2 bg-yellow-500 rounded-full"></span>
                                    <span>{{ $t('Domain authority range') }}</span>
                                </span>

                                <div class="mt-4 flex space-x-4">
                                    <div class="flex items-center">
                                        <label for="from_da" class="text-xs text-gray-500 self-start text-right">{{
                                            $t('From') }}</label>
                                        <input v-model="filters.da.from" id="from_da" name="from_da" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>

                                    <div class="flex items-center">
                                        <label for="to_da" class="text-xs text-gray-500 self-start text-right">{{ $t('To')
                                        }}</label>
                                        <input v-model="filters.da.to" id="to_da" name="to_da" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-1 flex flex-col">
                                <span class="flex items-center space-x-2 text-sm font-medium">
                                    <span class="block h-2 w-2 bg-purple-500 rounded-full"></span>
                                    <span>{{ $t('Domain rating range') }}</span>
                                </span>

                                <div class="mt-4 flex space-x-4">
                                    <div class="flex items-center">
                                        <label for="from_dr" class="text-xs text-gray-500 self-start text-right">{{
                                            $t('From') }}</label>
                                        <input v-model="filters.dr.from" id="from_dr" name="from_dr" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>

                                    <div class="flex items-center">
                                        <label for="to_dr" class="text-xs text-gray-500 self-start text-right">{{ $t('To')
                                        }}</label>
                                        <input v-model="filters.dr.to" id="to_dr" name="to_dr" type="text"
                                            class="ml-2 w-[7rem] bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-3"><hr class="my-5"></div>

                            <div class="col-span-1 flex flex-col">
                                <span class="flex items-center space-x-2 text-sm font-medium">
                                    <GlobeAltIcon class="h-5 w-5" />
                                    <span>{{ $t('Language') }}</span>
                                </span>

                            <div class="mt-4 flex">
                                <div class="grid grid-rows-3 grid-flow-col gap-x-4 gap-y-2">
                                    <div v-for="(language, index) in languages" :key="index"
                                        class="relative flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input :value="language.id" v-model="filters.language_id"
                                                    :id="`language-${language.id}`" :name="`language-${language.id}`"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                            </div>
                                            <div class="ml-2 text-sm">
                                                <label :for="`language-${language.id}`"
                                                    class="font-medium text-gray-700 whitespace-nowrap">{{ language.name
                                                    }}</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-span-2 flex flex-col">
                                <span class="flex items-center space-x-2 text-sm font-medium">
                                    <GlobeAltIcon class="h-5 w-5" />
                                    <span>{{ $t('Country') }}</span>
                                </span>

                                <div class="mt-4 flex">
                                    <div class="grid grid-rows-3 grid-flow-col gap-x-4 gap-y-2">
                                        <div v-for="(country, index) in countries" :key="index"
                                            class="relative flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input :value="country.id" v-model="filters.country_id"
                                                    :id="`country-${country.id}`" :name="`country-${country.id}`"
                                                    type="checkbox"
                                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                            </div>
                                            <div class="ml-2 text-sm">
                                                <label :for="`country-${country.id}`"
                                                    class="font-medium text-gray-700 whitespace-nowrap">{{ country.name
                                                    }}</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-span-3"><hr class="my-5"></div>

                            <div class="col-span-1">
                                <span class="text-sm font-medium">{{ $t('Category') }}</span>

                                <select v-model="filters.category_id" id="category_id" name="category_id"
                                    class="mt-4 ml-2 w-full bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0">
                                    <option :value="null">{{ $t('All') }}</option>
                                    <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.title }}</option>
                                </select>
                            </div>

                            <div class="col-span-1">
                                <span class="text-sm font-medium">{{ $t('Seller') }}</span>

                                <select v-model="filters.seller_id" id="seller_id" name="seller_id"
                                    class="mt-4 ml-2 w-full bg-gray-100 text-sm font-medium border border-gray-300 rounded-md focus:ring-0">
                                    <option :value="null">{{ $t('All') }}</option>
                                    <option v-for="(seller, index) in sellers" :key="index" :value="seller.id">{{ seller.name }}</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-5">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-24">

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                <SwitchGroup as="div" class="col-span-1 px-4 flex justify-end items-center">
                                    <SwitchLabel as="span" class="flex h-full">
                                        <span class="text-sm font-medium self-center text-right">{{ $t('Accepts gambling')
                                        }}</span>
                                    </SwitchLabel>

                                    <Switch v-model="filters.gambling"
                                        :class="[filters.gambling ? 'bg-blue-600' : 'bg-gray-200', 'ml-2 relative inline-flex h-4 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']">
                                        <span aria-hidden="true"
                                            :class="[filters.gambling ? 'translate-x-5' : '-translate-x-1', '-translate-y-1 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out border border-gray-300']" />
                                    </Switch>
                                </SwitchGroup>

                                <SwitchGroup as="div" class="col-span-1 px-4 flex justify-end items-center">
                                    <SwitchLabel as="span" class="flex h-full">
                                        <span class="text-sm font-medium self-center text-right">{{ $t('Marked as sponsored') }}</span>
                                    </SwitchLabel>

                                <Switch v-model="filters.sponsor"
                                    :class="[filters.sponsor ? 'bg-blue-600' : 'bg-gray-200', 'ml-2 relative inline-flex h-4 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']">
                                    <span aria-hidden="true"
                                        :class="[filters.sponsor ? 'translate-x-5' : '-translate-x-1', '-translate-y-1 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out border border-gray-300']" />
                                </Switch>
                            </SwitchGroup>

                                <SwitchGroup as="div" class="col-span-1 px-4 flex justify-end items-center">
                                    <SwitchLabel as="span" class="flex h-full">
                                        <span class="text-sm font-medium self-center text-right">{{ $t('New') }}</span>
                                    </SwitchLabel>

                                    <Switch v-model="filters.new"
                                        :class="[filters.new ? 'bg-blue-600' : 'bg-gray-200', 'ml-2 relative inline-flex h-4 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']">
                                        <span aria-hidden="true"
                                            :class="[filters.new ? 'translate-x-5' : '-translate-x-1', '-translate-y-1 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out border border-gray-300']" />
                                    </Switch>
                                </SwitchGroup>
                            </div>

                            <div class="w-full">
                                <label for="search" class="text-sm font-medium">{{ $t('...or just find by name:') }}</label>
                                <div class="mt-1 relative  text-gray-400 focus-within:text-gray-600">
                                    <div class="pointer-events-none absolute inset-y-0 left-2 flex items-center"
                                        aria-hidden="true">
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

                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Showing results:') }}&nbsp;{{ sites.total }}&nbsp;{{ $t('sites found') }}</h2>
                            </div>

                            <div class="flex space-x-3">
                                <Link :href="route('sites.create')"
                                    class="flex max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700">
                                <span class="px-1 text-sm font-medium text-white">{{ $t('Add site') }}</span>
                                </Link>

                                <button @click="openImportDialog = true"
                                    class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-gray-50">
                                    <span class="ml-1 text-sm font-medium text-gray-700">{{ $t('Import') }}</span>
                                    <CloudArrowUpIcon class="ml-2 h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                                </button>

                                <a :href="route('sites.export')"
                                    class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-gray-50">
                                    <span class="ml-1 text-sm font-medium text-gray-700">{{ $t('Export') }}</span>
                                    <CloudArrowDownIcon class="ml-2 h-5 w-5 flex-shrink-0 text-gray-400"
                                        aria-hidden="true" />
                                </a>
                            </div>
                        </div>

                        <div class="flex flex-col mt-5">
                            <Link v-if="pendingCount > 0" :href="route('sites.index', { 'filter[of_status]': 'PENDING' })"
                                class="w-fit text-sm font-medium text-blue-500 hover:text-blue-700">{{ pendingCount }} sites
                                aguardando aprovação.</Link>

                            <Link v-if="offersCount > 0" :href="route('sites.offers')"
                                class="w-fit text-sm font-medium text-blue-500 hover:text-blue-700"> 
                                {{ offersCount > 1 ? `${offersCount} novas ofertas de valor` : `${offersCount} nova oferta de valor` }}
                            </Link>
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
                            class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle border border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th v-show="columns[0].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Price') }}</span>
                                                <TableSortButton column='sale' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>

                                        <th v-show="columns[1].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Domain') }}</span>
                                                <TableSortButton column='url' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[2].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('DA') }}</span>
                                                <TableSortButton column='da' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>

                                        <th v-show="columns[3].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('DR') }}</span>
                                                <TableSortButton column='dr' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[4].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Accepts gambling') }}</th>
                                        <th v-show="columns[5].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Marked as sponsored') }}</th>
                                        <th v-show="columns[6].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Cripto') }}</th>
                                        <th v-show="columns[7].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('SSL') }}</th>
                                        <th v-show="columns[8].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Category') }}</th>
                                        <!-- <th v-show="columns[9].visible"
                                                class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col">{{ $t('Banners') }}</th>
                                            <th v-show="columns[10].visible"
                                                class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col">{{ $t('Links') }} menu</th> -->
                                        <th v-show="columns[9].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Obs') }}</th>
                                        <th v-show="columns[10].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Example') }}</span>
                                                <TableSortButton column='example' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[11].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Upload date') }}</span>
                                                <TableSortButton column='inserted_at' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>
                                        <th v-show="columns[12].visible"
                                            class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Updated date') }}</span>
                                                <TableSortButton column='last_updated_at' :current="sort"
                                                    @onClick='(column) => sort = column' />
                                            </div>
                                        </th>

                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">

                                    <template v-for="(site, index) in sites.data" :key="index">
                                        <tr :class="['bg-white border-gray-200', { 'border-b': index < sites.data.length -1 && !expanded.includes(index), 'border-t': expanded.includes(index -1), 'bg-red-50': site.deleted_at !== null, }]">
                                            <td v-show="columns[0].visible" class="whitespace-nowrap px-4 py-4 text-sm">
                                                <span
                                                    :data-tippy-content="site.sale_coin != coinStore.coin ? `${$filters.currency(site.sale / 100, coins[site.sale_coin])}` : null"
                                                    class="relative flex space-x-2 items-center">
                                                    <span v-if="site.sale_coin != coinStore.coin"
                                                        class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                    <span>
                                                        {{ site.sale_coin != coinStore.coin ? '~ ' : null }}

                                                        {{ $filters.currency(Math.ceil((site.sale / coinStore.ratios[site.sale_coin]) / 100), { ...coins[coinStore.coin], precision: 0, }) }}
                                                    </span>
                                                </span>
                                            </td>
                                            <td v-show="columns[1].visible" class="whitespace-nowrap px-4 py-4 text-sm">
                                                <Link :href="route('sites.edit', site.id)" :class="[{
                                                    'text-gray-500 hover:text-gray-900': site.deleted_at === null,
                                                    'text-red-500 hover:text-red-700': site.deleted_at !== null,
                                                }]">
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
                                            <td v-show="columns[12].visible"
                                                class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                                {{ site.formatted_updated_at }}
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-sm">
                                                <div class="flex justify-end space-x-3">
                                                    <button v-if="site.types.length > 0" type="button" @click="expanded.includes(index) ? expanded = expanded.filter(k => k != index) :  expanded.push(index)"
                                                        class="text-gray-500 hover:text-gray-700">
                                                        <InformationCircleIcon class="h-5 w-5" />
                                                    </button>

                                                    <Link :href="route('sites.edit', site.id)"
                                                        class="text-blue-500 hover:text-blue-700">
                                                        <PencilSquareIcon class="h-5 w-5" />
                                                    </Link>

                                                    <button @click="toggle(site.id)"
                                                        :class="[site.deleted_at === null ? 'text-red-500 hover:text-red-700' : 'text-green-500 hover:text-green-700']">
                                                        <TrashIcon v-if="site.deleted_at === null" class="h-5 w-5" />
                                                        <ArrowPathIcon v-else class="h-5 w-5" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr v-show="site.types.length > 0 && expanded.includes(index)">
                                            <td :colspan="columns.filter(c => c.visible).length + 1" class="px-4 py-4 text-sm">

                                                <ul class="space-y-2">

                                                    <li v-for="(type, j) in site.types" :key="j">
                                                        <!-- <span
                                                            :data-tippy-content="type.pivot.cost_coin != coinStore.coin ? `${$filters.currency(type.pivot.cost / 100, coins[type.pivot.cost_coin])}` : null"
                                                            class="relative flex space-x-2 items-center">
                                                            <span v-if="type.pivot.cost_coin != coinStore.coin"
                                                                class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                            <span>
                                                                {{ type.pivot.cost_coin != coinStore.coin ? '~ ' : null }}

                                                                {{ $filters.currency(Math.ceil((type.pivot.cost / coinStore.ratios[type.pivot.cost_coin]) / 100), { ...coins[coinStore.coin], precision: 0, }) }}
                                                            </span>
                                                        </span> -->
                                                        
                                                        <div class="flex space-x-2">
                                                            <span
                                                                :data-tippy-content="type.pivot.sale_coin != coinStore.coin ? `${$filters.currency(type.pivot.sale / 100, coins[type.pivot.sale_coin])}` : null"
                                                                class="relative flex space-x-2 items-center">
                                                                <span v-if="type.pivot.sale_coin != coinStore.coin"
                                                                    class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                                <span>
                                                                    {{ type.pivot.sale_coin != coinStore.coin ? '~ ' : null }}

                                                                    {{ $filters.currency(Math.ceil((type.pivot.sale / coinStore.ratios[type.pivot.sale_coin]) / 100), { ...coins[coinStore.coin], precision: 0, }) }}
                                                                </span>
                                                            </span>

                                                            <span>for {{ type.name }} links</span>
                                                        </div>
                                                    </li>

                                                </ul>

                                            </td>
                                        </tr>
                                    </template>
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
        </AppLayoutNew>
    </AppSuspense>
</template>
        