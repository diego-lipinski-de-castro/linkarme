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

const { invoice } = defineProps({
    invoice: Object,
});

const form = useForm({

});

const submit = () => {
    form.put(route('invoices.update', invoice.id), {
        onError(error) {
            console.log(error)
        }
    });
}
</script>
        
<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Edit invoice')">
            <div class="max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-10">
                        <PencilIcon class="h-6 w-6" />
                    </div>

                    <div>
                        <span class="block font-bold">{{ $t('Edit invoice') }}</span>
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 py-5">
                    <div class="ml-12 mr-16">
                        <form @submit.prevent="submit" class="grid grid-cols-1 sm:grid-cols-6 gap-6">

                            <div class="col-span-6">
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ $t('Status') }}</label>
                                <div class="mt-1">

                                    <select v-model="form.status" id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option v-for="(label, key) in statuses" :key="key" :value="key">{{ label }}</option>
                                    </select>

                                </div>

                                <InputError class="mt-2" :message="form.errors.status"/>
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

                                <button type="button" class="mt-2 flex max-w-xs items-center rounded-md bg-blue-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-2 hover:bg-blue-700 transition-all">
                                    <span class="px-1 text-sm font-medium text-white">{{ $t("Add sites") }}</span>
                                </button>

                            </div>

                            <div class="col-span-6">
                                <InputLabel for="delivery_date" :value="$t('Data de entrega')"/>
                                <TextInput id="delivery_date" v-model="form.delivery_date" type="date" class="mt-1 block w-full"/>
                                <InputError class="mt-2" :message="form.errors.delivery_date"/>
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
        