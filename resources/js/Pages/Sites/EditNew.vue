<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { unformat, format } from 'v-money3';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationTriangleIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import { PencilIcon } from '@heroicons/vue/24/outline';
import { watch } from 'vue';

const { site, coins, categories, languages, countries, sellers, note, types: typesProp } = defineProps({
    site: Object,
    coins: Object,
    categories: Array,
    languages: Array,
    countries: Array,
    sellers: Array,
    teams: Array,
    types: Array,
    note: Object,
});

const form = useForm({
    id: site.id,
    url: site.url,
    category_id: site.category_id,
    da: site.da,
    dr: site.dr,
    traffic: site.traffic,
    tf: site.tf,
    language_id: site.language_id,
    country_id: site.country_id,
    link_type: site.link_type,

    gambling: site.gambling ?? false,
    cdb: site.cdb ?? false,
    cripto: site.cripto ?? false,
    sponsor: site.sponsor ?? false,
    menu: site.menu ?? false,
    banner: site.banner ?? false,

    cost: site.cost,
    cost_coin: site.cost_coin,

    sale: site.sale,
    sale_coin: site.sale_coin,

    last_posted: site.last_posted,
    seller_id: site.seller_id,
    team_id: site.team_id,

    obs: site.obs,

    owner_name: site.owner_name,
    owner_email: site.owner_email,
    owner_phone: site.owner_phone,
    bank: site.bank,
    pix: site.pix,
    phone: site.phone,
    paypal: site.paypal,
    instagram: site.instagram,
    facebook: site.facebook,

    owner_role: site.owner_role,
    promo: site.promo ?? false,
    restrict: site.restrict ?? false,
    restrict_detail: site.restrict_detail,
    archive_article: site.archive_article ?? false,
    archive_due: site.archive_due,
    links: site.links,
    embed: site.embed ?? false,
    images: site.images,
    example_article: site.example_article,
    rules: site.rules,
    google_news: site.google_news ?? false,
    global_account: site.global_account,

    types: typesProp.map(type => {
        
        const t = site.types.find(t => t.id == type.id)

        if(null == t) return type

        type.available = true

        type.cost = t.pivot.cost
        type.cost_coin = t.pivot.cost_coin

        type.sale = t.pivot.sale
        type.sale_coin = t.pivot.sale_coin

        return type
    }),
});

watch(() => form.cost, () => computeSuggested())

const suggested = ref(0)

const computeSuggested = () => {

    let cost = parseInt(unformat(form.cost, coins[form.cost_coin]));

    if(['BRL'].includes(form.cost_coin)) {
        
        cost = (cost) + (cost * 0.30) + 675;

    } else if(['EUR', 'USD'].includes(form.cost_coin)) {

        cost = ((cost + 135) * 0.15) + (cost + 135)
    }

    suggested.value = format(cost, coins[form.cost_coin]);
}

const computeSuggestedForType = (type) => {

    let cost = parseInt(unformat(type.cost, coins[type.cost_coin]));

    if(['BRL'].includes(type.cost_coin)) {
        
        cost = (cost) + (cost * 0.30) + 675;

    } else if(['EUR', 'USD'].includes(type.cost_coin)) {

        cost = ((cost + 135) * 0.15) + (cost + 135)
    }

    type.suggested = format(cost, coins[type.cost_coin]);
}

watch(() => form.cost_coin, () => form.sale_coin = form.cost_coin)

const updateCoinForType = (type) => {
    type.sale_coin = type.cost_coin
} 

const open = ref(false)

const submit = (confirm = false) => {

    // if ((unformat(form.sale, coins[form.sale_coin].value) * 100) < site.suggested) {
    //     if (!confirm) {
    //         open.value = true;
    //         return;
    //     }

    //     open.value = false;
    // }

    form.put(route('sites.update', site.id), {
        onError(error) {
            console.log(error)
        }
    });
}

const approve = () => {
    form.put(route('sites.approve', site.id));
}

const reject = () => {
    form.put(route('sites.reject', site.id));
}

const toggle = () => {
    form.post(route('sites.toggle', site.id));
}

const destroy = () => {
    form.delete(route('sites.destroy', site.id));
}

const noteForm = useForm({
    text: note?.text ?? '',
})

