<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, ref } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationTriangleIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const { coins, categories, languages, countries } = defineProps({
    coins: Object,
    categories: Array,
    languages: Array,
    countries: Array,
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

    last_posted: '',

    team: '',

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
});

const submit = () => {
    form.post(route('seller.sites.store'));
}

const offerForm = useForm({
    cost: '',
    cost_coin: 'BRL',
})

const showModal = ref(false)
const exists = ref(null)

const check = async () => {
    try {
        const res = await fetch(route('seller.sites.check', { url: form.url }))
        const data = await res.json()
        exists.value = data.exists
    } catch (error) {
        console.log(error)
        exists.value = null
    }
}

const submitOffer = () => {
    offerForm
        .transform((data) => ({
            ...data,
            url: form.url,
        }))
        .post(route('seller.sites.offer'), {
            preserveState: true,
            onSuccess(res) {
                showModal.value = false
            }
        })
}

const costFormat = computed(() => coins[form.cost_coin])
const offerCostFormat = computed(() => coins[offerForm.cost_coin])

</script>
        
<template>
    <AppSuspense>
        <TransitionRoot as="template" :show="showModal">
            <Dialog as="div" class="relative z-10" @close="showModal = false">
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
                                class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md sm:p-6">
                                <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                                    <button type="button"
                                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        @click="showModal = false">
                                        <span class="sr-only">Close</span>
                                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                    </button>
                                </div>
                                <div>
                                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">{{ $t('Submit offer') }}</DialogTitle>
                                    <div class="mt-2">
                                        <InputLabel for="cost" :value="$t('Cost')" />

                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input v-model.lazy="offerForm.cost" v-money3="offerCostFormat" type="text"
                                                name="cost" id="cost"
                                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                            <div class="absolute inset-y-0 right-0 flex items-center">
                                                <label for="cost_coin" class="sr-only">Moeda</label>
                                                <select v-model="offerForm.cost_coin" id="cost_coin" name="cost_coin"
                                                    class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                    <option value="BRL">BRL</option>
                                                    <option value="EUR">EUR</option>
                                                    <option value="USD">USD</option>
                                                </select>
                                            </div>
                                        </div>

                                        <InputError class="mt-2" :message="offerForm.errors.cost" />
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <button type="button"
                                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="submitOffer">{{ $t('Submit') }}</button>
                                    <button type="button"
                                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm"
                                        @click="showModal = false">{{ $t('Back') }}</button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <SellerLayout :title="$t('Add site')">
            <template #header>
                <div
                    class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $t('Add site') }}
                    </h2>
                </div>
            </template>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form @submit.prevent="submit"
                    class="grid grid-cols-1 sm:grid-cols-6 gap-x-6 gap-y-6 p-6 bg-white border-b border-gray-200">

                    <div class="col-span-6">
                        <InputLabel for="url" :value="$t('URL')" />
                        <TextInput @blur="check" id="url" v-model="form.url" type="text" class="mt-1 block w-full"
                            required autofocus autocomplete="url" />
                        <p v-if="exists" class="mt-2 text-sm text-red-500">Verificamos que já existe um site com essa
                            url. <button type="button" @click="showModal = true" class="text-blue-500">Clique aqui para
                                fazer uma proposta de valor</button></p>
                        <InputError class="mt-2" :message="form.errors.url" />
                    </div>

                    <div class="col-span-6">
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                        <div class="mt-1">

                            <select v-model="form.category_id" id="category_id" name="category_id"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">Selecione</option>
                                <option v-for="(category, index) in categories" :key="index" :value="category.id">{{
                                        category.name
                                }}</option>
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
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">Selecione</option>
                                <option v-for="(language, index) in languages" :key="index" :value="language.id">{{
                                        language.name
                                }}</option>
                            </select>

                        </div>

                        <InputError class="mt-2" :message="form.errors.language_id" />
                    </div>

                    <div class="col-span-3">
                        <label for="country_id" class="block text-sm font-medium text-gray-700">{{ $t('Country')
                        }}</label>
                        <div class="mt-1">

                            <select v-model="form.country_id" id="country_id" name="country_id"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">Selecione</option>
                                <option v-for="(country, index) in countries" :key="index" :value="country.id">{{
                                        country.name
                                }}</option>
                            </select>

                        </div>

                        <InputError class="mt-2" :message="form.errors.country_id" />
                    </div>

                    <div class="col-span-6">
                        <label for="link_type" class="block text-sm font-medium text-gray-700">Tipo de link</label>
                        <div class="mt-1">

                            <select v-model="form.link_type" id="link_type" name="link_type"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">Selecione</option>
                                <option value="DOFOLLOW">DOFOLLOW</option>
                                <option value="NOFOLLOW">NOFOLLOW</option>

                            </select>

                        </div>

                        <InputError class="mt-2" :message="form.errors.link_type" />
                    </div>

                    <div class="col-span-6 space-y-3">
                        <div class="flex items-center space-x-2">
                            <Checkbox id="gambling" v-model:checked="form.gambling" name="gambling" />
                            <InputLabel for="gambling" :value="$t('Gambling')" />
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox id="cdb" v-model:checked="form.cdb" name="cdb" />
                            <InputLabel for="cdb" :value="$t('CDB')" />
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox id="cripto" v-model:checked="form.cripto" name="cripto" />
                            <InputLabel for="cripto" :value="$t('Cripto')" />
                        </div>

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
                        <InputLabel for="cost" :value="$t('Cost')" />

                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input v-model.lazy="form.cost" v-money3="costFormat" type="text" name="cost" id="cost"
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <label for="cost_coin" class="sr-only">Moeda</label>
                                <select v-model="form.cost_coin" id="cost_coin" name="cost_coin"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                    <option value="BRL">BRL</option>
                                    <option value="EUR">EUR</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                        </div>

                        <InputError class="mt-2" :message="form.errors.cost" />
                    </div>

                    <div class="col-span-6">
                        <InputLabel for="last_posted" value="Último post" />
                        <TextInput id="last_posted" v-model="form.last_posted" type="date" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="form.errors.last_posted" />
                    </div>

                    <div class="col-span-6">
                        <InputLabel for="team" :value="$t('Atendimento')"/>
                        <TextInput id="team" v-model="form.team" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="form.errors.team"/>
                    </div>

                    <div class="col-span-6">
                        <InputLabel for="obs" :value="$t('Obs')" />
                        <textarea id="obs" v-model="form.obs" type="text" rows="3"
                            class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>
                        <InputError class="mt-2" :message="form.errors.obs" />
                    </div>

                    <div class="col-span-2">
                        <InputLabel for="owner_name" :value="$t('Owner name')" />
                        <TextInput id="owner_name" v-model="form.owner_name" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="form.errors.owner_name" />
                    </div>

                    <div class="col-span-2">
                        <InputLabel for="owner_email" :value="$t('Owner email')" />
                        <TextInput id="owner_email" v-model="form.owner_email" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="form.errors.owner_email" />
                    </div>

                    <div class="col-span-2">
                        <InputLabel for="owner_phone" :value="$t('Owner phone')" />
                        <TextInput id="owner_phone" v-model="form.owner_phone" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="form.errors.owner_phone" />
                    </div>

                    <div class="col-span-6">
                        <InputLabel for="bank" :value="$t('Site bank')" />
                        <textarea id="bank" v-model="form.bank" type="text" rows="3"
                            class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>
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

                    <div class="col-span-6 flex justify-end">
                        <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                            class="flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">{{
                                    $t('Save')
                            }}</button>
                    </div>
                </form>
            </div>
        </SellerLayout>
    </AppSuspense>
</template>
        