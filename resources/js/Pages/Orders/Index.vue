<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TableSortButton from "@/Components/TableSortButton.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { computed, onMounted, ref, watch } from "vue";
import unionBy from "lodash/unionBy";
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
    Switch,
    SwitchGroup,
    SwitchLabel,
} from "@headlessui/vue";

import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
    CloudArrowDownIcon,
    CloudArrowUpIcon,
} from "@heroicons/vue/20/solid";

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

import { debounce } from "debounce";
import { useTranslation } from "i18next-vue";

import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import AppSuspense from "../../Layouts/AppSuspense.vue";

const FilePond = vueFilePond(FilePondPluginFileValidateType);

const { t } = useTranslation();

const props = defineProps({
    title: String,
    orders: Object,
    statuses: Object,
    sites: Array,
    clients: Array,
    sellers: Array,
    importFailures: Object,
    importDiff: Number,
});

const links = computed(() => {
    const _links = props.orders.links;
    _links.shift();
    _links.pop();
    return _links;
});

const _defaultColumns = [
    { key: "url", label: t("Url"), visible: true },
    { key: "client", label: t("Client"), visible: true },
    { key: "seller", label: t("Seller"), visible: true },
    { key: "charged", label: t("Charged"), visible: true },
    { key: "paid", label: t("Paid"), visible: true },
    { key: "markup", label: t("Markup"), visible: true },
    { key: "comission", label: t("Comission"), visible: true },
    { key: "status", label: t("Status"), visible: true },
    { key: "created_at", label: t("Created at"), visible: true },
];

const _columns = localStorage.getItem("orders.index.columns")
    ? unionBy(
          JSON.parse(localStorage.getItem("orders.index.columns")),
          _defaultColumns,
          "key"
      )
    : _defaultColumns;

const columns = ref(_columns);

