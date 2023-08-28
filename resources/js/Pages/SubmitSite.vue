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
    first_name: '',
    last_name: '',
    name: '',
    birthday: '',
    phone: '',
    email: '',
    password: '',
    password_confirmation: '',
    locale: 'en',
    coin: 'USD',
    notify_updates_via_email: null,
});

const submit = () => {
    // form.post(route('submitSite.create'));
};
</script>

<template>
    <Head :title="$t('Login')" />

    <div class="min-h-full relative flex justify-center">
        <div class="z-0 fixed inset-0">
            <img class="h-full w-full object-cover" src="@/assets/images/login-bg.jpg" alt="Background" />
        </div>

        <div class="z-10 absolute flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8 sm:w-full sm:max-w-xl">
            <div class="mx-5 sm:mx-auto sm:w-full">
                <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">

                    <div class="sm:mx-auto sm:w-full">
                        <ApplicationLogoNew class="mx-auto h-12 w-auto" />
                    </div>

                    <form @submit.prevent="submit" class="mt-8 space-y-6">

                        <div v-if="status" class="font-medium text-sm text-green-600">
                            {{ status }}
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                            <div v-if="seller !== null" class="col-span-2">
                                <InputLabel for="seller" :value="$t('Vendedor')" class="text-center" />
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
                                <InputLabel for="owner_name" :value="$t('Responsável')" />
                                <TextInput id="owner_name" v-model="form.owner_name" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_name" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="owner_role" :value="$t('Cargo do responsável')" />
                                <TextInput id="owner_role" v-model="form.owner_role" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.owner_role" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="email" :value="$t('E-mail')" />
                                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="whatsapp" :value="$t('Whatsapp')" />
                                <TextInput id="whatsapp" v-model="form.whatsapp" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.whatsapp" />
                            </div>

                            <div class="col-span-2">

                                <div class="space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="accepts_promo" v-model:checked="form.accepts_promo"
                                            name="accepts_promo" />
                                        <InputLabel for="accepts_promo"
                                            :value="$t('Aceita conteúdos de caráter promocional?')" />
                                    </div>

                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <Checkbox id="restriction" v-model:checked="form.restriction"
                                                name="restriction" />
                                            <InputLabel for="restriction"
                                                :value="$t('Restrição ou exigência quanto ao tamanho do artigo?')" />
                                        </div>

                                        <TextInput v-if="form.restriction" id="restriction_reason"
                                            v-model="form.restriction_reason" type="text"
                                            class="mt-1 block w-full border-t-0 border-l-0 border-r-0 border-b rounded-none shadow-none focus:ring-0 px-0"
                                            placeholder="Informe sobre as restrição" />
                                        <InputError v-if="form.restriction" class="mt-2"
                                            :message="form.errors.restriction_reason" />
                                    </div>

                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <Checkbox id="article_persists" v-model:checked="form.article_persists"
                                                name="article_persists" />
                                            <InputLabel for="article_persists"
                                                :value="$t('Artigo fica no arquivo do site após a publicação?')" />
                                        </div>

                                        <TextInput v-if="form.article_persists" id="article_persists_time"
                                            v-model="form.article_persists_time" type="text"
                                            class="mt-1 block w-full border-t-0 border-l-0 border-r-0 border-b rounded-none shadow-none focus:ring-0 px-0"
                                            placeholder="Informe quanto tempo" />
                                        <InputError v-if="form.article_persists" class="mt-2"
                                            :message="form.errors.article_persists_time" />
                                    </div>
                                </div>

                            </div>

                            <div>
                                <InputLabel for="article_link_amount" :value="$t('Quantidade de links no artigo')" />

                                <div class="mt-2 grid grid-cols-1 gap-2">
                                    <label class="flex items-center">
                                        <input type="radio" v-model="form.article_link_amount" :value="3" />
                                        <span class="ml-2 text-sm text-gray-600">3</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="radio" v-model="form.article_link_amount" :value="5" />
                                        <span class="ml-2 text-sm text-gray-600">5</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="radio" v-model="form.article_link_amount" :value="null" />
                                        <span class="ml-2 text-sm text-gray-600 whitespace-nowrap">{{ $t('Sem limites')
                                        }}</span>
                                    </label>

                                </div>
                            </div>

                            <div class="col-span-2">
                                <div class="flex items-center space-x-2">
                                    <Checkbox id="article_video_enabled" v-model:checked="form.article_video_enabled"
                                        name="article_video_enabled" />
                                    <InputLabel for="article_video_enabled"
                                        :value="$t('Permitido incorporar vídeos no artigo (youtube)')" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="article_image_amount" :value="$t('Número máximo de imagens no contéudo')"
                                    class="whitespace-nowrap" />

                                <div class="mt-2 grid grid-cols-1 gap-2">
                                    <label class="flex items-center">
                                        <input type="radio" v-model="form.article_image_amount" :value="1" />
                                        <span class="ml-2 text-sm text-gray-600">1</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="radio" v-model="form.article_image_amount" :value="3" />
                                        <span class="ml-2 text-sm text-gray-600">3</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="radio" v-model="form.article_image_amount" :value="5" />
                                        <span class="ml-2 text-sm text-gray-600 whitespace-nowrap">5</span>
                                    </label>

                                </div>
                            </div>

                            <div class="col-span-2">
                                <div class="flex items-center space-x-2">
                                    <Checkbox id="article_sponsor" v-model:checked="form.article_sponsor"
                                        name="article_sponsor" />
                                    <InputLabel for="article_sponsor"
                                        :value="$t('A publicação é marcada como publicidade?')" />
                                </div>
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="link_example"
                                    :value="$t('Link com exemplo de uma publicação que já tenha sido feita')" />
                                <TextInput id="link_example" v-model="form.link_example" type="text"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.link_example" />
                            </div>

                            <div class="col-span-2 ">
                                <InputLabel :value="$t('Assinale os segmentos de links que são aceitos dentro do texto')" />

                                <div class="mt-2 space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="cbd" v-model:checked="form.cbd" name="cbd" />
                                        <InputLabel for="cbd" :value="$t('CBD')" />
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="gambling" v-model:checked="form.gambling" name="gambling" />
                                        <InputLabel for="gambling" :value="$t('Cassinos')" />
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="sports" v-model:checked="form.sports" name="sports" />
                                        <InputLabel for="sports" :value="$t('Apostas esportivas')" />
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="dates" v-model:checked="form.dates" name="dates" />
                                        <InputLabel for="dates" :value="$t('Encontros (tipo Tinder)')" />
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="escorts" v-model:checked="form.escorts" name="escorts" />
                                        <InputLabel for="escorts" :value="$t('Acompanhantes (tipo Fatal Models)')" />
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="cripto" v-model:checked="form.cripto" name="cripto" />
                                        <InputLabel for="cripto" :value="$t('Criptomoedas')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="rules" :value="$t('Regras para conteúdo')" />
                                <textarea id="rules" v-model="form.rules" type="rules" class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.rules" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="obs" :value="$t('Observações')" />
                                <textarea id="obs" v-model="form.obs" type="obs" class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>
                                <InputError class="mt-2" :message="form.errors.obs" />
                            </div>

                            <div class="col-span-2">
                                <InputLabel for="cost" :value="$t('Valor negociado')" />
                                <TextInput id="cost" v-money3="coins['BRL']" v-model="form.cost" type="text" class="mt-1 block w-full" />
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
                                <InputLabel for="global_account" :value="$t('Conta global')" />
                                <TextInput id="global_account" v-model="form.global_account" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.global_account" />
                            </div>
                            
                        </div>

                        <div>
                            <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit"
                                class="flex w-full justify-center rounded-md border border-transparent bg-zinc-900 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 transition-colors">{{
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
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md overflow-hidden bg-white border border-gray-300 shadow-sm focus:outline-none">

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