<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, getCurrentInstance } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import { PencilIcon, PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { useCoinStore } from '@/stores/coin'

const app = getCurrentInstance()

const coinStore = useCoinStore()

const { order, coins, statuses, sites, clientes, sellers } = defineProps({
    order: Object,
    coins: Object,
    statuses: Object,
    sites: Array,
    clients: Array,
    sellers: Array,
});

const form = useForm({
    status: order.status,
    client_id: order.client_id,
    receipt_date: order.receipt_date,
    delivery_date: order.delivery_date,
    payment_date: order.payment_date,
    charged: order.charged,
    paid: order.paid,
    markup: order.markup,
    comission: order.comission,
    sites: order.items,
});

const submit = () => {
    form.put(route('orders.update', order.id), {
        onError(error) {
            console.log(error)
        }
    });
}

const format = coins['BRL']

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
</script>
        
<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Edit order')">
            <div class="max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-10">
                        <PencilIcon class="h-6 w-6" />
                    </div>

                    <div>
                        <span class="block font-bold">{{ $t('Edit order') }}</span>
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
        