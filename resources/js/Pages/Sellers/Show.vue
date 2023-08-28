<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, ref, watch } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {
    EyeIcon,
    EyeSlashIcon,
} from '@heroicons/vue/20/solid'
import AppSuspense from '../../Layouts/AppSuspense.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import { UserCircleIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
} from '@heroicons/vue/20/solid'

import { onMounted } from 'vue';
import { useCoinStore } from '@/stores/coin'

const coinStore = useCoinStore()

const props = defineProps({
    seller: Object,
    sites: Object,
    coins: Object,
})

const links = computed(() => {
    const _links = props.sites.links
    _links.shift()
    _links.pop()
    return _links
})

const coinFormat = computed(() => props.coins[coinStore.coin])

onMounted(() => {
    setTimeout(() => {
        tippy('[data-tippy-content]');
    }, 500)
})

const copy = () => {
    const url = route('submitSite.create', {
        _query: {
            ref: props.seller.email,
        },
    });

    navigator.clipboard.writeText(url).then(function () {
        alert('Link successfully copied to clipboard!')
    }, function (err) {
        alert('Failed to copy link :(')
    });
}
</script>
        
<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Edit seller')">


            <div class="max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-10">
                        <UserCircleIcon class="h-8 w-8" />
                    </div>

                    <div>
                        <span class="block font-bold">{{ $t('Seller details') }}</span>
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 py-5">
                    <div class="ml-12 mr-16">
                        <form @submit.prevent class="grid grid-cols-1 gap-x-12 gap-y-6">

                            <div>
                                <InputLabel for="name" :value="$t('Username')" />
                                <TextInput readonly id="name" :value="seller.name ?? '-'" type="text"
                                    class="border-none shadow-none mt-1 block w-full text-gray-500" />
                            </div>

                            <div>
                                <InputLabel for="email" :value="$t('Email')" />
                                <TextInput readonly id="email" :value="seller.email ?? '-'" type="email"
                                    class="border-none shadow-none mt-1 block w-full text-gray-500" />
                            </div>

                            <div>
                                <InputLabel for="phone" :value="$t('Phone')" />
                                <TextInput readonly id="phone" :value="seller.phone ?? '-'" type="phone"
                                    class="border-none shadow-none mt-1 block w-full text-gray-500" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-6 max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-full flex justify-between items-center">
                        <span class="block font-bold">{{ $t('Sites') }}</span>

                        <button @click="copy" class="rounded-md bg-blue-500 hover:bg-blue-300 text-white text-sm font-medium px-4 py-2">{{ $t('Copy seller link') }}</button>
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 pt-2 pb-5">
                    <div>
                        <div
                            class="min-w-full overflow-hidden overflow-x-auto align-middle border border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Price') }}</span>
                                            </div>
                                        </th>

                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Domain') }}</span>
                                            </div>
                                        </th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('DA') }}</span>
                                            </div>
                                        </th>

                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('DR') }}</span>
                                            </div>
                                        </th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Accepts gambling') }}</th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Marked as sponsored') }}</th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Cripto') }}</th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('SSL') }}</th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Category') }}</th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Upload date') }}</span>
                                            </div>
                                        </th>
                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">
                                            <div class="flex group">
                                                <span class="block">{{ $t('Updated date') }}</span>
                                            </div>
                                        </th>

                                        <th class="bg-gray-50 px-4 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(site, index) in sites.data" :key="index" class="bg-white">
                                        <td class="whitespace-nowrap px-4 py-4 text-sm">
                                            <span
                                                :data-tippy-content="site.sale_coin != coinStore.coin ? `${$filters.currency(site.sale / 100, coins[site.sale_coin])}` : null"
                                                class="relative flex space-x-2 items-center">
                                                <span v-if="site.sale_coin != coinStore.coin"
                                                    class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                <span>
                                                    {{ site.sale_coin != coinStore.coin ? '~ ' : null }}

                                                    {{ $filters.currency(Math.ceil((site.sale /
                                                        coinStore.ratios[site.sale_coin]) / 100), {
                                                        ...coins[coinStore.coin],
                                                        precision: 0,
                                                    }) }}
                                                </span>
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm">
                                            <Link :href="route('sites.edit', site.id)" :class="[{
                                                'text-gray-500 hover:text-gray-900': site.deleted_at === null,
                                                'text-red-500 hover:text-red-700': site.deleted_at !== null,
                                            }]">
                                            {{ site.url }}
                                            </Link>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.da ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.dr ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.gambling ? 'Yes' : 'No') }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.sponsor ? 'Yes' : 'No') }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.cripto ? 'Yes' : 'No') }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ $t(site.ssl ? 'Yes' : 'No') }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            <span :data-tippy-content="site.category?.subtitle">{{ site.category?.title ??
                                                '-' }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.formatted_inserted_at }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">
                                            {{ site.formatted_updated_at }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm">
                                            <div class="flex justify-end space-x-2">
                                                <Link :href="route('sites.edit', site.id)"
                                                    class="text-blue-500 hover:text-blue-700">
                                                <PencilSquareIcon class="h-5 w-5" />
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <nav class="mt-6 flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                            <div class="-mt-px flex w-0 flex-1">
                                <Link :href="sites.prev_page_url"
                                    class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                                {{ $t('Previous') }}
                                </Link>
                            </div>
                            <div class="hidden md:-mt-px md:flex">
                                <Link v-for="(link, index) in links" :key="index" :href="link.url"
                                    :class="[link.active ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'inline-flex items-center border-t-2  px-4 pt-4 text-sm font-medium ']">
                                {{ link.label }}
                                </Link>
                            </div>
                            <div class="-mt-px flex w-0 flex-1 justify-end">
                                <Link :href="sites.next_page_url"
                                    class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                {{ $t('Next') }}
                                <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                                </Link>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
    </AppLayoutNew>
</AppSuspense></template>
        