<script setup>
import ClientLayoutNew from '@/Layouts/ClientLayoutNew.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { computed } from 'vue'
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { PencilIcon } from '@heroicons/vue/24/outline';

const { site, coins, note } = defineProps({
    site: Object,
    coins: Object,
    note: Object,
});

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
        <ClientLayoutNew :title="$t('Show site')">
            <template #header>
                <div
                    class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $t('Show site') }}
                    </h2>
                </div>
            </template>

            <div>
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
                            <form @submit.prevent="submit" class="grid grid-cols-1 sm:grid-cols-6 gap-x-6 gap-y-6">

                                <div class="col-span-6">
                                    <InputLabel for="note" :value="$t('Note')" />
                                    <textarea id="note" v-model="form.text" type="text" rows="3"
                                        class="disabled:opacity-50 mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-300 focus:outline-none focus:ring-blue-200 sm:text-sm"></textarea>

                                </div>

                                <div class="col-span-6 flex justify-end">

                                    <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                        type="submit"
                                        class="ml-2 flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">{{
                                            $t('Save')
                                        }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                    <form @submit.prevent
                        class="grid grid-cols-1 sm:grid-cols-6 gap-x-6 gap-y-6 p-6 bg-white border-b border-gray-200">

                        <div class="col-span-6">
                            <InputLabel for="url" :value="$t('Url')" />
                            <TextInput disabled id="url" :value="site.real_url" type="text"
                                class="disabled:opacity-50 mt-1 block w-full" required autofocus autocomplete="url" />
                        </div>

                        <div class="col-span-6">
                            <InputLabel for="category" :value="$t('Category')" />
                            <TextInput disabled id="category" :value="site.category?.name" type="text"
                                class="disabled:opacity-50 mt-1 block w-full" required autofocus autocomplete="category" />


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
                            <TextInput disabled id="language" :value="site.language?.name" type="text"
                                class="disabled:opacity-50 mt-1 block w-full" required autofocus autocomplete="language" />
                        </div>

                        <div class="col-span-3">
                            <InputLabel for="country" :value="$t('Country')" />
                            <TextInput disabled id="country" :value="site.country?.name" type="text"
                                class="disabled:opacity-50 mt-1 block w-full" required autofocus autocomplete="country" />
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
                                <Checkbox disabled id="sponsor" :checked="site.sponsor" name="sponsor"
                                    class="disabled:opacity-50" />
                                <InputLabel for="sponsor" :value="$t('Sponsor')" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox disabled id="menu" :checked="site.menu" name="menu" class="disabled:opacity-50" />
                                <InputLabel for="menu" :value="$t('Menu')" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox disabled id="banner" :checked="site.banner" name="banner"
                                    class="disabled:opacity-50" />
                                <InputLabel for="banner" :value="$t('Banner')" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox disabled id="promo" :checked="site.promo" name="promo"
                                    class="disabled:opacity-50" />
                                <InputLabel for="promo" :value="$t('Promo')" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox disabled id="google_news" :checked="site.google_news" name="google_news"
                                    class="disabled:opacity-50" />
                                <InputLabel for="google_news" :value="$t('Google News')" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox disabled id="embed" :checked="site.embed" name="embed"
                                    class="disabled:opacity-50" />
                                <InputLabel for="embed" :value="$t('Embed')" />
                            </div>

                            <div>
                                <div class="flex items-center space-x-2">
                                    <Checkbox disabled id="restrict" :checked="site.restrict" name="restrict"
                                        class="disabled:opacity-50" />
                                    <InputLabel for="restrict" :value="$t('Restriction')" />
                                </div>
                                <TextInput disabled v-if="site.restrict" id="restrict_detail" :value="site.restrict_detail"
                                    type="text"
                                    class="mt-1 block w-full border-t-0 border-l-0 border-r-0 border-b rounded-none shadow-none focus:ring-0 px-0 disabled:opacity-50"
                                    :placeholder="$t('Informe sobre as restrição')" />
                            </div>

                            <div>
                                <div class="flex items-center space-x-2">
                                    <Checkbox disabled id="archive_article" :checked="site.archive_article"
                                        name="archive_article" class="disabled:opacity-50" />
                                    <InputLabel for="archive_article" :value="$t('Archive article')" />
                                </div>
                                <TextInput disabled v-if="site.archive_article" id="archive_due" :value="site.archive_due"
                                    type="text"
                                    class="mt-1 block w-full border-t-0 border-l-0 border-r-0 border-b rounded-none shadow-none focus:ring-0 px-0 disabled:opacity-50"
                                    :placeholder="$t('Informe quanto tempo')" />
                            </div>
                        </div>

                        <div class="col-span-6">
                            <div>
                                <InputLabel for="links" :value="$t('Number of links in the article')" />

                                <div class="mt-2 flex space-x-3">
                                    <label class="flex items-center">
                                        <input disabled type="radio" v-model="site.links" :value="3"
                                            class="disabled:opacity-50" />
                                        <span class="ml-2 text-sm text-gray-600">3</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input disabled type="radio" v-model="site.links" :value="5"
                                            class="disabled:opacity-50" />
                                        <span class="ml-2 text-sm text-gray-600">5</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input disabled type="radio" v-model="site.links" :value="null"
                                            class="disabled:opacity-50" />
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
                                        <input disabled type="radio" v-model="site.images" :value="1"
                                            class="disabled:opacity-50" />
                                        <span class="ml-2 text-sm text-gray-600">1</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input disabled type="radio" v-model="site.images" :value="3"
                                            class="disabled:opacity-50" />
                                        <span class="ml-2 text-sm text-gray-600">3</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input disabled type="radio" v-model="site.images" :value="5"
                                            class="disabled:opacity-50" />
                                        <span class="ml-2 text-sm text-gray-600 whitespace-nowrap">5</span>
                                    </label>

                                </div>
                            </div>
                        </div>

                        <div class="col-span-6">

                            <label for="link_type" class="block text-sm font-medium text-gray-700">{{ $t('Pricing table')
                            }}</label>

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

                                                <div class="relative">
                                                    {{ $filters.currency(site.sale / 100, coins[site.sale_coin]) }}
                                                </div>

                                            </td>

                                        </tr>

                                        <!--  -->
                                        <tr v-for="(type, index) in site.types" :key="index">
                                            <td
                                                :class="'border-t border-transparent relative py-4 pl-4 pr-3 text-sm sm:pl-6'">
                                                <div class="font-medium text-gray-900">
                                                    {{ type.name }}
                                                </div>

                                                <div class="absolute -top-px left-6 right-0 h-px bg-gray-200" />
                                            </td>

                                            <td :class="['border-t border-gray-200 relative px-3 py-3.5 text-sm text-gray-500']">

                                                <div class="relative">
                                                    <input :value="type.pivot.sale" v-money3="coins[type.pivot.sale_coin]"
                                                        type="text" name="sale" id="sale"
                                                        class="sm:text-sm border-0 focus:outline-0" />
                                                </div>
                                            </td>
                                        </tr>
                                        <!--  -->

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-span-6">
                            <InputLabel for="last_posted" :value="$t('Last posted')" />
                            <TextInput disabled id="last_posted" :value="site.last_posted" type="date"
                                class="disabled:opacity-50 mt-1 block w-full" />
                        </div>


                        <div class="col-span-6">
                            <InputLabel for="obs" :value="$t('Obs')" />
                            <textarea disabled id="obs" :value="site.obs" type="text" rows="3"
                                class="disabled:opacity-50 mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"></textarea>

                        </div>

                        <div class="col-span-6">
                            <InputLabel for="rules" :value="$t('Rules for content')" />
                            <textarea disabled id="rules" :value="site.rules" type="text" rows="3"
                                class="disabled:opacity-50 mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-blue-300 focus:outline-none focus:ring-blue-200 sm:text-sm"></textarea>
                        </div>

                        <div class="col-span-6">
                            <InputLabel for="example_article" :value="$t('Example article')" />
                            <TextInput disabled id="example_article" :value="site.example_article" type="text"
                                class="disabled:opacity-50 mt-1 block w-full" />
                        </div>
                    </form>
                </div>
            </div>
        </ClientLayoutNew>
    </AppSuspense>
</template>
        