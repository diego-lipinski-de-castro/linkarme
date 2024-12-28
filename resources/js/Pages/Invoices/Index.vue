<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<script setup>
import AppLayoutNew from "@/Layouts/AppLayoutNew.vue";
import TableSortButton from "@/Components/TableSortButton.vue";
import { Link, useForm } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from "vue";
import unionBy from "lodash/unionBy";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
    CloudArrowDownIcon,
    CloudArrowUpIcon,
} from "@heroicons/vue/20/solid";

import {
    ArrowTopRightOnSquareIcon,
    PaperClipIcon
} from '@heroicons/vue/24/outline'

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
} from "@heroicons/vue/24/outline";
import {
    BanknotesIcon,
    BuildingOfficeIcon,
    CheckCircleIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/20/solid";

import { useTranslation } from "i18next-vue";

import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import AppSuspense from "../../Layouts/AppSuspense.vue";
import { watchDebounced } from "@vueuse/core";
import { useCoinStore } from '@/stores/coin';
import VueMultiselect from 'vue-multiselect';
import VueTailwindDatepicker from "vue-tailwind-datepicker";

const FilePond = vueFilePond(FilePondPluginFileValidateType);

const coinStore = useCoinStore()
const { t } = useTranslation();

const props = defineProps({
    title: String,
    invoices: Object,
    clients: Array,
    coins: Object,
});

const links = computed(() => {
    const _links = props.invoices.links;
    _links.shift();
    _links.pop();
    return _links;
});

const _defaultColumns = [
    { key: "url", label: t("Portal"), visible: true },
    { key: "link", label: t("Link"), visible: true },
    { key: "client", label: t("Client"), visible: true },
    { key: "seller", label: t("Seller"), visible: true },
    { key: "charged", label: t("Charged"), visible: true },
    { key: "paid", label: t("Paid"), visible: true },
    { key: "markup", label: t("Markup"), visible: true },
    { key: "comission", label: t("Comission"), visible: true },
    { key: "status", label: t("Status"), visible: true },
    { key: "created_at", label: t("Created at"), visible: true },
];

const _columns = localStorage.getItem("invoices.index.columns")
    ? unionBy(
        JSON.parse(localStorage.getItem("invoices.index.columns")),
        _defaultColumns,
        "key"
    )
    : _defaultColumns;

const columns = ref(_columns);

watch(
    columns,
    (n, o) => {
        localStorage.setItem(
            "invoices.index.columns",
            JSON.stringify(columns.value)
        );
    },
    {
        deep: true,
    }
);

const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});

const sort = ref(params.sort ?? "-created_at");

const filters = ref({
    search: params["filter[search]"] ?? null,
    client: params["filter[client]"] ?? null,
    created_at: params["filter[created_at]"] ?? [],
});

const formatter = ref({
    date: 'DD/MM/YYYY',
    month: 'MM',
});

watchDebounced(
    filters,
    (n, o) => get(),
    {
        debounce: 400,
        deep: true,
    }
);

const get = async () => {
    Inertia.get(
        route("invoices.index"),
        {
            sort: sort.value,
            filter: filters.value,
        },
        {
            preserveState: true,
        }
    );
};
</script>

