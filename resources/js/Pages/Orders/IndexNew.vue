<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<script setup>
import AppLayoutNew from "@/Layouts/AppLayoutNew.vue";
import { Inertia } from "@inertiajs/inertia";
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { computed, ref, watch } from "vue";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

import {
    PencilSquareIcon,
    PaperClipIcon,
} from "@heroicons/vue/24/outline";
import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
    CloudArrowUpIcon,
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
import { getOrderStatusColor } from '@/utils'
import VueMultiselect from 'vue-multiselect'

const FilePond = vueFilePond(FilePondPluginFileValidateType);

const coinStore = useCoinStore()
const { t } = useTranslation();

const props = defineProps({
    title: String,
    orders: Object,
    statuses: Object,
    clients: Array,
    sellers: Array,
    importFailures: Object,
    importDiff: Number,
    sites: Object,
    coins: Object,
});

const links = computed(() => {
    const _links = props.orders.links;
    _links.shift();
    _links.pop();
    return _links;
});

const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});

// const sort = ref(params.sort ?? "url");

const filters = ref({
    search: params["filter[search]"] ?? null,
    status: params["filter[status]"] ?? null,
});

// watch(sort, (n, o) => get());

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
        route("orders.index"),
        {
            // sort: sort.value,
            filter: {
                search: filters.value.search,
                status: filters.value.status,
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

const form = useForm({
    list: '',
})

const list = ref([])

const submit = () => {
    form.post(route('orders.go'), {
        preserveScroll: true,
        onSuccess: (res) => {
            Inertia.reload({
                only: ["sites"],
            });

            openOrderDialog.value = false
            openSitesDialog.value = true

            list.value = res.props.sites

            form.reset();
        }
    });
}

const openOrderDialog = ref(false)
const openSitesDialog = ref(false)

const invoiceForm = useForm({
    selected: [],
})

const submitInvoice = () => {
    invoiceForm.post(route('invoices.store'), {
        preserveScroll: true,
        preserveState: true,
    })
}

const canSelect = (order) => {
    if(invoiceForm.selected.length === 0) {
        return true;
    }

    const _order = props.orders.data.find(o => o.id === invoiceForm.selected[0])
    
    if (order.client_id !== _order.client_id) {
        return false;
    }

    return order.invoice === null;
}
</script>

<template>
    <AppSuspense>
        <TransitionRoot as="template" :show="openImportDialog">
            <Dialog as="div" class="relative z-10" @close="openImportDialog = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
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
                                <h3 class="text-center font-medium text-gray-900">
                                    {{ $t("Import orders") }}
                                </h3>

                                <div class="mt-4 mx-auto w-96">
                                    <file-pond ref="importFilepond" :server="{ process: uploadOrders }"
                                        :instantUpload="true" :allowRevert="false" accepted-file-types="text/csv"
                                        :dropOnPage="true" :dropOnElement="false" :dropValidation="true" />
                                </div>

                                <div v-if="importFinished" class="mt-8">
                                    <span class="block text-center">{{ importDiff }} pedidos
                                        adicionados.</span>

                                    <div v-if="importFailures.length > 0" class="mt-4">
                                        <div
                                            class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col"
                                                            class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                            Linha
                                                        </th>
                                                        <th scope="col"
                                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l">
                                                            Coluna
                                                        </th>
                                                        <th scope="col"
                                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l">
                                                            Valor
                                                        </th>
                                                        <th scope="col"
                                                            class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 border-l">
                                                            Erro
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 bg-white">
                                                    <tr v-for="(
                                                            failure, index
                                                        ) in importFailures" :key="index">
                                                        <td
                                                            class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">
                                                            {{ failure.row }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 border-l">
                                                            {{
                                                                failure.attribute
                                                            }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-2 py-2 text-sm text-gray-900 border-l">
                                                            {{
                                                                failure.values[
                                                                failure
                                                                    .attribute
                                                                ] ?? "-"
                                                            }}
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 border-l">
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

        <TransitionRoot as="template" :show="openOrderDialog">
            <Dialog as="div" class="relative z-10" @close="openOrderDialog = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
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
                                <h3 class="font-medium text-gray-900">
                                    {{ $t("Quick order") }}
                                </h3>

                                <div class="mt-4 mx-auto w-96">
                                    <div>
                                        <InputLabel for="list" :value="$t('Site list')" />
                                        <textarea id="list" v-model="form.list" rows="5"
                                            class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>
                                        <InputError class="mt-2" :message="form.errors.list" />
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button @click="submit" type="button" :disabled="form.processing"
                                        class="mt-4 max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 disabled:opacity-50">
                                        <span class="px-1 text-sm font-medium text-white">{{ $t("Go") }}</span>
                                    </button>
                                </div>

                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <TransitionRoot as="template" :show="openSitesDialog">
            <Dialog as="div" class="relative z-10" @close="openSitesDialog = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
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
                                class="relative transform overflow-scroll rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:p-6">
                                <h3 class="font-medium text-gray-900">
                                    {{ $t("Quick order") }}
                                </h3>

                                <div class="mt-4 mx-auto">
                                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        {{ $t('Portal') }}</th>
                                                    <th scope="col"
                                                        class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        {{ $t('Vendedor') }}</th>
                                                    <th scope="col"
                                                        class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        {{ $t('Valor de compra') }}</th>
                                                    <th scope="col"
                                                        class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        {{ $t('Valor de venda') }}</th>
                                                    <th scope="col"
                                                        class="relative whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-0">
                                                        <span class="sr-only">Edit</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                <tr v-for="(site, url) in list" :key="url">
                                                    <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500"> {{
                                                        url }}</td>

                                                    <td
                                                        class="whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        {{ site === null ? '-' : site.seller?.name }}
                                                    </td>

                                                    <td
                                                        class="whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <span v-if="site === null">-</span>
                                                        <span v-else>
                                                            <span
                                                                :data-tippy-content="site.cost_coin != coinStore.coin ? `${$filters.currency(site.cost / 100, coins[site.cost_coin])}` : null"
                                                                class="relative flex space-x-2 items-center">
                                                                <span v-if="site.cost_coin != coinStore.coin"
                                                                    class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                                <span>
                                                                    {{ site.cost_coin != coinStore.coin ? '~ ' : null }}
                                                                    {{ $filters.currency(Math.ceil((site.cost /
                                                                        coinStore.ratios[site.cost_coin]) / 100), {
                                                                    ...coins[coinStore.coin], precision: 0, }) }}
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </td>

                                                    <td
                                                        class="whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <span v-if="site === null">-</span>
                                                        <span v-else>
                                                            <span
                                                                :data-tippy-content="site.sale_coin != coinStore.coin ? `${$filters.currency(site.sale / 100, coins[site.sale_coin])}` : null"
                                                                class="relative flex space-x-2 items-center">
                                                                <span v-if="site.sale_coin != coinStore.coin"
                                                                    class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                                <span>
                                                                    {{ site.sale_coin != coinStore.coin ? '~ ' : null }}
                                                                    {{ $filters.currency(Math.ceil((site.sale /
                                                                        coinStore.ratios[site.sale_coin]) / 100), {
                                                                    ...coins[coinStore.coin], precision: 0, }) }}
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </td>

                                                    <td
                                                        class="relative whitespace-nowrap px-3 py-2 text-right text-sm font-medium sm:pr-0">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-end space-x-2">
                                    <button type="button"
                                        class="flex max-w-xs items-center rounded-md text-blue-500 border border-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-50 transition-all">
                                        <span class="px-1 text-sm font-medium">{{ $t("Copy") }}</span>
                                    </button>

                                    <button
                                        class="flex max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 transition-all">
                                        <span class="px-1 text-sm font-medium text-white">{{ $t("Add order") }}</span>
                                    </button>

                                    <button type="button"
                                        class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 p-2 hover:bg-gray-50 transition-all">
                                        <span class="ml-1 text-sm font-medium text-gray-700">{{ $t("Generate invoice")
                                            }}</span>
                                        <PaperClipIcon class="ml-2 h-5 w-5 flex-shrink-0 text-gray-400"
                                            aria-hidden="true" />
                                    </button>
                                </div>

                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <AppLayoutNew :title="$t('Orders')">
            <div class="rounded-md bg-white px-5 py-6 shadow sm:px-6">
                <div class="flex flex-col">

                    <div class="flex justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-10">
                                <MagnifyingGlassIcon class="h-8 w-8" />
                            </div>

                            <div>
                                <h2 class="text-xl font-bold leading-tight">{{ $t('Search orders') }}</h2>
                                <span class="block text-xs text-gray-400">{{ $t('Choose filters to start') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-3 md:gap-y-0">

                        <div class="col-span-full md:col-span-2">
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

                    </div>

                </div>
            </div>

            <div class="mt-5">

                <div class="flex flex-col">

                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('Showing results:')
                                }}&nbsp;{{
                                    orders.total }}&nbsp;{{ $t('orders found') }}</h2>
                        </div>

                        <div class="flex space-x-3">
                            <button @click="openOrderDialog = true"
                                class="flex max-w-xs items-center rounded-md text-blue-500 border border-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-50 transition-all">
                                <span class="px-1 text-sm font-medium">{{ $t("Quick order") }}</span>
                            </button>

                            <Link :href="route('orders.create')"
                                class="flex max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 transition-all">
                            <span class="px-1 text-sm font-medium text-white">{{ $t("Add order") }}</span>
                            </Link>

                            <button @click="openImportDialog = true"
                                class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 p-2 hover:bg-gray-50 transition-all">
                                <span class="ml-1 text-sm font-medium text-gray-700">{{ $t("Import") }}</span>
                                <CloudArrowUpIcon class="ml-2 h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                            </button>

                            <!-- <a :href="route('orders.export')"
                                class="flex max-w-xs items-center rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-gray-50">
                                <span class="ml-1 text-sm font-medium text-gray-700">{{ $t('Export') }}</span>
                                <CloudArrowDownIcon class="ml-2 h-5 w-5 flex-shrink-0 text-gray-400"
                                    aria-hidden="true" />
                            </a> -->
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <div class="sm:hidden border border-gray-200 rounded-lg overflow-hidden">
                        <ul role="list" class="divide-y divide-gray-200">
                            <li v-for="(order, index) in orders.data" :key="index">
                                <Link :href="route('orders.edit', order.id)"
                                    class="block bg-white px-4 py-4 hover:bg-gray-50">
                                <span class="flex items-center space-x-4">
                                    <span class="flex flex-1 space-x-2 truncate">
                                        <span class="text-sm text-gray-500">
                                            {{ order.url }}
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
                                <Link :href="orders.prev_page_url"
                                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                                {{ $t("Previous") }}</Link>
                                <Link :href="orders.next_page_url"
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
                                            <th
                                                class="bg-gray-50 pl-4 py-3 text-left text-sm font-semibold text-gray-900">
                                            </th>
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
                                                {{ $t("Invoice") }}
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
                                        <tr v-for="(order, index) in orders.data" :key="index" class="bg-white">
                                            <td class="pl-4 py-4">
                                                <div v-if="canSelect(order)" class="w-fit">
                                                    <input v-model="invoiceForm.selected" :value="order.id"
                                                        type="checkbox"
                                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" />
                                                </div>
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                #{{ order.number }}
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                {{ order.client?.name ?? "-" }}
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                <Link v-if="order.invoice"
                                                    :href="route('invoices.edit', order.invoice.id)"
                                                    class="text-blue-500 hover:text-blue-700 transition-colors">
                                                #{{ order.invoice?.number }}
                                                </Link>
                                                <span v-else>-</span>
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-xs text-white">
                                                <span
                                                    :class="['px-2 py-1 rounded-md font-bold', getOrderStatusColor(order)]">
                                                    {{ order.formatted_status ?? "-" }}
                                                </span>
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                {{
                                                    new Date(
                                                        order.created_at
                                                    ).toLocaleString()
                                                }}
                                            </td>

                                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                <div class="flex justify-end space-x-2">
                                                    <Link :href="route(
                                                        'orders.edit',
                                                        order.id
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
                                    <Link :href="orders.prev_page_url"
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
                                    <Link :href="orders.next_page_url"
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

            <Transition>
                <span v-if="invoiceForm.selected.length > 0"
                    class="fixed bottom-10 left-[50%] -translate-x-1/2  inline-flex rounded-md shadow-sm">
                    <button @click="submitInvoice" :disabled="invoiceForm.processing" type="button"
                        class="disabled:opacity-50 relative inline-flex items-center space-x-2 bg-blue-500 rounded-md px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition-colors focus:z-10">
                        <PaperClipIcon class="size-5" />
                        <span v-if="invoiceForm.processing">{{ $t('Generating invoice') }}</span>
                        <span v-else>{{ $t('Generate invoice for') }} {{ invoiceForm.selected.length }} {{
                            invoiceForm.selected.length === 1 ? $t('order') : $t('orders') }}</span>
                    </button>
                </span>
            </Transition>
        </AppLayoutNew>
    </AppSuspense>
</template>
