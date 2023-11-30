<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import ApplicationLogoNew from '@/Components/ApplicationLogoNew.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/24/outline';
import { useLanguageStore } from '@/stores/language'

const languageStore = useLanguageStore()

const props = defineProps({
    status: String,
    categories: Array,
    languages: Array,
    countries: Array,
    sellers: Array,
    teams: Array,
    types: Array,
    coins: Object,
    seller: Object,
});

const form = useForm({
    seller: props.seller?.id,

    url: '',

    owner_name: '',
    owner_role: '',
    owner_email: '',
    owner_phone: '',

    promo: false,

    restrict: false,
    restrict_detail: '',

    archive_article: false,
    archive_due: '',

    links: null,
    embed: false,
    images: null,
    sponsor: false,

    example_article: '',

    rules: '',
    obs: '',

    google_news: false,

    cost: '',
    cost_coin: 'BRL',

    pix: '',
    paypal: '',
    global_account: '',

    types: props.types
});

const submit = () => {
    form.post(route('submitSite.create'));
};
</script>

<template>
    <Head :title="$t('Login')" />

    <div class="min-h-full relative flex justify-center">
        <div class="z-0 fixed inset-0">
            <img class="h-full w-full object-cover" src="@/assets/images/login-bg.jpg" alt="Background" />
        </div>

        <div class="z-10 absolute flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8 sm:w-full sm:max-w-xl">
            <div class="relative mx-5 sm:mx-auto sm:w-full">

                <div class="absolute top-0 -right-14">
                    <Menu as="div" class="relative ml-3">
                        <div class="bg-white/10 rounded-lg">
                            <MenuButton
                                class="flex max-w-xs items-center rounded-full text-sm focus:outline-none lg:rounded-md lg:p-2">
                                <span class="block w-8 h-8">
                                    <img v-if="languageStore.language == 'en'"
                                        src="data:image/png;base64,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" />

                                    <img v-if="languageStore.language == 'pt'"
                                        src="data:image/png;base64,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" />

                                    <img v-if="languageStore.language == 'es'"
                                        src="data:image/png;base64,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" />
                                </span>
                            </MenuButton>
                        </div>
                        <transition enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95">
                            <MenuItems
                                class="absolute right-0 z-10 mt-2 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">

                                <MenuItem v-slot="{ active }">
                                <button @click="languageStore.loadLanguage('en')"
                                    :class="[active ? 'bg-gray-100' : '', 'block px-2 py-2 w-12 h-12']">
                                    <img
                                        src="data:image/png;base64,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">
                                </button>
                                </MenuItem>

                                <MenuItem v-slot="{ active }">
                                <button @click="languageStore.loadLanguage('pt')"
                                    :class="[active ? 'bg-gray-100' : '', 'block px-2 py-2 w-12 h-12']">
                                    <img
                                        src="data:image/png;base64,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">
                                </button>
                                </MenuItem>

                                <MenuItem v-slot="{ active }">
                                <button @click="languageStore.loadLanguage('es')"
                                    :class="[active ? 'bg-gray-100' : '', 'block px-2 py-2 w-12 h-12']">
                                    <img
                                        src="data:image/png;base64,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">
                                </button>
                                </MenuItem>

                            </MenuItems>
                        </transition>
                    </Menu>
                </div>

                <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">

                    <div class="sm:mx-auto sm:w-full">
                        <ApplicationLogoNew class="mx-auto h-12 w-auto" />

                        <h3 class="font-bold text-center uppercase mt-3 text-xl">Submit your site</h3>
                    </div>

                    <form @submit.prevent="submit" class="mt-8 space-y-6">

                        <div v-if="status" class="font-medium text-sm text-green-600">
                            {{ status }}
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                            <div v-if="seller !== null" class="col-span-2">
                                <InputLabel for="seller" :value="$t('Seller')" class="text-center" />
                                <TextInput id="seller" :value="seller?.name" type="text"
                                    class="-mt-1 block w-full text-center border-0 shadow-none text-gray-400 italic focus:ring-0 cursor-default"
                                    readonly />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="url" :value="$t('URL')" />
                                <TextInput id="url" v-model="form.url" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.url" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="owner_name" :value="$t('Company representative')" />
                                <TextInput id="owner_name" v-model="form.owner_name" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_name" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="owner_role" :value="$t('Position in the company')" />
                                <TextInput id="owner_role" v-model="form.owner_role" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_role" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="owner_email" :value="$t('Email')" />
                                <TextInput id="owner_email" v-model="form.owner_email" type="email" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_email" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="owner_phone" :value="$t('Whatsapp')" />
                                <TextInput id="owner_phone" v-model="form.owner_phone" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_phone" />
                            </div>

                            <div class="col-span-2">

                                <div class="space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="promo" v-model:checked="form.promo"
                                            name="promo" />
                                        <InputLabel for="promo"
                                            :value="$t('Does the website accept promotional content?')" />
                                    </div>

                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <Checkbox id="restrict" v-model:checked="form.restrict"
                                                name="restrict" />
                                            <InputLabel for="restrict"
                                                :value="$t('Restriction or requirement regarding the length of the article?')" />
                                        </div>

                                        <TextInput v-if="form.restrict" id="restrict_detail"
                                            v-model="form.restrict_detail" type="text"
                                            class="mt-1 block w-full border-t-0 border-l-0 border-r-0 border-b rounded-none shadow-none focus:ring-0 px-0"
                                            :placeholder="$t('Informe sobre as restrição')" />
                                        <InputError v-if="form.restrict" class="mt-2"
                                            :message="form.errors.restrict_detail" />
                                    </div>

                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <Checkbox id="archive_article" v-model:checked="form.archive_article"
                                                name="archive_article" />
                                            <InputLabel for="archive_article"
                                                :value="$t('Does the article remain in the website archive after publication?')" />
                                        </div>

                                        <TextInput v-if="form.archive_article" id="archive_due"
                                            v-model="form.archive_due" type="text"
                                            class="mt-1 block w-full border-t-0 border-l-0 border-r-0 border-b rounded-none shadow-none focus:ring-0 px-0"
                                            placeholder="Informe quanto tempo" />
                                        <InputError v-if="form.archive_article" class="mt-2"
                                            :message="form.errors.archive_due" />
                                    </div>
                                </div>

                            </div>

                            <div>
                                <InputLabel for="links" :value="$t('Number of links in the article')" />

                                <div class="mt-2 grid grid-cols-1 gap-2">
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

                            <div class="col-span-2">
                                <div class="flex items-center space-x-2">
                                    <Checkbox id="embed" v-model:checked="form.embed"
                                        name="embed" />
                                    <InputLabel for="embed"
                                        :value="$t('Allowed to embed videos in the article (YouTube)')" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="images" :value="$t('Maximum number of images in content')"
                                    class="whitespace-nowrap" />

                                <div class="mt-2 grid grid-cols-1 gap-2">
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

                            <div class="col-span-2">
                                <div class="flex items-center space-x-2">
                                    <Checkbox id="sponsor" v-model:checked="form.sponsor"
                                        name="sponsor" />
                                    <InputLabel for="sponsor"
                                        :value="$t('Is the post marked as advertising?')" />
                                </div>
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="example_article"
                                    :value="$t('Link with example of a publication that has already been made')" />
                                <TextInput id="example_article" v-model="form.example_article" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.example_article" />
                            </div>

                            <div class="col-span-2 ">
                                <InputLabel :value="$t('Select the link segments that are accepted within the text')" />

                                <div class="mt-2 space-y-3">
                                    <div v-for="(type, index) in types" :key="index" class="flex items-center space-x-2">
                                        <Checkbox :id="`type-${type.id}`" v-model:checked="form.types[index].available"
                                            :name="`type-${type.id}`" />
                                        <InputLabel :for="`type-${type.id}`" :value="$t(type.name)" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="rules" :value="$t('Rules for content')" />
                                <textarea id="rules" v-model="form.rules" type="rules"
                                    class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.rules" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="obs" :value="$t('Comments')" />
                                <textarea id="obs" v-model="form.obs" type="obs"
                                    class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.obs" />
                            </div>

                            <div class="col-span-2">
                                <div class="flex items-center space-x-2">
                                    <Checkbox id="google_news" v-model:checked="form.google_news" name="google_news" />
                                    <InputLabel for="google_news" :value="$t('Google News?')" />
                                </div>
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="cost" :value="$t('Negotiated value')" />

                                <div class="mt-1 relative rounded-md shadow-sm">
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

                                <InputError class="mt-2" :message="form.errors.cost" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="pix" :value="$t('PIX')" />
                                <TextInput id="pix" v-model="form.pix" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.pix" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="paypal" :value="$t('Paypal')" />
                                <TextInput id="paypal" v-model="form.paypal" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.paypal" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="global_account" :value="$t('Global account')" />
                                <TextInput id="global_account" v-model="form.global_account" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.global_account" />
                            </div>

                        </div>

                        <div>
                            <button :disabled="form.processing" type="submit"
                                class="flex w-full justify-center rounded-md border border-transparent bg-zinc-900 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition-colors disabled:opacity-25">{{
                                    $t('Submit') }}</button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300" />
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="bg-white px-2 text-gray-500">{{ $t('Need some help?') }}</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <Menu as="div" class="relative flex-shrink-0">
                                <MenuButton
                                    class="inline-flex w-full justify-center items-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-500 shadow-sm hover:bg-gray-50">
                                    <span>{{ $t('Our team is here') }}</span>
                                    <ChevronDownIcon class="ml-3 h-4 w-4" />
                                </MenuButton>

                                <transition enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-48 origin-x-right rounded-md overflow-hidden bg-white border border-gray-300 shadow-sm focus:outline-none">

                                        <MenuItem v-slot="{ active }">
                                        <a target="_blank"
                                            href="https://wa.me/5547989104747?text=Ol%C3%A1%21+Preciso+de+ajuda+para+logar+no+sistema"
                                            :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm font-bold text-gray-700']">
                                            <span>{{ $t('Whatsapp') }}</span>
                                        </a>
                                        </MenuItem>

                                        <MenuItem v-slot="{ active }">
                                        <a target="_blank" href="https://t.me/maxpiress"
                                            :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm font-bold text-gray-700']">
                                            <span>{{ $t('Telegram') }}</span>
                                        </a>
                                        </MenuItem>

                                        <MenuItem v-slot="{ active }">
                                        <a target="_blank" href="https://msng.link/o/?maxpires=sk"
                                            :class="[active ? 'bg-gray-100' : '', 'flex space-x-2 py-2 px-4 text-sm font-bold text-gray-700']">
                                            <span>{{ $t('Skype') }}</span>
                                        </a>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div></template>