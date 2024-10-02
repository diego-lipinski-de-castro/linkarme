<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { computed, ref, getCurrentInstance } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { useCoinStore } from '@/stores/coin'

const app = getCurrentInstance()

const coinStore = useCoinStore()

const { coins, statuses, sites, clientes, sellers } = defineProps({
    coins: Object,
    statuses: Object,
    clients: Array,
    sellers: Array,
    sites: Object,
});

const form = useForm({
    status: null,
    site_id: null,
    client_id: null,
    seller_id: null,
    url: '',
    receipt_date: '',
    delivery_date: '',
    payment_date: '',
    charged: '',
    paid: '',
    markup: '',
    comission: '',
    sites: [],
});

const submit = () => {
    form.post(route('orders.store'), {
        onError(error) {
            console.log(error)
        }
    });
}

const format = coins['BRL']

const listForm = useForm({
    list: 'issoenoticia.com.br\nblogdoanderson.com\ncliccamaqua.com.br',
})

const list = ref([])

const go = () => {
    listForm.post(route('orders.go'), {
        preserveScroll: true,
        onSuccess: (res) => {
            console.log(res.props)

            openOrderDialog.value = false
            openSitesDialog.value = true

            list.value = res.props.sites
        }
    });
}

const openOrderDialog = ref(false)
const openSitesDialog = ref(false)

const add = () => {
    for(let url of Object.keys(list.value)) {
        form.sites.push(list.value[url])
    }

    openSitesDialog.value = false
}

const remove = (index) => {
    form.sites.splice(index, 1)
}

const costTotal = computed(() => {
    let total = 0

    for(let site of form.sites) {
        total += Math.ceil((site.cost / coinStore.ratios[site.cost_coin]) / 100);
        
        console.log([site.cost_coin, site.cost], [coinStore.coin, Math.ceil((site.cost / coinStore.ratios[site.cost_coin]) / 100)])
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
        
        console.log([site.sale_coin, site.sale], [coinStore.coin, Math.ceil((site.sale / coinStore.ratios[site.sale_coin]) / 100)])
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
                                    <button @click="add" type="button"
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

        <AppLayoutNew :title="$t('Add order')">
            <div class="max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-10">
                        <PlusIcon class="h-6 w-6" />
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

                                <div class="overflow-hidden border border-gray-300 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="whitespace-nowrap pl-4 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
                                                <th scope="col" class="whitespace-nowrap pr-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Portal') }}</th>
                                                <th scope="col" class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Vendedor') }}</th>
                                                <th scope="col" class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Valor de compra') }}</th>
                                                <th scope="col" class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $t('Valor de venda') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">

                                            <tr v-if="form.sites.length === 0">
                                                <td colspan="5" class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 italic">
                                                    {{ $t('No site have been added to this order yet.') }}
                                                </td>
                                            </tr>

                                            <tr v-else v-for="(site, index) in form.sites" :key="index">
                                                <td class="whitespace-nowrap pl-4 py-2 text-red-500 hover:text-red-700 transition-all">
                                                    <div class="flex items-center w-fit">
                                                        <button @click="remove(index)" type="button" class="min-w-5">
                                                            <TrashIcon class="size-5"/>
                                                        </button>
                                                    </div>
                                                </td>

                                                <td class="whitespace-nowrap pr-3 py-2 text-sm text-gray-500"> {{ site.url }}</td>

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
                                            </tr>
                                        </tbody>

                                        <tfoot v-if="form.sites.length > 0">
                                            <tr>
                                                <td colspan="3" class="px-3 py-2"></td>

                                                <td class="whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                    ~ {{ costTotal }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                    ~ {{ saleTotal }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <button @click="openOrderDialog = true;" type="button" class="mt-2 flex max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 transition-all">
                                    <span class="px-1 text-sm font-medium text-white">{{ $t("Add sites") }}</span>
                                </button>

                            </div>

                            <div class="col-span-6">
                                <InputLabel for="url" :value="$t('URL')"/>
                                <TextInput id="url" v-model="form.url" type="text" class="mt-1 block w-full"/>
                                <InputError class="mt-2" :message="form.errors.url"/>
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

                            <div class="col-span-3">
                                <InputLabel for="charged" :value="$t('Charged')"/>
                                <input v-model.lazy="form.charged" v-money3="format" type="text" name="charged" id="charged" class="mt-1 block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md" />
                                <InputError class="mt-2" :message="form.errors.charged"/>
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="paid" :value="$t('Paid')"/>
                                <input v-model.lazy="form.paid" v-money3="format" type="text" name="paid" id="paid" class="mt-1 block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md" />
                                <InputError class="mt-2" :message="form.errors.paid"/>
                            </div>

                            <div class="col-span-6">
                                <InputLabel for="markup" :value="$t('Markup')"/>
                                <input v-model.lazy="form.markup" v-money3="format" type="text" name="markup" id="markup" class="mt-1 block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md" />
                                <InputError class="mt-2" :message="form.errors.markup"/>
                            </div>

                            <div class="col-span-6">
                                <InputLabel for="comission" :value="$t('Comission')"/>
                                <input v-model.lazy="form.comission" v-money3="format" type="text" name="comission" id="comission" class="mt-1 block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md" />
                                <InputError class="mt-2" :message="form.errors.comission"/>
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
        