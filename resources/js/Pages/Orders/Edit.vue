<script setup>
import AppLayout from '@/Layouts/AppLayoutNew.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

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
    site_id: order.site_id,
    client_id: order.client_id,
    seller_id: order.seller_id,
    url: order.url,
    receipt_date: order.receipt_date,
    delivery_date: order.delivery_date,
    payment_date: order.payment_date,
    charged: order.charged,
    paid: order.paid,
    markup: order.markup,
    comission: order.comission,
});

const submit = () => {
    form.put(route('orders.update', order.id), {
        onError(error) {
            console.log(error)
        }
    });
}

const format = coins['BRL']

</script>
        
<template>
    <AppSuspense>
        <AppLayout :title="$t('Edit order')">
            <template #header>
                <div
                    class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $t('Edit order') }}
                    </h2>
                </div>
            </template>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form @submit.prevent="submit" class="grid grid-cols-1 sm:grid-cols-6 gap-x-6 gap-y-6 p-6 bg-white border-b border-gray-200">

                    <div class="col-span-6">
                        <label for="status" class="block text-sm font-medium text-gray-700">{{ $t('Status') }}</label>
                        <div class="mt-1">

                            <select v-model="form.status" id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">Selecione</option>
                                <option v-for="(label, key) in statuses" :key="key" :value="key">{{ label }}</option>
                            </select>

                        </div>

                        <InputError class="mt-2" :message="form.errors.status"/>
                    </div>

                    <div class="col-span-3">
                        <label for="client_id" class="block text-sm font-medium text-gray-700">{{ $t('Client') }}</label>
                        <div class="mt-1">
                            <select v-model="form.client_id" id="client_id" name="client_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">Selecione</option>
                                <option v-for="(client, index) in clients" :key="index" :value="client.id">{{ client.name }}</option>
                            </select>
                        </div>

                        <InputError class="mt-2" :message="form.errors.client_id"/>

                    </div>

                    <div class="col-span-3">
                        <label for="seller_id" class="block text-sm font-medium text-gray-700">{{ $t('Seller') }}</label>
                        <div class="mt-1">
                            <select v-model="form.seller_id" id="seller_id" name="seller_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">Selecione</option>
                                <option v-for="(seller, index) in sellers" :key="index" :value="seller.id">{{ seller.name }}</option>
                            </select>
                        </div>

                        <InputError class="mt-2" :message="form.errors.seller_id"/>

                    </div>

                    <div class="col-span-6">
                        <label for="site_id" class="block text-sm font-medium text-gray-700">{{ $t('Site') }}</label>
                        <div class="mt-1">
                            <select v-model="form.site_id" id="site_id" name="site_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">Selecione</option>
                                <option v-for="(site, index) in sites" :key="index" :value="site.id">{{ site.url }}</option>
                            </select>
                        </div>

                        <InputError class="mt-2" :message="form.errors.site_id"/>
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
                        <input v-model.lazy="form.charged" v-money3="format" type="text" name="charged" id="charged" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" />
                        <InputError class="mt-2" :message="form.errors.charged"/>
                    </div>

                    <div class="col-span-3">
                        <InputLabel for="paid" :value="$t('Paid')"/>
                        <input v-model.lazy="form.paid" v-money3="format" type="text" name="paid" id="paid" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" />
                        <InputError class="mt-2" :message="form.errors.paid"/>
                    </div>

                    <div class="col-span-6">
                        <InputLabel for="markup" :value="$t('Markup')"/>
                        <input v-model.lazy="form.markup" v-money3="format" type="text" name="markup" id="markup" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" />
                        <InputError class="mt-2" :message="form.errors.markup"/>
                    </div>

                    <div class="col-span-6">
                        <InputLabel for="comission" :value="$t('Comission')"/>
                        <input v-model.lazy="form.comission" v-money3="format" type="text" name="comission" id="comission" class="mt-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" />
                        <InputError class="mt-2" :message="form.errors.comission"/>
                    </div>

                    <div class="col-span-6 flex justify-end">
                        <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                            class="flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">{{ $t('Save') }}</button>
                    </div>
                </form>
            </div>
        </AppLayout>
    </AppSuspense>
</template>
        