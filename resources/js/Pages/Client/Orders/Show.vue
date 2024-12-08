<script setup>
import AppLayoutNew from '@/Layouts/ClientLayoutNew.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { ref, computed, getCurrentInstance, toRaw } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import { PencilIcon, TrashIcon, PlusCircleIcon, ArrowTopRightOnSquareIcon, CheckIcon } from '@heroicons/vue/24/outline';
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { useCoinStore } from '@/stores/coin'

const app = getCurrentInstance()
const coinStore = useCoinStore()

const { order, coins, statuses } = defineProps({
    order: Object,
    coins: Object,
    statuses: Object,
    sites: Object,
    types: Array,
});

const form = useForm({
    status: order.status,
    client_id: order.client_id,
    type_id: order.type_id,
    receipt_date: order.receipt_date,
    delivery_date: order.delivery_date,
    payment_date: order.payment_date,
    sites: order.items.map((item) => ({
        ...item,

        id: item.pivot.id,

        site_id: item.pivot.site_id,
        seller_id: item.seller?.id,

        link: item.pivot.link,

        cost: item.pivot.cost,
        sale: item.pivot.sale,
        comission: item.pivot.comission,

        cost_coin: item.pivot.cost_coin,
        sale_coin: item.pivot.sale_coin,
        comission_coin: item.pivot.comission_coin,

        received: item.pivot.received,
        paid: item.pivot.paid,
        comissioned: item.pivot.comissioned,

        link_status: item.pivot.link_status,
    })),
});

const costTotal = computed(() => {
    let total = 0

    for (let site of form.sites) {
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

    for (let site of form.sites) {
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
        <AppLayoutNew :title="$t('Show order')">
            <div class="mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-10">
                        <PencilIcon class="h-6 w-6" />
                    </div>

                    <div>
                        <span class="block font-bold">{{ $t('Show order') }}</span>
                        <span class="block text-xs text-gray-500">#{{ order.number }}</span>
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 pt-5 pb-10">
                    <div class="ml-12 mr-16">
                        <form @submit.prevent="submit" class="grid grid-cols-1 sm:grid-cols-6 gap-6">

                            <div class="col-span-3">
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ $t('Status') }}</label>
                                
                                <span v-if="order.status">{{ order.status }}</span>
                                <span v-else>-</span>
                            </div>

                            <div class="col-span-6">
                                <label class="block text-sm font-medium text-gray-700">{{ $t('Type') }}</label>
                                <span v-if="order.type">{{ order.type.name }}</span>
                                <span v-else>-</span>
                            </div>

                            <div class="col-span-6">

                                <div class="overflow-hidden border border-gray-300 sm:rounded-lg">
                                    <div class="overflow-x-scroll">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        {{ $t('Portal') }}</th>
                                                    <th scope="col"
                                                        class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        {{ $t('Link') }}</th>
                                                    <th scope="col"
                                                        class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        {{ $t('Valor') }}</th>
                                                    <th scope="col"
                                                        class="border-l whitespace-nowrap px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        {{ $t('Status') }}</th>
                                                </tr>
                                            </thead>

                                            <tbody class="divide-y divide-gray-200 bg-white">

                                                <tr v-if="form.sites.length === 0">
                                                    <td colspan="12"
                                                        class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 italic">
                                                        {{ $t('No site have been added to this order yet.') }}
                                                    </td>
                                                </tr>

                                                <tr v-else v-for="(site, index) in form.sites" :key="index">
                                                    <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                        <div class="flex space-x-1">
                                                            <span>{{ site.url }}</span>
                                                            <a :href="`https://${site.url}`" target="_blank"
                                                                class="block text-blue-500 hover:text-blue-700">
                                                                <ArrowTopRightOnSquareIcon class="size-4" />
                                                            </a>
                                                        </div>
                                                    </td>

                                                    <td
                                                        class="border-l whitespace-nowrap px-3 py-2 text-sm text-gray-500">
                                                        <div v-if="site.link" class="flex space-x-1">
                                                            <span :data-tippy-content="`${site.link}`">{{ site.link }}</span>
                                                            <a :href="`https://${site.link}`" target="_blank"
                                                                class="block text-blue-500 hover:text-blue-700">
                                                                <ArrowTopRightOnSquareIcon class="size-4" />
                                                            </a>
                                                        </div>

                                                        <div v-else class="flex space-x-1">
                                                            -
                                                        </div>
                                                    </td>

                                                    <td
                                                        class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <span v-if="site === null">-</span>
                                                        <span v-else class="flex items-center space-x-1">
                                                            <span
                                                                :data-tippy-content="site.sale_coin != coinStore.coin ? `${$filters.currency(site.sale / 100, coins[site.sale_coin])}` : null"
                                                                class="relative flex space-x-2 items-center">
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
                                                        class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        <span class="">
                                                            <span v-if="site.link_status === null"
                                                                class="text-blue-500">{{ $t('-') }}</span>
                                                            <span v-if="site.link_status === 'SUBMITTED'"
                                                                class="text-blue-500">{{ $t('Enviado ao portal')
                                                                }}</span>
                                                            <span v-if="site.link_status === 'PRODUCTION'"
                                                                class="text-blue-500">{{ $t('Em redação') }}</span>
                                                            <span v-if="site.link_status === 'WAITING'"
                                                                class="text-blue-500">{{ $t('Aguardando aprovação')
                                                                }}</span>
                                                            <span v-if="site.link_status === 'PUBLISHED'"
                                                                class="text-blue-500">{{ $t('Publicado') }}</span>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>

                                            <tfoot v-if="form.sites.length > 0">
                                                <tr>
                                                    <td colspan="2" class="px-3 py-2"></td> 

                                                    <td
                                                        class="border-l whitespace-nowrap px-3 py-2 text-sm font-medium text-gray-900">
                                                        ~ {{ saleTotal }}
                                                    </td>

                                                    <td colspan="4" class="border-l px-3 py-2"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <div class="col-span-2">
                                <InputLabel for="receipt_date" :value="$t('Data de recebimento')" />
                                <span v-if="form.receipt_date">{{ new Date(form.receipt_date).toLocaleDateString() }}</span>
                                <span v-else>-</span>
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="delivery_date" :value="$t('Data de entrega')" />
                                <span v-if="form.delivery_date">{{ new Date(form.delivery_date).toLocaleDateString() }}</span>
                                <span v-else>-</span>
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="payment_date" :value="$t('Data de pagamento')" />
                                <span v-if="form.payment_date">{{ new Date(form.payment_date).toLocaleDateString() }}</span>
                                <span v-else>-</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayoutNew>
    </AppSuspense>
</template>