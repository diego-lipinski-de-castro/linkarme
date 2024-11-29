<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { computed, ref, getCurrentInstance, toRaw, onMounted, watch, nextTick } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { PencilIcon, TrashIcon, PlusCircleIcon, ArrowTopRightOnSquareIcon } from '@heroicons/vue/24/outline';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { useCoinStore } from '@/stores/coin'
import Checkbox from '@/Components/Checkbox.vue';

const app = getCurrentInstance()
const coinStore = useCoinStore()

const { coins, statuses, clients, sites } = defineProps({
    coins: Object,
    statuses: Object,
    clients: Array,
    sites: Object,
    types: Array,
});

const form = useForm({
    status: null,
    client_id: null,
    type_id: null,
    receipt_date: '',
    delivery_date: '',
    payment_date: '',
    sites: [],
});

const submit = () => {
    form.transform((data) => ({
        status: data.status,
        client_id: data.client_id,
        type_id: data.type_id,

        items: data.sites.map((item) => ({
            site_id: item.id,
            seller_id: item.seller?.id,

            link: item.link,

            cost: item.cost,
            sale: item.sale,
            comission: item.seller?.comission ?? 0,

            cost_coin: coinStore.coin,
            sale_coin: coinStore.coin,
            comission_coin: coinStore.coin,

            received: item.received,
            paid: item.paid,
            comissioned: item.comissioned,

            link_status: item.link_status,
        })),

        receipt_date: data.receipt_date,
        delivery_date: data.delivery_date,
        payment_date: data.payment_date,
    })).post(route('orders.store'), {
        onError(error) {
            console.log(error)
        }
    });
}

const listForm = useForm({
    list: 'issoenoticia.com.br\nblogdoanderson.com\ncliccamaqua.com.br',
})

const list = ref([])

const go = () => {
    listForm.post(route('orders.go'), {
        preserveScroll: true,
        onSuccess: (res) => {
            openOrderDialog.value = false
            openSitesDialog.value = true

            list.value = res.props.sites
        }
    });
}

const openOrderDialog = ref(false)
const openSitesDialog = ref(false)
const openEditDialog = ref(false)

const openLinkDialog = ref(false)
const linkValue = ref('')

const editingColumn = ref(null);
const editingRow = ref(null);
const editingSite = ref(null);
const editingValue = ref('')

const add = (site = null, index) => {
    if(site) {
        const newSite = structuredClone(toRaw(site));

        newSite.link = null;
        newSite.received = false;
        newSite.paid = false;
        newSite.comissioned = false;
        newSite.link_status = null;

        form.sites = form.sites.toSpliced(index, 0, newSite)
        return;
    }

    for(let url of Object.keys(list.value)) {
        list.value[url].link = null;
        list.value[url].received = false;
        list.value[url].paid = false;
        list.value[url].comissioned = false;
        list.value[url].link_status = null;

        form.sites.push(list.value[url])
    }

    openSitesDialog.value = false
}

const remove = (index) => {
    form.sites.splice(index, 1)
}

const addLink = (row) => {
    editingRow.value = row;
    linkValue.value = form.sites[editingRow.value].link;
    openLinkDialog.value = true;
}