<template>
    <AppSuspense>

        <AppLayoutNew :title="$t('Invoices')">
            <div class="rounded-md bg-white px-5 py-6 shadow sm:px-6">
                <div class="flex flex-col">

                    <div class="flex justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-10">
                                <MagnifyingGlassIcon class="h-8 w-8" />
                            </div>

                            <div>
                                <h2 class="text-xl font-bold leading-tight">{{ $t('Search invoices') }}</h2>
                                <span class="block text-xs text-gray-400">{{ $t('Choose filters to start') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-3 md:gap-y-0">

                        <div class="col-span-1">
                            <label for="search" class="text-sm font-medium">{{ $t('Find by number:')
                                }}</label>
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
                            <label class="text-sm font-medium">{{ $t('Filter by client') }}</label>

                            <VueMultiselect key="client" class="mt-1 ml-2" placeholder="Select..." v-model="filters.client"
                                track-by="value" label="name" :options="clients.map((client) => ({
                                    name: client.name,
                                    value: client.id,
                                }))" :multiple="false" :searchable="true" :close-on-select="true" selectLabel=""
                                deselectLabel="" :showLabels="false">

                                <template #placeholder>
                                    <span class="text-gray-500">{{ $t('Select...') }}</span>
                                </template>

                            </VueMultiselect>
                        </div>

                        <div class="col-span-1">
                            <label class="text-sm font-medium">{{ $t('Filter by status') }}</label>

                            <VueMultiselect class="mt-1 ml-2" placeholder="Select..." v-model="filters.status"
                                track-by="value" label="name" :options="[
                                    { name: 'Aguardando', value: 'WAITING' },
                                    { name: 'Produção do artigo', value: 'PRODUCTION' },
                                    { name: 'Enviado para vendedor', value: 'SUBMITTED' },
                                    { name: 'Aguardando publicação', value: 'PROCESSING' },
                                    { name: 'Publicado', value: 'PUBLISHED' },
                                    { name: 'Invoice', value: 'INVOICE' },
                                    { name: 'Finalizado', value: 'COMPLETED' },
                                ]" :multiple="false" :searchable="false" :close-on-select="true" selectLabel=""
                                deselectLabel="" :showLabels="false">

                                <template #placeholder>
                                    <span class="text-gray-500">{{ $t('Select...') }}</span>
                                </template>

                            </VueMultiselect>
                        </div>

                        <div class="col-span-1">
                            <label class="text-sm font-medium">{{ $t('Date') }}</label>
                            <div class="mt-1 ml-2">
                                <vue-tailwind-datepicker v-model="filters.created_at" i18n="pt-br" :formatter="formatter" />
                            </div>
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
                                    invoices.total }}&nbsp;{{ $t('invoices found') }}</h2>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <div class="sm:hidden border border-gray-200 rounded-lg overflow-hidden">
                        <ul role="list" class="divide-y divide-gray-200">
                            <li v-for="(invoice, index) in invoices.data" :key="index">
                                <Link :href="route('invoices.edit', invoice.id)"
                                    class="block bg-white px-4 py-4 hover:bg-gray-50">
                                <span class="flex items-center space-x-4">
                                    <span class="flex flex-1 space-x-2 truncate">
                                        <span class="text-sm text-gray-500">
                                            {{ invoice.url }}
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
                                <Link :href="invoices.prev_page_url"
                                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                                {{ $t("Previous") }}</Link>
                                <Link :href="invoices.next_page_url"
                                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                                {{ $t("Next") }}</Link>
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
                                                {{ $t("Number") }}
                                            </th>

                                            <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col">
                                                {{ $t("Client") }}
                                            </th>

                                            <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col">
                                                {{ $t("Status") }}
                                            </th>
                                            <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col">
                                                {{ $t("Created at") }}
                                            </th>
                                            <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                                scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="(invoice, index) in invoices.data" :key="index" class="bg-white">
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                #{{ invoice.number }}
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                {{ invoice.client?.name ?? "-" }}
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                {{ invoice.formatted_status ?? "-" }}
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                {{
                                                    new Date(
                                                        invoice.created_at
                                                    ).toLocaleString()
                                                }}
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                <div class="flex justify-end space-x-2">
                                                    <Link :href="route(
                                                        'invoices.edit',
                                                        invoice.id
                                                    )
                                                        " class="text-blue-500 hover:text-blue-700">
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
                                    <Link :href="invoices.prev_page_url"
                                        class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                    <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                                    {{ $t("Previous") }}
                                    </Link>
                                </div>
                                <div class="hidden md:-mt-px md:flex">
                                    <Link v-for="(link, index) in links" :key="index" :href="link.url" :class="[
                                        link.active
                                            ? 'border-blue-500 text-blue-600'
                                            : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                                        'inline-flex items-center border-t-2  px-4 pt-4 text-sm font-medium ',
                                    ]">
                                    {{ link.label }}
                                    </Link>
                                </div>
                                <div class="-mt-px flex w-0 flex-1 justify-end">
                                    <Link :href="invoices.next_page_url"
                                        class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                    {{ $t("Next") }}
                                    <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                                    </Link>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayoutNew>
    </AppSuspense>
</template>
