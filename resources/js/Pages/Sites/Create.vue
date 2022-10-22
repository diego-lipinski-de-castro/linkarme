<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, ref, watch } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

const { coins, categories, languages, countries, sellers } = defineProps({
    coins: Object,
    categories: Array,
    languages: Array,
    countries: Array,
    sellers: Array,
});

const form = useForm({
    url: '',
    da: '',
    dr: '',
    traffic: '',
    tf: '',
    gambling: false,
    cdb: false,
    cripto: false,
    sponsor: false,
    menu: false,
    banner: false,
    owner_name: '',
    owner_email: '',
    owner_phone: '',
    bank: '',
    pix: '',
    phone: '',
    paypal: '',
    instagram: '',
    facebook: '',
});

const submit = () => {
    form.post(route('sites.store'));
}

const cost = ref('')
const sale = ref('')

const costCoin = ref('BRL')
const saleCoin = ref('BRL')

const costFormat = computed(() => coins[costCoin.value])
const saleFormat = computed(() => coins[saleCoin.value])

</script>
        
<template>
    <AppSuspense>
        <AppLayout :title="$t('Add site')">
            <template #uppermenu>
                
            </template>

            <template #header>
                <div
                    class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $t('Add site') }}
                    </h2>
                </div>
            </template>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form @submit.prevent="submit" class="flex flex-col space-y-6 p-6 bg-white border-b border-gray-200">
                    
                    <div>
                        <InputLabel for="url" :value="$t('URL')" />
                        <TextInput id="url" v-model="form.url" type="text" class="mt-1 block w-full" required autofocus autocomplete="url" />
                        <InputError class="mt-2" :message="form.errors.url" />
                    </div>

                    <div>
                        <InputLabel for="da" :value="$t('DA')"/>
                        <TextInput id="da" v-model="form.da" type="number" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.da"/>
                    </div>

                    <div>
                        <InputLabel for="dr" :value="$t('DR')"/>
                        <TextInput id="dr" v-model="form.dr" type="number" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.dr"/>
                    </div>

                    <div>
                        <InputLabel for="traffic" :value="$t('Traffic')"/>
                        <TextInput id="traffic" v-model="form.traffic" type="number" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.traffic"/>
                    </div>

                    <div>
                        <InputLabel for="tf" :value="$t('TF')"/>
                        <TextInput id="tf" v-model="form.tf" type="number" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.tf"/>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <Checkbox id="gambling" v-model:checked="form.gambling" name="gambling" />
                            <InputLabel for="gambling" :value="$t('Gambling')"/>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox id="cdb" v-model:checked="form.cdb" name="cdb"/>
                            <InputLabel for="cdb" :value="$t('CDB')"/>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox id="cripto" v-model:checked="form.cripto" name="cripto"/>
                            <InputLabel for="cripto" :value="$t('Cripto')"/>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox id="sponsor" v-model:checked="form.sponsor" name="sponsor"/>
                            <InputLabel for="sponsor" :value="$t('Sponsor')"/>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox id="menu" v-model:checked="form.menu" name="menu"/>
                            <InputLabel for="menu" :value="$t('Menu')"/>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <Checkbox id="banner" v-model:checked="form.banner" name="banner" />
                            <InputLabel for="banner" :value="$t('Banner')"/>
                        </div>
                    </div>

                    <!-- <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                        <div class="mt-1">

                            <select id="category_id" name="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('category_id') border-red-300 @enderror>
                                <option value="">Selecione</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        @error('category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="language_id" class="block text-sm font-medium text-gray-700">Linguagem</label>
                        <div class="mt-1">

                            <select id="language_id" name="language_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('language_id') border-red-300 @enderror>
                                <option value="">Selecione</option>
                                @foreach ($languages as $language)
                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        @error('language_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="country_id" class="block text-sm font-medium text-gray-700">País</label>
                        <div class="mt-1">

                            <select id="country_id" name="country_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('country_id') border-red-300 @enderror>
                                <option value="">Selecione</option>
                                @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        @error('country_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="link_type" class="block text-sm font-medium text-gray-700">Tipo de link</label>
                        <div class="mt-1">

                            <select id="link_type" name="link_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('link_type') border-red-300 @enderror>
                                <option value="">Selecione</option>
                                <option value="DOFOLLOW">DOFOLLOW</option>
                                <option value="NOFOLLOW">NOFOLLOW</option>

                            </select>

                        </div>

                        @error('link_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div> -->

                    <div>
                        <InputLabel for="cost" :value="$t('Cost')"/>
                        <!-- <TextInput id="owner_email" v-model="form.owner_email" type="text" class="mt-1 block w-full"/> -->

                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input v-model.lazy="cost" v-money="costFormat" type="text" name="cost" id="cost" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('cost') border-red-300 @enderror" />

                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <label for="cost_coin" class="sr-only">Moeda</label>
                                <select v-model="costCoin" id="cost_coin" name="cost_coin" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                    <option value="BRL">BRL</option>
                                    <option value="EUR">EUR</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                        </div>

                        <InputError class="mt-2" :message="form.errors.cost"/>
                    </div>

                    <div>
                        <label for="sale" class="block text-sm font-medium text-gray-700">Venda</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input v-model.lazy="sale" v-money="saleFormat" type="text" name="sale" id="sale" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('sale') border-red-300 @enderror" />

                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <label for="sale_coin" class="sr-only">Moeda</label>
                                <select v-model="saleCoin" id="sale_coin" name="sale_coin" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                    <option value="BRL">BRL</option>
                                    <option value="EUR">EUR</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                        </div>

                        @error('sale')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- <div>
                        <label for="last_posted" class="block text-sm font-medium text-gray-700">Último post</label>
                        <div class="mt-1">
                            <input type="date" name="last_posted" id="last_posted" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('last_posted') border-red-300 @enderror" />
                        </div>

                        @error('last_posted')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="seller_id" class="block text-sm font-medium text-gray-700">Vendedor</label>
                        <div class="mt-1">

                            <select id="seller_id" name="seller_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @error('seller_id') border-red-300 @enderror>
                                <option value="">Selecione</option>
                                @foreach ($sellers as $seller)
                                <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        @error('seller_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="obs" class="block text-sm font-medium text-gray-700">Observações</label>
                        <div class="mt-1">
                            <textarea type="text" name="obs" id="obs" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('obs') border-red-300 @enderror" placeholder="Insira suas anotações aqui"></textarea>
                        </div>

                        @error('obs')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admin_obs" class="block text-sm font-medium text-gray-700">Notas</label>
                        <div class="mt-1">
                            <textarea type="text" name="admin_obs" id="admin_obs" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('admin_obs') border-red-300 @enderror" placeholder="Insira suas anotações aqui"></textarea>
                        </div>

                        @error('admin_obs')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    -->

                    <div>
                        <InputLabel for="owner_name" :value="$t('Nome dono')"/>
                        <TextInput id="owner_name" v-model="form.owner_name" type="text" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.owner_name"/>
                    </div>

                    <div>
                        <InputLabel for="owner_email" :value="$t('E-mail dono')"/>
                        <TextInput id="owner_email" v-model="form.owner_email" type="text" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.owner_email"/>
                    </div>

                    <div>
                        <InputLabel for="owner_phone" :value="$t('Telefone dono')"/>
                        <TextInput id="owner_phone" v-model="form.owner_phone" type="text" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.owner_phone"/>
                    </div>

                    <div>
                        <InputLabel for="bank" :value="$t('Informações bancárias')"/>
                        <TextInput id="bank" v-model="form.bank" type="text" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.bank"/>
                    </div>

                    <div>
                        <InputLabel for="pix" :value="$t('PIX site')"/>
                        <TextInput id="pix" v-model="form.pix" type="text" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.pix"/>
                    </div>

                    <div>
                        <InputLabel for="phone" :value="$t('Telefone site')"/>
                        <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.phone"/>
                    </div>

                    <div>
                        <InputLabel for="paypal" :value="$t('Paypal site')"/>
                        <TextInput id="paypal" v-model="form.paypal" type="text" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.paypal"/>
                    </div>

                    <div>
                        <InputLabel for="instagram" :value="$t('Instagram site')"/>
                        <TextInput id="instagram" v-model="form.instagram" type="text" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.instagram"/>
                    </div>

                    <div>
                        <InputLabel for="facebook" :value="$t('Facebook site')"/>
                        <TextInput id="facebook" v-model="form.facebook" type="text" class="mt-1 block w-full"/>
                        <InputError class="mt-2" :message="form.errors.facebook"/>
                    </div>

                    <div class="flex justify-end">
                        <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                            class="flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">{{ $t('Save') }}</button>
                    </div>
                </form>
            </div>
        </AppLayout>
    </AppSuspense>
</template>
        