watch(
    columns,
    (n, o) => {
        localStorage.setItem(
            "orders.index.columns",
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

const sort = ref(params.sort ?? "url");

const filters = ref({
    // url: params["filter[url]"] ?? null,
    // gambling: params["filter[gambling]"] == 'true',
    // sponsor: params["filter[sponsor]"] == 'true',
});

// watch(sort, (n, o) => get());

watch(
    filters,
    debounce((n, o) => {
        get();
    }, 400),
    {
        deep: true,
    }
);

const get = async () => {
    Inertia.get(
        route("orders.index"),
        {
            sort: sort.value,
            filter: {
                // url: filters.value.url,
                // da: filters.value.da,
            },
        },
        {
            preserveState: true,
        }
    );
};

const importFilepond = ref(null);
const openImportDialog = ref(false);
const importFinished = ref(false);

const uploadOrders = (
    fieldName,
    file,
    metadata,
    load,
    error,
    progress,
    abort
) => {
    Inertia.post(
        route("orders.import"),
        {
            file: file,
        },
        {
            forceFormData: true,
            preserveScroll: true,
            preserveState: true,
            onError(err) {
                abort();
            },
            onSuccess(res) {
                load();
            },
            onProgress(p) {
                progress(true, p.loaded, p.total);
            },
            onFinish() {
                importFinished.value = true;
                // setTimeout(() => {
                //     importFilepond.value.removeFiles()
                // }, 600)
            },
        }
    );
};

watch(openImportDialog, (n, o) => {
    if (!n) {
        setTimeout(() => {
            importFinished.value = false;
            Inertia.reload({
                only: ["importFailures", "importDiff"],
            });
        }, 300);
    }
});
</script>

<template>
    <AppSuspense>
        <TransitionRoot as="template" :show="openImportDialog">
            <Dialog
                as="div"
                class="relative z-10"
                @close="openImportDialog = false"
            >
                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    />
                </TransitionChild>

                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div
                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                    >
                        <TransitionChild
                            as="template"
                            enter="ease-out duration-300"
                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enter-to="opacity-100 translate-y-0 sm:scale-100"
                            leave="ease-in duration-200"
                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        >
                            <DialogPanel
                                class="relative transform overflow-scroll rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 max-h-96 sm:p-6"
                            >
                                <h3
                                    class="text-center font-medium text-gray-900"
                                >
                                    {{ $t("Import orders") }}
                                </h3>

                                <div class="mt-4 mx-auto w-96">
                                    <file-pond
                                        ref="importFilepond"
                                        :server="{ process: uploadOrders }"
                                        :instantUpload="true"
                                        :allowRevert="false"
                                        accepted-file-types="text/csv"
                                        :dropOnPage="true"
                                        :dropOnElement="false"
                                        :dropValidation="true"
                                    />
                                </div>

                                <div v-if="importFinished" class="mt-8">
                                    <span class="block text-center"
                                        >{{ importDiff }} pedidos
                                        adicionados.</span
                                    >

                                    <div
                                        v-if="importFailures.length > 0"
                                        class="mt-4"
                                    >
                                        <div
                                            class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg"
                                        >
                                            <table
                                                class="min-w-full divide-y divide-gray-300"
                                            >
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th
                                                            scope="col"
                                                            class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                                        >
                                                            Linha
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l"
                                                        >
                                                            Coluna
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l"
                                                        >
                                                            Valor
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l"
                                                        >
                                                            Erro
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="divide-y divide-gray-200 bg-white"
                                                >
                                                    <tr
                                                        v-for="(
                                                            failure, index
                                                        ) in importFailures"
                                                        :key="index"
                                                    >
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-6"
                                                        >
                                                            {{ failure.row }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 border-l"
                                                        >
                                                            {{
                                                                failure.attribute
                                                            }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-2 py-2 text-sm text-gray-900 border-l"
                                                        >
                                                            {{
                                                                failure.values[
                                                                    failure
                                                                        .attribute
                                                                ] ?? "-"
                                                            }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 border-l"
                                                        >
                                                            {{
                                                                failure
                                                                    .errors[0]
                                                            }}
                                                        </td>
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

        <AppLayout :title="$t('Orders')">
            <template #uppermenu>
                <!-- <div class="flex w-full md:ml-0">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center" aria-hidden="true">
                            <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
                        </div>
                        <input v-model="filters.url" id="search" name="search"
                            class="block h-full w-full border-transparent py-2 pl-8 pr-3 text-gray-900 placeholder-gray-500 focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm"
                            :placeholder="$t('Search orders')" type="search" />
                    </div>
                </div> -->
            </template>

            <template #header>
                <div class="flex flex-col">
                    <div
                        class="w-full flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200"
                    >
                        <h2
                            class="font-semibold text-xl text-gray-800 leading-tight"
                        >
                            {{ $t("Orders") }}
                        </h2>

                        <div class="flex space-x-3">
                            <Link
                                :href="route('orders.create')"
                                class="flex max-w-xs items-center rounded-md bg-indigo-500 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 p-2 hover:bg-indigo-700"
                            >
                                <span
                                    class="px-1 text-sm font-medium text-white"
                                    >{{ $t("Add order") }}</span
                                >
                            </Link>

                            <button
                                @click="openImportDialog = true"
                                class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 p-2 hover:bg-gray-50"
                            >
                                <span
                                    class="ml-1 text-sm font-medium text-gray-700"
                                    >{{ $t("Import") }}</span
                                >
                                <CloudArrowUpIcon
                                    class="ml-2 h-5 w-5 flex-shrink-0 text-gray-400"
                                    aria-hidden="true"
                                />
                            </button>

                            <!-- <a :href="route('orders.export')"
                                class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 p-2 hover:bg-gray-50">
                                <span class="ml-1 text-sm font-medium text-gray-700">{{ $t('Export') }}</span>
                                <CloudArrowDownIcon class="ml-2 h-5 w-5 flex-shrink-0 text-gray-400"
                                    aria-hidden="true" />
                            </a> -->

                            <Menu as="div" class="relative">
                                <div class="hidden sm:block">
                                    <MenuButton
                                        class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 p-2 hover:bg-gray-50"
                                    >
                                        <span
                                            class="ml-1 text-sm font-medium text-gray-700"
                                            >{{ $t("Columns") }}</span
                                        >
                                        <ChevronDownIcon
                                            class="ml-1 h-5 w-5 flex-shrink-0 text-gray-400"
                                            aria-hidden="true"
                                        />
                                    </MenuButton>
                                </div>
                                <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    >
                                        <div
                                            v-for="(column, index) in columns"
                                            :key="index"
                                            class="px-4 py-2 relative flex"
                                        >
                                            <div class="flex items-center h-5">
                                                <input
                                                    v-model="column.visible"
                                                    :value="column.key"
                                                    :id="column.key"
                                                    :name="column.key"
                                                    type="checkbox"
                                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                                />
                                            </div>

                                            <div class="ml-3 text-sm">
                                                <label
                                                    :for="column.key"
                                                    class="font-medium text-gray-700"
                                                    >{{ column.label }}</label
                                                >
                                            </div>
                                        </div>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                </div>
            </template>

            <div>
                <div
                    class="sm:hidden border border-gray-200 rounded-lg overflow-hidden"
                >
                    <ul role="list" class="divide-y divide-gray-200">
                        <li v-for="(order, index) in orders.data" :key="index">
                            <Link
                                :href="route('orders.edit', order.id)"
                                class="block bg-white px-4 py-4 hover:bg-gray-50"
                            >
                                <span class="flex items-center space-x-4">
                                    <span
                                        class="flex flex-1 space-x-2 truncate"
                                    >
                                        <span class="text-sm text-gray-500">
                                            {{ order.url }}
                                        </span>
                                    </span>
                                    <ChevronRightIcon
                                        class="h-5 w-5 flex-shrink-0 text-gray-400"
                                        aria-hidden="true"
                                    />
                                </span>
                            </Link>
                        </li>
                    </ul>

                    <nav
                        class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3"
                        aria-label="Pagination"
                    >
                        <div class="flex flex-1 justify-between">
                            <Link
                                :href="orders.prev_page_url"
                                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500"
                            >
                                {{ $t("Previous") }}</Link
                            >
                            <Link
                                :href="orders.next_page_url"
                                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500"
                            >
                                {{ $t("Next") }}</Link
                            >
                        </div>
                    </nav>
                </div>

                <div class="hidden sm:block">
                    <div class="flex flex-col">
                        <div
                            class="min-w-full overflow-hidden overflow-x-auto align-middle border border-gray-200 sm:rounded-lg"
                        >
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            v-show="columns[0].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            {{ $t("Url") }}
                                        </th>
                                        <th
                                            v-show="columns[1].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            {{ $t("Client") }}
                                        </th>
                                        <th
                                            v-show="columns[2].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            {{ $t("Seller") }}
                                        </th>
                                        <th
                                            v-show="columns[3].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            {{ $t("Charged") }}
                                        </th>
                                        <th
                                            v-show="columns[4].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            {{ $t("Paid") }}
                                        </th>
                                        <th
                                            v-show="columns[5].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            {{ $t("Markup") }}
                                        </th>
                                        <th
                                            v-show="columns[6].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            {{ $t("Comission") }}
                                        </th>
                                        <th
                                            v-show="columns[7].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            {{ $t("Status") }}
                                        </th>
                                        <th
                                            v-show="columns[8].visible"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            {{ $t("Created at") }}
                                        </th>
                                        <th
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        ></th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-200 bg-white"
                                >
                                    <tr
                                        v-for="(order, index) in orders.data"
                                        :key="index"
                                        class="bg-white"
                                    >
                                        <td
                                            v-show="columns[0].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm"
                                        >
                                            <a
                                                :href="order.url"
                                                target="_blank"
                                                class="text-gray-500 hover:text-gray-900"
                                            >
                                                {{ order.url }}
                                            </a>
                                        </td>

                                        <td
                                            v-show="columns[1].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                        >
                                            {{ order.client?.name ?? "-" }}
                                        </td>

                                        <td
                                            v-show="columns[2].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                        >
                                            {{ order.seller?.name ?? "-" }}
                                        </td>

                                        <td
                                            v-show="columns[3].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                        >
                                            {{ order.formatted_charged ?? "-" }}
                                        </td>

                                        <td
                                            v-show="columns[4].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                        >
                                            {{ order.formatted_paid ?? "-" }}
                                        </td>

                                        <td
                                            v-show="columns[5].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                        >
                                            {{ order.formatted_markup ?? "-" }}
                                        </td>

                                        <td
                                            v-show="columns[6].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                        >
                                            {{
                                                order.formatted_comission ?? "-"
                                            }}
                                        </td>

                                        <td
                                            v-show="columns[7].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                        >
                                            {{ order.formatted_status ?? "-" }}
                                        </td>

                                        <td
                                            v-show="columns[8].visible"
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                        >
                                            {{
                                                new Date(
                                                    order.created_at
                                                ).toLocaleString()
                                            }}
                                        </td>

                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm"
                                        >
                                            <div
                                                class="flex justify-end space-x-2"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'orders.edit',
                                                            order.id
                                                        )
                                                    "
                                                    class="text-blue-500 hover:text-blue-700"
                                                >
                                                    <PencilSquareIcon
                                                        class="h-5 w-5"
                                                    />
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <nav
                            class="mt-6 flex items-center justify-between border-t border-gray-200 px-4 sm:px-0"
                        >
                            <div class="-mt-px flex w-0 flex-1">
                                <Link
                                    :href="orders.prev_page_url"
                                    class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                                >
                                    <ArrowLongLeftIcon
                                        class="mr-3 h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                    {{ $t("Previous") }}
                                </Link>
                            </div>
                            <div class="hidden md:-mt-px md:flex">
                                <Link
                                    v-for="(link, index) in links"
                                    :key="index"
                                    :href="link.url"
                                    :class="[
                                        link.active
                                            ? 'border-indigo-500 text-indigo-600'
                                            : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                                        'inline-flex items-center border-t-2  px-4 pt-4 text-sm font-medium ',
                                    ]"
                                >
                                    {{ link.label }}
                                </Link>
                            </div>
                            <div class="-mt-px flex w-0 flex-1 justify-end">
                                <Link
                                    :href="orders.next_page_url"
                                    class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                                >
                                    {{ $t("Next") }}
                                    <ArrowLongRightIcon
                                        class="ml-3 h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                </Link>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </AppLayout>
    </AppSuspense>
</template>