const addLinkConfirm = () => {
    form.sites[editingRow.value].link = linkValue.value.replace(/^https?:\/\//, '');
    openLinkDialog.value = false;
    linkValue.value = '';
}

const edit = (column, row, site) => {
    editingColumn.value = column;
    editingRow.value = row;
    editingSite.value = site;

    if(column === 'comission') {
        editingValue.value = Math.ceil((site.seller?.comission / coinStore.ratios[site.seller?.comission_coin]) / 100) * 100;
    } else {
        editingValue.value = Math.ceil((site[column] / coinStore.ratios[site[`${column}_coin`]]) / 100) * 100;
    }

    openEditDialog.value = true;
}

const update = (all) => {
    if(Number.isInteger(editingValue.value)) return;

    if(editingColumn.value === 'comission') {
        const rawValue = app.appContext.config.globalProperties.$filters.unformat(editingValue.value, coins[coinStore.coin]) * 100;
        const coin = editingSite.value.seller.comission_coin;
        const value = Math.floor(rawValue * coinStore.ratios[coin])

        if(all) {
            form.sites
                .filter(site => site.url === editingSite.value.url)
                .forEach(site => {
                    site.seller.comission = value;
                })
        } else {
            form.sites[editingRow.value].seller.comission = value;
        }
    } else {
        const rawValue = app.appContext.config.globalProperties.$filters.unformat(editingValue.value, coins[coinStore.coin]) * 100;
        const coin = editingSite.value[`${editingColumn.value}_coin`];
        const value = Math.floor(rawValue * coinStore.ratios[coin])

        if(all) {
            form.sites
                .filter(site => site.url === editingSite.value.url)
                .forEach(site => {
                    site[editingColumn.value] = value;
                })
        } else {
            form.sites[editingRow.value][editingColumn.value] = value;
        }
    }

    openEditDialog.value = false;
    editingValue.value = '';
}

const showEditAll = computed(() => {
    if(editingSite.value === null) {
        return false;
    }

    return form.sites.filter(site => site.url === editingSite.value.url).length > 1
})

const costTotal = computed(() => {
    let total = 0

    for(let site of form.sites) {
        total += Math.ceil((site.cost / coinStore.ratios[site.cost_coin]) / 100);
    
    }

    total = app.appContext.config.globalProperties.$filters.currency(total, {
        ...coins[coinStore.coin],
        precision: 0,
    });

    return total;
})

const saleTotal = computed(() => {
    let total = 0

    for(let site of form.sites) {
        total += Math.ceil((site.sale / coinStore.ratios[site.sale_coin]) / 100);
    
    }

    total = app.appContext.config.globalProperties.$filters.currency(total, {
        ...coins[coinStore.coin],
        precision: 0,
    });

    return total;

})

const markup = (site) => {
    let mark = Math.ceil(((site.sale - site.cost - (site.seller?.comission ?? 0)) / coinStore.ratios[site.sale_coin]) / 100)

    return app.appContext.config.globalProperties.$filters.currency(mark, {
        ...coins[coinStore.coin],
        precision: 0,
    })
}

const markupTotal = computed(() => {
    let total = 0

    for(let site of form.sites) {
        total += Math.ceil(((site.sale - site.cost - (site.seller?.comission ?? 0)) / coinStore.ratios[site.sale_coin]) / 100);
    }

    total = app.appContext.config.globalProperties.$filters.currency(total, {
        ...coins[coinStore.coin],
        precision: 0,
    });

    return total;
})

const comissionTotal = computed(() => {
    let total = 0

    for(let site of form.sites) {
        if(!site.seller) continue;
        total += Math.ceil(((site.seller.comission) / coinStore.ratios[site.seller.comission_coin]) / 100);
    }

    total = app.appContext.config.globalProperties.$filters.currency(total, {
        ...coins[coinStore.coin],
        precision: 0,
    });

    return total;
})
</script>
        
<template>
    <AppSuspense>
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
                                    {{ $t("Add sites") }}
                                </h3>

                                <div class="mt-4 mx-auto w-96">
                                    <div>
                                        <InputLabel for="list" :value="$t('Site list')" />
                                        <textarea id="list" v-model="listForm.list" rows="5"
                                            class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>
                                        <InputError class="mt-2" :message="listForm.errors.list" />
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button @click="go" type="button" :disabled="listForm.processing" class="mt-4 max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 disabled:opacity-50">
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
                                    {{ $t("Add sites") }}
                                </h3>

                                <div class="mt-4 mx-auto">
                                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Portal') }}</th>
                                                    <th scope="col" class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Vendedor') }}</th>
                                                    <th scope="col" class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Valor de compra') }}</th>
                                                    <th scope="col" class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Valor de venda') }}</th>
                                                    <th scope="col" class="relative whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-0">
                                                        <span class="sr-only">Edit</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                <tr v-for="(site, url) in list" :key="url">
                                                    <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{ url }}</td>

                                                    <td class="whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        {{ site === null ? '-' : site.seller?.name }}
                                                    </td>

                                                    <td class="whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <span v-if="site === null">-</span>
                                                        <span v-else>
                                                            <span :data-tippy-content="site.cost_coin != coinStore.coin ? `${$filters.currency(site.cost / 100, coins[site.cost_coin])}` : null" class="relative flex space-x-2 items-center">
                                                                <span v-if="site.cost_coin != coinStore.coin" class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                                <span>
                                                                    {{ site.cost_coin != coinStore.coin ? '~ ' : null }}
                                                                    {{ $filters.currency(Math.ceil((site.cost / coinStore.ratios[site.cost_coin]) / 100), { ...coins[coinStore.coin], precision: 0, }) }}
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </td>

                                                    <td class="whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <span v-if="site === null">-</span>
                                                        <span v-else>
                                                            <span :data-tippy-content="site.sale_coin != coinStore.coin ? `${$filters.currency(site.sale / 100, coins[site.sale_coin])}` : null" class="relative flex space-x-2 items-center">
                                                                <span v-if="site.sale_coin != coinStore.coin" class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                                <span>
                                                                    {{ site.sale_coin != coinStore.coin ? '~ ' : null }}
                                                                    {{ $filters.currency(Math.ceil((site.sale / coinStore.ratios[site.sale_coin]) / 100), { ...coins[coinStore.coin], precision: 0, }) }}
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

                                <div class="text-right">
                                    <button @click="add(null)" type="button"
                                        class="mt-4 lex max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 disabled:opacity-50">
                                        <span class="px-1 text-sm font-medium text-white">{{ $t("Add to order") }}</span>
                                    </button>
                                </div>

                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <TransitionRoot as="template" :show="openEditDialog">
            <Dialog as="div" class="relative z-10" @close="openEditDialog = false">
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
                                    <span v-if="editingColumn === 'cost'">{{ $t('Editar valor de compra') }}</span>
                                    <span v-if="editingColumn === 'sale'">{{ $t('Editar valor de venda') }}</span>
                                    <span v-if="editingColumn === 'comission'">{{ $t('Editar valor de comissão') }}</span>
                                </h3>

                                <div class="mt-4 mx-auto w-96">
                                    <div>
                                        <InputLabel for="value" :value="$t('Valor')" />
                                        <input v-model="editingValue" v-money3="coins[coinStore.coin]" id="value" rows="5" class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></input>
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="openEditDialog = false" type="button" class="rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 px-3 py-2 hover:bg-gray-50 transition-all">
                                        <span class="text-sm font-medium text-gray-700">{{ $t("Back") }}</span>
                                    </button>

                                    <button @click="update(false)" type="button" :disabled="false" class="rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 px-3 py-2 hover:bg-blue-700 disabled:opacity-50 transition-all">
                                        <span class="text-sm font-medium text-white">{{ $t("Update") }}</span>
                                    </button>

                                    <button v-show="showEditAll" @click="update(true)" type="button" :disabled="false" class="rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 px-3 py-2 hover:bg-blue-700 disabled:opacity-50 transition-all">
                                        <span class="text-sm font-medium text-white">{{ $t("Update all") }}</span>
                                    </button>
                                </div>

                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <TransitionRoot as="template" :show="openLinkDialog">
            <Dialog as="div" class="relative z-10" @close="openLinkDialog = false">
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
                                    {{ $t('Adicionar link') }}
                                </h3>

                                <div class="mt-4 mx-auto w-96">
                                    <div>
                                        <InputLabel for="link" :value="$t('Link')" />
                                        <input v-model="linkValue" id="link" class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm" />
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-end space-x-2">
                                    <button @click="openLinkDialog = false" type="button" class="rounded-md bg-white border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 px-3 py-2 hover:bg-gray-50 transition-all">
                                        <span class="text-sm font-medium text-gray-700">{{ $t("Back") }}</span>
                                    </button>

                                    <button @click="addLinkConfirm" type="button" :disabled="false" class="rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 px-3 py-2 hover:bg-blue-700 disabled:opacity-50 transition-all">
                                        <span class="text-sm font-medium text-white">{{ $t("Add") }}</span>
                                    </button>
                                </div>

                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <AppLayoutNew :title="$t('Add order')">
            <div class="mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-10">
                        <PlusCircleIcon class="h-6 w-6" />
                    </div>

                    <div>
                        <span class="block font-bold">{{ $t('Add order') }}</span>
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 py-5">
                    <div class="ml-12 mr-16">
                        <form @submit.prevent="submit" class="grid grid-cols-1 sm:grid-cols-6 gap-6">

                            <div class="col-span-3">
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ $t('Status') }}</label>
                                <div class="mt-1">

                                    <select v-model="form.status" id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option v-for="(label, key) in statuses" :key="key" :value="key">{{ label }}</option>
                                    </select>

                                </div>

                                <InputError class="mt-2" :message="form.errors.status"/>
                            </div>

                            <div class="col-span-3">
                                <label for="client_id" class="block text-sm font-medium text-gray-700">{{ $t('Client') }}</label>
                                <div class="mt-1">
                                    <select v-model="form.client_id" id="client_id" name="client_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option v-for="(client, index) in clients" :key="index" :value="client.id">{{ client.name }}</option>
                                    </select>
                                </div>

                                <InputError class="mt-2" :message="form.errors.client_id"/>
                            </div>

                            <div class="col-span-6">
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Type') }}</label>
                                <div class="mt-1">
                                    <select v-model="form.type_id" id="type_id" name="type_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option v-for="(type, index) in types" :key="index" :value="type.id">{{ type.name }}</option>
                                    </select>
                                </div>
                                <InputError class="mt-2" :message="form.errors.type_id"/>
                            </div>

                            <div class="col-span-6">

                                <div class="overflow-hidden border border-gray-300 sm:rounded-lg">
                                    <div class="overflow-x-scroll">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="whitespace-nowrap pl-4 pr-2 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Portal') }}</th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Link') }}</th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Vendedor') }}</th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Valor de compra') }}</th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Valor de venda') }}</th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Comissão') }}</th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Markup') }}</th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Recebido') }}</th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Pago') }}</th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Comissão') }}</th>
                                                    <th scope="col" class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">

                                                <tr v-if="form.sites.length === 0">
                                                    <td colspan="12" class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 italic">
                                                        {{ $t('No site have been added to this order yet.') }}
                                                    </td>
                                                </tr>

                                                <tr v-else v-for="(site, index) in form.sites" :key="index">
                                                    <td class="whitespace-nowrap pl-4 pr-2 py-2 text-red-500 hover:text-red-700 transition-all">
                                                        <div class="flex items-center w-fit space-x-2">
                                                            <button @click="remove(index)" type="button" class="min-w-5">
                                                                <TrashIcon class="size-5"/>
                                                            </button>

                                                            <button @click="add(site, index)" type="button" class="min-w-5 text-blue-500 hover:text-blue-700">
                                                                <PlusCircleIcon class="size-5"/>
                                                            </button>
                                                        </div>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                        <div class="flex space-x-1">
                                                            <span class="truncate w-36">{{ site.url }}</span>
                                                            <a :href="route('sites.edit', site.id)" target="_blank" class="block text-blue-500 hover:text-blue-700">
                                                                <ArrowTopRightOnSquareIcon class="size-4"/>
                                                            </a>
                                                        </div>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                        <div v-if="site.link" class="flex space-x-1">
                                                            <span :data-tippy-content="`${site.link}`" class="truncate w-36">{{ site.link }}</span>
                                                            <a :href="`https://${site.link}`" target="_blank" class="block text-blue-500 hover:text-blue-700">
                                                                <ArrowTopRightOnSquareIcon class="size-4"/>
                                                            </a>
                                                        </div>

                                                        <div v-else class="flex space-x-1">
                                                            <button @click="addLink(index)" type="button" class="text-blue-500 hover:text-blue-700">{{ $t('Add link') }}</button>
                                                        </div>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <span class="truncate w-36">
                                                            {{ site === null ? '-' : site.seller?.name }}
                                                        </span>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900 group">
                                                        <span v-if="site === null">-</span>
                                                        <span v-else class="flex items-center space-x-1">
                                                            <span :data-tippy-content="site.cost_coin != coinStore.coin ? `${$filters.currency(site.cost / 100, coins[site.cost_coin])}` : null" class="relative flex space-x-2 items-center">
                                                                <!-- <span v-if="site.cost_coin != coinStore.coin" class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span> -->
                                                                <span>
                                                                    {{ site.cost_coin != coinStore.coin ? '~ ' : null }}
                                                                    {{ $filters.currency(Math.ceil((site.cost / coinStore.ratios[site.cost_coin]) / 100), { ...coins[coinStore.coin], precision: 0, }) }}
                                                                </span>
                                                            </span>

                                                            <button @click="edit('cost', index, site)" type="button" class="p-1 scale-0 group-hover:scale-100 transition-all">
                                                                <PencilIcon class="-mt-1 size-4 text-blue-500 hover:text-blue-700"/>
                                                            </button>
                                                        </span>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900 group">
                                                        <span v-if="site === null">-</span>
                                                        <span v-else class="flex items-center space-x-1">
                                                            <span :data-tippy-content="site.sale_coin != coinStore.coin ? `${$filters.currency(site.sale / 100, coins[site.sale_coin])}` : null" class="relative flex space-x-2 items-center">
                                                                <!-- <span v-if="site.sale_coin != coinStore.coin" class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span> -->
                                                                <span>
                                                                    {{ site.sale_coin != coinStore.coin ? '~ ' : null }}
                                                                    {{ $filters.currency(Math.ceil((site.sale / coinStore.ratios[site.sale_coin]) / 100), { ...coins[coinStore.coin], precision: 0, }) }}
                                                                </span>
                                                            </span>

                                                            <button @click="edit('sale', index, site)" type="button" class="p-1 scale-0 group-hover:scale-100 transition-all">
                                                                <PencilIcon class="-mt-1 size-4 text-blue-500 hover:text-blue-700"/>
                                                            </button>
                                                        </span>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900 group">
                                                        <span v-if="site === null || site.seller === null">-</span>
                                                        <span v-else class="flex items-center space-x-1">
                                                            <span class="relative flex space-x-2 items-center">
                                                                <!-- <span v-if="site.seller.comission_coin != coinStore.coin" class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span> -->
                                                                <span>
                                                                    {{ site.seller.comission_coin != coinStore.coin ? '~ ' : null }}
                                                                    {{ $filters.currency(Math.ceil((site.seller.comission / coinStore.ratios[site.seller.comission_coin]) / 100), { ...coins[coinStore.coin], precision: 0, }) }}
                                                                </span>
                                                            </span>

                                                            <button @click="edit('comission', index, site)" type="button" class="p-1 scale-0 group-hover:scale-100 transition-all">
                                                                <PencilIcon class="-mt-1 size-4 text-blue-500 hover:text-blue-700"/>
                                                            </button>
                                                        </span>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <span v-if="site === null">-</span>
                                                        <span v-else>
                                                            ~ {{ markup(site) }}
                                                        </span>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <div>
                                                            <input
                                                                v-model="site.received"
                                                                type="checkbox"
                                                                id="received"
                                                                name="received"
                                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                            >
                                                        </div>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <div>
                                                            <input
                                                                v-model="site.paid"
                                                                type="checkbox"
                                                                id="paid"
                                                                name="paid"
                                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                            >
                                                        </div>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <div>
                                                            <input
                                                                v-model="site.comissioned"
                                                                type="checkbox"
                                                                id="comissioned"
                                                                name="comissioned"
                                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                            >
                                                        </div>
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <select v-model="site.link_status" id="link_status" name="link_status" class="block w-52 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                            <option :value="null">{{ $t('Selecione') }}</option>
                                                            <option value="SUBMITTED">{{ $t('Enviado ao portal') }}</option>
                                                            <option value="PRODUCTION">{{ $t('Em redação') }}</option>
                                                            <option value="WAITING">{{ $t('Aguardando aprovação') }}</option>
                                                            <option value="PUBLISHED">{{ $t('Publicado') }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>

                                            <tfoot v-if="form.sites.length > 0">
                                                <tr>
                                                    <td colspan="4" class="px-3 py-2"></td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        ~ {{ costTotal }}
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        ~ {{ saleTotal }}
                                                    </td>
                                                    
                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        ~ {{ comissionTotal }}
                                                    </td>

                                                    <td class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        ~ {{ markupTotal }}
                                                    </td>

                                                    <td colspan="4" class="border-l px-3 py-2"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <button @click="openOrderDialog = true;" type="button" class="mt-2 flex max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 transition-all">
                                    <span class="px-1 text-sm font-medium text-white">{{ $t("Add sites") }}</span>
                                </button>

                            </div>

                            <div class="col-span-2">
                                <InputLabel for="receipt_date" :value="$t('Data de recebimento')"/>
                                <TextInput id="receipt_date" v-model="form.receipt_date" type="date" class="mt-1 block w-full"/>
                                <InputError class="mt-2" :message="form.errors.receipt_date"/>
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="delivery_date" :value="$t('Data de entrega')"/>
                                <TextInput id="delivery_date" v-model="form.delivery_date" type="date" class="mt-1 block w-full"/>
                                <InputError class="mt-2" :message="form.errors.delivery_date"/>
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="payment_date" :value="$t('Data de pagamento')"/>
                                <TextInput id="payment_date" v-model="form.payment_date" type="date" class="mt-1 block w-full"/>
                                <InputError class="mt-2" :message="form.errors.payment_date"/>
                            </div>

                            <div class="col-span-6 self-end mb-1 flex">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Save
                                </PrimaryButton>

                                <ActionMessage :on="form.recentlySuccessful" class="self-end ml-3">
                                    Saved.
                                </ActionMessage>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayoutNew>
    </AppSuspense>
</template>
        