const submitNote = () => {
    noteForm.put(route('notes.update', site.id), {
        preserveScroll: true,
        preserveState: true,
    })
}
</script>
        
<template>
    <AppSuspense>
        <TransitionRoot as="template" :show="open">
            <Dialog as="div" class="relative z-10" @close="open = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                    leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
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
                                class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                                    <button type="button"
                                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        @click="open = false">
                                        <span class="sr-only">Close</span>
                                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                    </button>
                                </div>
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <ExclamationTriangleIcon class="h-6 w-6 text-red-600" aria-hidden="true" />
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">{{
                                            $t('Confirm sale value') }}</DialogTitle>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">Você informou um valor de venda menor que o
                                                valor sugerido.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <button @click="submit(true)" type="button"
                                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">{{
                                            $t('Confirm') }}</button>
                                    <button type="button"
                                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm"
                                        @click="open = false">{{ $t('Cancel') }}</button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <AppLayoutNew :title="$t('Edit site')">

            <div class="max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">

                <div class="flex items-center justify-between px-5">

                    <div class="flex items-center space-x-2">
                        <div class="w-10">
                            <PencilIcon class="h-6 w-6" />
                        </div>

                        <span class="block font-bold">{{ $t('Edit note') }}</span>
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 pb-5">
                    <!-- ml-12 mr-16 -->
                    <div class="">
                        <form @submit.prevent="submitNote" class="grid grid-cols-1 sm:grid-cols-6 gap-x-6 gap-y-6">

                            <div class="col-span-6">
                                <InputLabel for="note" :value="$t('Note')" />
                                <textarea id="note" v-model="noteForm.text" type="text" rows="3"
                                    class="disabled:opacity-50 mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-300 focus:outline-none focus:ring-blue-200 sm:text-sm"></textarea>

                            </div>

                            <div class="col-span-6 flex justify-end">

                                <button :class="{ 'opacity-25': noteForm.processing }" :disabled="noteForm.processing"
                                    type="submit"
                                    class="ml-2 flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">{{
                                        $t('Save')
                                    }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--  -->
            <div class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center justify-between px-5">

                    <div class="flex items-center space-x-2">
                        <div class="w-10">
                            <PencilIcon class="h-6 w-6" />
                        </div>

                        <span class="block font-bold">{{ $t('Edit site') }}</span>
                    </div>

                    <span :class="['text-xl font-bold uppercase', {
                        'text-yellow-500': site.status == 'PENDING',
                        'text-green-500': site.status == 'APPROVED',
                        'text-red-500': site.status == 'REJECTED',
                    }]">{{ site.formatted_status }}</span>

                    <div class="flex space-x-2 justify-end">
                        <form @submit.prevent="toggle" class="space-x-2 flex">

                            <button v-if="site.deleted_at != null" type="submit"
                                class="text-sm font-medium bg-green-500 hover:bg-green-700 px-2 py-1 rounded-md text-white">
                                Reativar
                            </button>

                            <button v-else type="submit"
                                class="text-sm font-medium bg-yellow-500 hover:bg-yellow-700 px-2 py-1 rounded-md text-white">
                                Inativar
                            </button>

                        </form>

                        <form v-if="site.deleted_at != null" @submit.prevent="destroy" class="flex items-center">
                            <button type="submit"
                                class="text-sm font-medium bg-red-500 hover:bg-red-700 px-2 py-1 rounded-md text-white">
                                Apagar
                            </button>
                        </form>
                    </div>

                </div>

                <hr class="my-5">

                <div class="px-5 py-5">
                    <div class="ml-12 mr-16">
                        <form @submit.prevent="submit(false)" class="grid grid-cols-1 sm:grid-cols-6 gap-x-6 gap-y-6">

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
                                        <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.name }}</option>
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

                                <div class="flex items-center space-x-2">
                                    <Checkbox id="promo" v-model:checked="form.promo" name="promo" />
                                    <InputLabel for="promo" :value="$t('Promo')" />
                                </div>

                                <div class="flex items-center space-x-2">
                                    <Checkbox id="google_news" v-model:checked="form.google_news" name="google_news" />
                                    <InputLabel for="google_news" :value="$t('Google News')" />
                                    <InputError class="mt-2" :message="form.errors.google_news" />
                                </div>

                                <div class="flex items-center space-x-2">
                                    <Checkbox id="embed" v-model:checked="form.embed" name="embed" />
                                    <InputLabel for="embed" :value="$t('Embed')" />
                                    <InputError class="mt-2" :message="form.errors.embed" />
                                </div>

                                <div>
                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="restrict" v-model:checked="form.restrict" name="restrict" />
                                        <InputLabel for="restrict" :value="$t('Restriction')" />
                                    </div>
                                    <TextInput v-if="form.restrict" id="restrict_detail" v-model="form.restrict_detail" type="text" class="mt-1 block w-full border-t-0 border-l-0 border-r-0 border-b rounded-none shadow-none focus:ring-0 px-0" :placeholder="$t('Informe sobre as restrição')"/>
                                    <InputError class="mt-2" :message="form.errors.restrict_detail" />
                                </div>

                                <div>
                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="archive_article" v-model:checked="form.archive_article" name="archive_article" />
                                        <InputLabel for="archive_article" :value="$t('Archive article')" />
                                    </div>
                                    <TextInput v-if="form.archive_article" id="archive_due" v-model="form.archive_due" type="text" class="mt-1 block w-full border-t-0 border-l-0 border-r-0 border-b rounded-none shadow-none focus:ring-0 px-0" :placeholder="$t('Informe quanto tempo')"/>
                                    <InputError class="mt-2" :message="form.errors.archive_due" />
                                </div>
                            </div>

                            <div class="col-span-6">
                                <div>
                                    <InputLabel for="links" :value="$t('Number of links in the article')" />

                                    <div class="mt-2 flex space-x-3">
                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.links" :value="3" />
                                            <span class="ml-2 text-sm text-gray-600">3</span>
                                        </label>

                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.links" :value="5" />
                                            <span class="ml-2 text-sm text-gray-600">5</span>
                                        </label>

                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.links" :value="null" />
                                            <span class="ml-2 text-sm text-gray-600 whitespace-nowrap">{{ $t('No limit')
                                            }}</span>
                                        </label>

                                    </div>
                                </div>
                            </div>

                            <div class="col-span-6">
                                <div>
                                    <InputLabel for="images" :value="$t('Maximum number of images in content')"
                                        class="whitespace-nowrap" />

                                    <div class="mt-2 flex space-x-3">
                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.images" :value="1" />
                                            <span class="ml-2 text-sm text-gray-600">1</span>
                                        </label>

                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.images" :value="3" />
                                            <span class="ml-2 text-sm text-gray-600">3</span>
                                        </label>

                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.images" :value="5" />
                                            <span class="ml-2 text-sm text-gray-600 whitespace-nowrap">5</span>
                                        </label>

                                    </div>
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
                                                        <input v-model.lazy="form.cost" v-money3="coins[form.cost_coin]" type="text" name="cost" id="cost"
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
                                                        <input v-model.lazy="form.sale" v-money3="coins[form.sale_coin]" type="text" name="sale" id="sale"
                                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                                            <label for="sale_coin" class="sr-only">Moeda</label>
                                                            <select disabled v-model="form.sale_coin" id="sale_coin" name="sale_coin"
                                                                class="bg-invisible focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                <option value="BRL">BRL</option>
                                                                <option value="EUR">EUR</option>
                                                                <option value="USD">USD</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <p :class="['mt-1 ml-1 text-xs font-medium text-blue-500']">{{ $t('Suggested') }}: {{ suggested }}</p>
                                                </td>
                                            </tr>

                                            <!--  -->
                                            <tr v-for="(type, index) in form.types" :key="index">
                                                <td :class="'border-t border-transparent relative py-4 pl-4 pr-3 text-sm sm:pl-6'">
                                                    <div class="font-medium text-gray-900">
                                                        {{ type.name }}
                                                    </div>

                                                    <div class="absolute -top-px left-6 right-0 h-px bg-gray-200" />
                                                </td>

                                                <td :class="['border-t border-gray-200 px-3 py-3.5 text-sm text-gray-500']">
                                                    <Checkbox :id="type.id" v-model:checked="type.available" :name="type.id" />
                                                </td>

                                                <td :class="['border-t border-gray-200 relative px-3 py-3.5 text-sm text-gray-500']">
                                                    
                                                    <div :class="['relative rounded-md shadow-sm', {'opacity-50': !type.available}]">
                                                        <input @change="computeSuggestedForType(type)" :disabled="!type.available" v-model.lazy="type.cost" v-money3="coins[type.cost_coin]" type="text" name="cost" id="cost"
                                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                                            <label for="cost_coin" class="sr-only">Moeda</label>
                                                            <select @change="updateCoinForType(type)" :disabled="!type.available" v-model="type.cost_coin" id="cost_coin" name="cost_coin"
                                                                class="focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                <option value="BRL">BRL</option>
                                                                <option value="EUR">EUR</option>
                                                                <option value="USD">USD</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                </td>

                                                <td :class="['border-t border-transparent relative px-3 py-3.5 text-sm text-gray-500']">
                                                    
                                                    <div :class="['relative rounded-md shadow-sm', {'opacity-50': !type.available}]">
                                                        <input :disabled="!type.available" v-model.lazy="type.sale" v-money3="coins[type.sale_coin]" type="text" name="sale" id="sale"
                                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                                            <label for="sale_coin" class="sr-only">Moeda</label>
                                                            <select disabled v-model="type.sale_coin" id="sale_coin" name="sale_coin"
                                                                class="bg-invisible focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                <option value="BRL">BRL</option>
                                                                <option value="EUR">EUR</option>
                                                                <option value="USD">USD</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <p v-show="type.available" :class="['mt-1 ml-1 text-xs font-medium text-blue-500']">{{ $t('Suggested') }}: {{ type.suggested ?? '' }}</p>

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

                            <!-- <div class="col-span-6">
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
                            </div> -->

                            <div class="col-span-6">
                                <InputLabel for="obs" :value="$t('Obs')" />
                                <textarea id="obs" v-model="form.obs" type="text" rows="3"
                                    class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-300 focus:outline-none focus:ring-blue-200 sm:text-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.obs" />
                            </div>

                            <div class="col-span-6">
                                <InputLabel for="rules" :value="$t('Rules for content')" />
                                <textarea id="rules" v-model="form.rules" type="text" rows="3"
                                    class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-300 focus:outline-none focus:ring-blue-200 sm:text-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.rules" />
                            </div>

                            <div class="col-span-6">
                                <InputLabel for="example_article" :value="$t('Example article')" />
                                <TextInput id="example_article" v-model="form.example_article" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.example_article" />
                            </div>

                            <div class="col-span-4">
                                <InputLabel for="owner_name" :value="$t('Owner name')" />
                                <TextInput id="owner_name" v-model="form.owner_name" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_name" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="owner_role" :value="$t('Owner role')" />
                                <TextInput id="owner_role" v-model="form.owner_role" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_role" />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="owner_email" :value="$t('Owner email')" />
                                <TextInput id="owner_email" v-model="form.owner_email" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_email" />
                            </div>

                            <div class="col-span-3">
                                <InputLabel for="owner_phone" :value="$t('Owner phone')" />
                                <TextInput id="owner_phone" v-model="form.owner_phone" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_phone" />
                            </div>

                            <div class="col-span-6">
                                <InputLabel for="bank" :value="$t('Site bank')" />
                                <textarea id="bank" v-model="form.bank" rows="3"
                                    class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-300 focus:outline-none focus:ring-blue-200 sm:text-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.bank" />
                            </div>

                            <div class="col-span-6">
                                <InputLabel for="global_account" :value="$t('Global account')" />
                                <TextInput id="global_account" v-model="form.global_account" type="text"
                                    class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-300 focus:outline-none focus:ring-blue-200 sm:text-sm"/>
                                <InputError class="mt-2" :message="form.errors.global_account" />
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

                                <button v-if="site.status == 'PENDING'" @click="reject" type="button"
                                    class="mr-3 bg-red-600 border border-transparent rounded-md shadow-sm py-3 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">{{
                                        $t('Reject') }}</button>

                                <button v-if="site.status == 'PENDING'" @click="approve" type="button"
                                    class="mr-3 bg-green-600 border border-transparent rounded-md shadow-sm py-3 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">{{
                                        $t('Approve') }}</button>

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
        