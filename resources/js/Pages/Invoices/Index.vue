<script setup>
import AppLayoutNew from "@/Layouts/AppLayoutNew.vue";
import TableSortButton from "@/Components/TableSortButton.vue";
import { Inertia } from "@inertiajs/inertia";
import { Link, useForm } from '@inertiajs/inertia-vue3';
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
import { useCoinStore } from '@/stores/coin'

const FilePond = vueFilePond(FilePondPluginFileValidateType);

const coinStore = useCoinStore()
const { t } = useTranslation();

const props = defineProps({
    title: String,
    invoices: Object,
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
            filter: {
            },
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

                    <div class="flex justify-between items-center space-x-2">
                        <h2 class="text-xl font-bold leading-tight">{{ $t('Invoices') }}</h2>

                        <div class="flex space-x-3">
                            <Link :href="route('invoices.create')"
                                class="flex max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 transition-all">
                            <span class="px-1 text-sm font-medium text-white">{{ $t("Add invoice") }}</span>
                            </Link>
                        </div>
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
        </AppLayoutNew>
    </AppSuspense>
</template>
