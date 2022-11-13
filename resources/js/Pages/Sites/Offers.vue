<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TableSortButton from '@/Components/TableSortButton.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, onMounted, ref, watch } from 'vue'
import { useTranslation } from "i18next-vue";
import { useCoinStore } from '@/stores/coin'
import {
    ChevronRightIcon,
} from '@heroicons/vue/20/solid'
import AppSuspense from '../../Layouts/AppSuspense.vue';
const coinStore = useCoinStore()
const { t } = useTranslation()

const props = defineProps({
    title: String,
    offers: Array,
    coins: Object,
});

const accept = (offer) => {
    Inertia.post(route('offers.accept', offer), null, {
        preserveScroll: true,
        preserveState: true,
    })
}

const reject = (offer) => {
    Inertia.delete(route('offers.reject', offer), {
        preserveScroll: true,
        preserveState: true,
    })
}

onMounted(() => {
    tippy('[data-tippy-content]', {
        interactive: true,
    });
})
</script>
        
<template>

    <AppSuspense>
        <AppLayout title="Sites">
            <template #header>
                <div class="flex flex-col">
                    <div
                        class="w-full flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ $t('Offers') }}
                        </h2>
                    </div>
                </div>
            </template>

            <div>
                <div class="sm:hidden border border-gray-200 rounded-lg overflow-hidden">
                    <ul role="list" class="divide-y divide-gray-200">

                        <li v-for="(offer, index) in offers.data" :key="index">
                            <span class="block bg-white px-4 py-4 hover:bg-gray-50">
                                <span class="flex items-center space-x-4">
                                    <span class="flex flex-1 space-x-2 truncate">
                                        <span class="text-sm text-gray-500">
                                            {{ offer.site.url }}
                                        </span>
                                    </span>
                                    <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                                </span>
                            </span>
                        </li>

                    </ul>
                </div>

                <div class="hidden sm:block">
                    <div class="flex flex-col">
                        <div
                            class="min-w-full overflow-hidden overflow-x-auto align-middle border border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Domain') }}
                                        </th>

                                        <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Current price') }}</th>
                                        <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Current Seller') }}</th>
                                        <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('New price') }}</th>
                                        <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('New Seller') }}</th>

                                        <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-if="offers.length == 0">
                                        <td colspan="6" class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 text-center italic">
                                            {{ $t('No new offers.') }}
                                        </td>
                                    </tr>

                                    <tr v-else v-for="(offer, index) in offers" :key="index" class="bg-white">
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 hover:text-gray-900">
                                            <a :href="route('sites.edit', offer.site.id)" target="_blank">{{ offer.site.url ?? '-' }}</a>
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ $filters.currency(offer.site.cost / 100, coins[offer.site.cost_coin]) }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ offer.site.seller.name ?? '-' }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ $filters.currency(offer.cost / 100, coins[offer.cost_coin]) }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ offer.seller.name ?? '-' }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            <div class="flex justify-end items-center space-x-2">

                                                <form @submit.prevent="accept(offer.id)">
                                                    <button
                                                        class="px-2 py-1 font-medium rounded-md bg-green-500 hover:bg-green-600 transition-colors text-white">Aprovar</button>
                                                </form>

                                                <form @submit.prevent="reject(offer.id)">
                                                    <button
                                                        class="px-2 py-1 font-medium rounded-md bg-red-500 hover:bg-red-600 transition-colors text-white">Recusar</button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    </AppSuspense>
</template>
        