<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed } from 'vue'
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

const { site, coins, note } = defineProps({
    site: Object,
    coins: Object,
    note: Object,
});

const costFormat = computed(() => coins[site.cost_coin])
const saleFormat = computed(() => coins[site.sale_coin])

const form = useForm({
    text: note?.text ?? '',
})

const submit = () => {
    form.put(route('client.notes.update', site.id), {
        preserveScroll: true,
        preserveState: true,
    })
}

</script>
        
<template>
    <AppSuspense>
        <ClientLayout :title="$t('Show site')">
            <template #header>
                <div
                    class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $t('Show site') }}
                    </h2>
                </div>
            </template>

            <div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit"
                        class="grid grid-cols-1 sm:grid-cols-6 gap-x-6 gap-y-6 p-6 bg-white border-b border-gray-200">

                        <div class="col-span-6">
                            <InputLabel for="note" :value="$t('Note')" />
                            <textarea id="note" v-model="form.text" type="text" rows="3"
                                class="disabled:opacity-50 mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>

                        </div>

                        <div class="col-span-6 flex justify-end">

                            <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                                class="ml-2 flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">{{
                                        $t('Save')
                                }}</button>
                        </div>
                    </form>
                </div>

                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent
                        class="grid grid-cols-1 sm:grid-cols-6 gap-x-6 gap-y-6 p-6 bg-white border-b border-gray-200">

                        <div class="col-span-6">
                            <InputLabel for="url" :value="$t('Url')" />
                            <TextInput disabled id="url" :value="site.url" type="text"
                                class="disabled:opacity-50 mt-1 block w-full" required autofocus autocomplete="url" />
                        </div>

                        <div class="col-span-6">
                            <InputLabel for="category" :value="$t('Category')" />
                            <TextInput disabled id="category" :value="site.category.name" type="text"
                                class="disabled:opacity-50 mt-1 block w-full" required autofocus
                                autocomplete="category" />


                        </div>

                        <div class="col-span-3">
                            <InputLabel for="da" :value="$t('DA')" />
                            <TextInput disabled id="da" :value="site.da" type="number"
                                class="disabled:opacity-50 mt-1 block w-full" />

                        </div>

                        <div class="col-span-3">
                            <InputLabel for="dr" :value="$t('DR')" />
                            <TextInput disabled id="dr" :value="site.dr" type="number"
                                class="disabled:opacity-50 mt-1 block w-full" />

                        </div>

                        <div class="col-span-3">
                            <InputLabel for="traffic" :value="$t('Traffic')" />
                            <TextInput disabled id="traffic" :value="site.traffic" type="number"
                                class="disabled:opacity-50 mt-1 block w-full" />

                        </div>

                        <div class="col-span-3">
                            <InputLabel for="tf" :value="$t('TF')" />
                            <TextInput disabled id="tf" :value="site.tf" type="number"
                                class="disabled:opacity-50 mt-1 block w-full" />

                        </div>

                        <div class="col-span-3">
                            <InputLabel for="language" :value="$t('Language')" />
                            <TextInput disabled id="language" :value="site.language.name" type="text"
                                class="disabled:opacity-50 mt-1 block w-full" required autofocus
                                autocomplete="language" />
                        </div>

                        <div class="col-span-3">
                            <InputLabel for="country" :value="$t('Country')" />
                            <TextInput disabled id="country" :value="site.country.name" type="text"
                                class="disabled:opacity-50 mt-1 block w-full" required autofocus
                                autocomplete="country" />
                        </div>

                        <div class="col-span-6">
                            <label for="link_type" class="block text-sm font-medium text-gray-700">Tipo de link</label>
                            <div class="mt-1">

                                <select disabled :value="site.link_type" id="link_type" name="link_type"
                                    class="disabled:opacity-50 mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option :value="null">Selecione</option>
                                    <option value="DOFOLLOW">DOFOLLOW</option>
                                    <option value="NOFOLLOW">NOFOLLOW</option>

                                </select>

                            </div>


                        </div>

                        <div class="col-span-6 space-y-3">
                            <div class="flex items-center space-x-2">
                                <Checkbox id="gambling" disabled :value:checked="site.gambling" name="gambling"
                                    class="disabled:opacity-50" />
                                <InputLabel for="gambling" :value="$t('Gambling')" class="opacity-50" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox id="cdb" disabled :value:checked="site.cdb" name="cdb"
                                    class="disabled:opacity-50" />
                                <InputLabel for="cdb" :value="$t('CDB')" class="opacity-50" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox id="cripto" disabled :value:checked="site.cripto" name="cripto"
                                    class="disabled:opacity-50" />
                                <InputLabel for="cripto" :value="$t('Cripto')" class="opacity-50" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox id="sponsor" disabled :value:checked="site.sponsor" name="sponsor"
                                    class="disabled:opacity-50" />
                                <InputLabel for="sponsor" :value="$t('Sponsor')" class="opacity-50" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox id="menu" disabled :value:checked="site.menu" name="menu"
                                    class="disabled:opacity-50" />
                                <InputLabel for="menu" :value="$t('Menu')" class="opacity-50" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox id="banner" disabled :value:checked="site.banner" name="banner"
                                    class="disabled:opacity-50" />
                                <InputLabel for="banner" :value="$t('Banner')" class="opacity-50" />
                            </div>
                        </div>

                        <div class="col-span-6">
                            <InputLabel for="sale" :value="$t('Sale')" />

                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input disabled :value="site.sale" v-money3="saleFormat" type="text" name="sale"
                                    id="sale"
                                    class="disabled:opacity-50  focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" />
                            </div>
                        </div>

                        <div class="col-span-6">
                            <InputLabel for="last_posted" :value="$t('Last posted')" />
                            <TextInput disabled id="last_posted" :value="site.last_posted" type="date"
                                class="disabled:opacity-50 mt-1 block w-full" />
                        </div>

                        <!-- <div class="col-span-6">
                        <label for="seller_id" class="block text-sm font-medium text-gray-700">{{ $t('Seller')
                        }}</label>
                        <div class="mt-1">
                            <select :value="site.seller_id" id="seller_id" name="seller_id"
                                class="disabled:opacity-50 mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">Selecione</option>
                                <option v-for="(seller, index) in sellers" :key="index" :value="seller.id">{{
                                        seller.name
                                }}</option>
                            </select>
                        </div>
                    </div> -->

                        <div class="col-span-6">
                            <InputLabel for="obs" :value="$t('Obs')" />
                            <textarea disabled id="obs" :value="site.obs" type="text" rows="3"
                                class="disabled:opacity-50 mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>

                        </div>
                    </form>
                </div>
            </div>
        </ClientLayout>
    </AppSuspense>
</template>
        