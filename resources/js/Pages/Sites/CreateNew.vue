<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import { PlusIcon } from '@heroicons/vue/24/outline';

const { coins, categories, languages, countries, sellers, types: typesProp } = defineProps({
    coins: Object,
    categories: Array,
    languages: Array,
    countries: Array,
    sellers: Array,
    teams: Array,
    types: Array,
});

const form = useForm({
    url: '',
    category_id: null,
    da: '',
    dr: '',
    traffic: '',
    tf: '',
    language_id: null,
    country_id: null,
    link_type: null,

    gambling: false,
    cdb: false,
    cripto: false,
    sponsor: false,
    menu: false,
    banner: false,

    cost: '',
    cost_coin: 'BRL',

    sale: '',
    sale_coin: 'BRL',

    last_posted: '',
    seller_id: null,
    team_id: null,

    obs: '',

    owner_name: '',
    owner_email: '',
    owner_phone: '',
    bank: '',
    pix: '',
    phone: '',
    paypal: '',
    instagram: '',
    facebook: '',

    types: typesProp,
});

const submit = () => {
    form.post(route('sites.store'));
}

</script>
        
<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Add site')">
            <div class="max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-10">
                        <PlusIcon class="h-6 w-6" />
                    </div>

                    <div>
                        <span class="block font-bold">{{ $t('Add site') }}</span>
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 py-5">
                    <div class="ml-12 mr-16">
                        <form @submit.prevent="submit" class="grid grid-cols-1 sm:grid-cols-6 gap-x-6 gap-y-6">

                            <div class="col-span-6">
                                <InputLabel for="url" :value="$t('URL')" />
                                <TextInput id="url" v-model="form.url" type="text" class="mt-1 block w-full" required
                                    autofocus autocomplete="url" />
                                <InputError class="mt-2" :message="form.errors.url" />
                            </div>

                            <div class="col-span-6">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                                <div class="mt-1">

                                    <select v-model="form.category_id" id="category_id" name="category_id"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option v-for="(category, index) in categories" :key="index" :value="category.id">{{
                                            category.name }}</option>
                                    </select>

                                </div>

                                <InputError class="mt-2" :message="form.errors.category_id" />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="da" :value="$t('DA')" />
                                <TextInput id="da" v-model="form.da" type="number" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.da" />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="dr" :value="$t('DR')" />
                                <TextInput id="dr" v-model="form.dr" type="number" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.dr" />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="traffic" :value="$t('Traffic')" />
                                <TextInput id="traffic" v-model="form.traffic" type="number" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.traffic" />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="tf" :value="$t('TF')" />
                                <TextInput id="tf" v-model="form.tf" type="number" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.tf" />
                            </div>

                            <div class="col-span-3">
                                <label for="language_id" class="block text-sm font-medium text-gray-700">{{ $t('Language')
                                }}</label>
                                <div class="mt-1">

                                    <select v-model="form.language_id" id="language_id" name="language_id"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option v-for="(language, index) in languages" :key="index" :value="language.id">{{
                                            language.name }}</option>
                                    </select>

                                </div>

                                <InputError class="mt-2" :message="form.errors.language_id" />
                            </div>

                            <div class="col-span-3">
                                <label for="country_id" class="block text-sm font-medium text-gray-700">{{ $t('Country')
                                }}</label>
                                <div class="mt-1">

                                    <select v-model="form.country_id" id="country_id" name="country_id"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option v-for="(country, index) in countries" :key="index" :value="country.id">{{
                                            country.name }}</option>
                                    </select>

                                </div>

                                <InputError class="mt-2" :message="form.errors.country_id" />
                            </div>

                            <div class="col-span-6">
                                <label for="link_type" class="block text-sm font-medium text-gray-700">Tipo de link</label>
                                <div class="mt-1">

                                    <select v-model="form.link_type" id="link_type" name="link_type"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option value="DOFOLLOW">DOFOLLOW</option>
                                        <option value="NOFOLLOW">NOFOLLOW</option>

                                    </select>

                                </div>

                                <InputError class="mt-2" :message="form.errors.link_type" />
                            </div>

                            <div class="col-span-6 space-y-3">
                                <div class="flex items-center space-x-2">
                                    <Checkbox id="sponsor" v-model:checked="form.sponsor" name="sponsor" />
                                    <InputLabel for="sponsor" :value="$t('Sponsor')" />
                                </div>

                                <div class="flex items-center space-x-2">
                                    <Checkbox id="menu" v-model:checked="form.menu" name="menu" />
                                    <InputLabel for="menu" :value="$t('Menu')" />
                                </div>

                                <div class="flex items-center space-x-2">
                                    <Checkbox id="banner" v-model:checked="form.banner" name="banner" />
                                    <InputLabel for="banner" :value="$t('Banner')" />
                                </div>
                            </div>

                            <div class="col-span-6">

                                <label for="link_type" class="block text-sm font-medium text-gray-700">{{ $t('Pricing table') }}</label>

                                <div class="mt-1 ring-1 ring-gray-300 sm:mx-0 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead>
                                            <tr>
                                                <th scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                    Type
                                                </th>

                                                <th scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                    Available
                                                </th>

                                                <th scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                    Cost
                                                </th>

                                                <th scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                    Sale
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <!-- Default -->
                                            <tr>
                                                <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6 align-top">
                                                    <div class="font-medium text-gray-900">
                                                        {{ $t('Default') }}
                                                    </div>
                                                </td>

                                                <td class="px-3 py-3.5 text-sm text-gray-500 align-top">

                                                </td>

                                                <td class="px-3 py-3.5 text-sm text-gray-500 align-top">

                                                    <div class="relative rounded-md shadow-sm">
                                                        <input v-model.lazy="form.cost" v-money3="coins[form.cost_coin]"
                                                            type="text" name="cost" id="cost"
                                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                                            <label for="cost_coin" class="sr-only">Moeda</label>
                                                            <select v-model="form.cost_coin" id="cost_coin" name="cost_coin"
                                                                class="focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                <option value="BRL">BRL</option>
                                                                <option value="EUR">EUR</option>
                                                                <option value="USD">USD</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </td>

                                                <td class="px-3 py-3.5 text-sm text-gray-500">

                                                    <div class="relative rounded-md shadow-sm">
                                                        <input v-model.lazy="form.sale" v-money3="coins[form.sale_coin]"
                                                            type="text" name="sale" id="sale"
                                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                                            <label for="sale_coin" class="sr-only">Moeda</label>
                                                            <select v-model="form.sale_coin" id="sale_coin" name="sale_coin"
                                                                class="focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                <option value="BRL">BRL</option>
                                                                <option value="EUR">EUR</option>
                                                                <option value="USD">USD</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                </td>
                                            </tr>

                                            <!--  -->
                                            <tr v-for="(type, index) in form.types" :key="index">
                                                <td
                                                    :class="'border-t border-transparent relative py-4 pl-4 pr-3 text-sm sm:pl-6'">
                                                    <div class="font-medium text-gray-900">
                                                        {{ type.name }}
                                                    </div>

                                                    <div class="absolute -top-px left-6 right-0 h-px bg-gray-200" />
                                                </td>

                                                <td :class="['border-t border-gray-200 px-3 py-3.5 text-sm text-gray-500']">
                                                    <Checkbox :id="type.id" v-model:checked="type.available"
                                                        :name="type.id" />
                                                </td>

                                                <td
                                                    :class="['border-t border-gray-200 relative px-3 py-3.5 text-sm text-gray-500']">

                                                    <div
                                                        :class="['relative rounded-md shadow-sm', { 'opacity-50': !type.available }]">
                                                        <input :disabled="!type.available" v-model.lazy="type.cost"
                                                            v-money3="coins[type.cost_coin]" type="text" name="cost"
                                                            id="cost"
                                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                                            <label for="cost_coin" class="sr-only">Moeda</label>
                                                            <select :disabled="!type.available" v-model="type.cost_coin"
                                                                id="cost_coin" name="cost_coin"
                                                                class="focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                <option value="BRL">BRL</option>
                                                                <option value="EUR">EUR</option>
                                                                <option value="USD">USD</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </td>

                                                <td
                                                    :class="['border-t border-transparent relative px-3 py-3.5 text-sm text-gray-500']">

                                                    <div
                                                        :class="['relative rounded-md shadow-sm', { 'opacity-50': !type.available }]">
                                                        <input :disabled="!type.available" v-model.lazy="type.sale"
                                                            v-money3="coins[type.sale_coin]" type="text" name="sale"
                                                            id="sale"
                                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                                            <label for="sale_coin" class="sr-only">Moeda</label>
                                                            <select :disabled="!type.available" v-model="type.sale_coin"
                                                                id="sale_coin" name="sale_coin"
                                                                class="focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                <option value="BRL">BRL</option>
                                                                <option value="EUR">EUR</option>
                                                                <option value="USD">USD</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="absolute -top-px left-0 right-6 h-px bg-gray-200" />
                                                </td>
                                            </tr>
                                            <!--  -->

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-span-6">
                                <InputLabel for="last_posted" value="Último post" />
                                <TextInput id="last_posted" v-model="form.last_posted" type="date"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.last_posted" />
                            </div>

                            <div class="col-span-6">
                                <label for="seller_id" class="block text-sm font-medium text-gray-700">{{ $t('Vendedor')
                                }}</label>
                                <div class="mt-1">
                                    <select v-model="form.seller_id" id="seller_id" name="seller_id"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option v-for="(seller, index) in sellers" :key="index" :value="seller.id">{{
                                            seller.name }}</option>
                                    </select>
                                </div>

                                <InputError class="mt-2" :message="form.errors.seller_id" />

                            </div>

                            <div class="col-span-6">
                                <InputLabel for="team_id" :value="$t('Atendimento')" />

                                <div class="mt-1">
                                    <select v-model="form.team_id" id="team_id" name="team_id"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option v-for="(team, index) in teams" :key="index" :value="team.id">{{ team.name }}
                                        </option>
                                    </select>
                                </div>

                                <InputError class="mt-2" :message="form.errors.team_id" />
                            </div>

                            <div class="col-span-6">
                                <InputLabel for="obs" :value="$t('Obs')" />
                                <textarea id="obs" v-model="form.obs" type="text" rows="3"
                                    class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-300 focus:outline-none focus:ring-blue-200 sm:text-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.obs" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="owner_name" :value="$t('Owner name')" />
                                <TextInput id="owner_name" v-model="form.owner_name" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_name" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="owner_email" :value="$t('Owner email')" />
                                <TextInput id="owner_email" v-model="form.owner_email" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_email" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="owner_phone" :value="$t('Owner phone')" />
                                <TextInput id="owner_phone" v-model="form.owner_phone" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_phone" />
                            </div>

                            <div class="col-span-6">
                                <InputLabel for="bank" :value="$t('Site bank')" />
                                <textarea id="bank" v-model="form.bank" type="text" rows="3"
                                    class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-300 focus:outline-none focus:ring-blue-200 sm:text-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.bank" />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="pix" :value="$t('Site pix')" />
                                <TextInput id="pix" v-model="form.pix" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.pix" />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="paypal" :value="$t('Site paypal')" />
                                <TextInput id="paypal" v-model="form.paypal" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.paypal" />
                        </div>

                        <div class="col-span-2">
                            <InputLabel for="phone" :value="$t('Site phone')" />
                            <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>

                        <div class="col-span-2">
                            <InputLabel for="instagram" :value="$t('Site instagram')" />
                            <TextInput id="instagram" v-model="form.instagram" type="text" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.instagram" />
                        </div>

                        <div class="col-span-2">
                            <InputLabel for="facebook" :value="$t('Site facebook')" />
                            <TextInput id="facebook" v-model="form.facebook" type="text" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.facebook" />
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
</AppSuspense></template>
        