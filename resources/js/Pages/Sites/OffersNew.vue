<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
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
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const coinStore = useCoinStore()
const { t } = useTranslation()

const props = defineProps({
    title: String,
    offers: Array,
    coins: Object,
});

const showModal = ref(false)
const selectedOffer = ref(null)

const showOffer = (offer) => {
    selectedOffer.value = offer;
    showModal.value = true;
}

const accept = (offer) => {
    Inertia.post(route('offers.accept', offer), null, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showModal.value = false;
            setTimeout(() => selectedOffer.value = null, 250)
        },
    })
}

const reject = (offer) => {
    Inertia.delete(route('offers.reject', offer), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showModal.value = false;
            setTimeout(() => selectedOffer.value = null, 250)
        },
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
        <TransitionRoot as="template" :show="showModal">
            <Dialog as="div" class="relative z-10" @close="showModal = false">
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
                                class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl sm:p-6">
                                <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                                    <button type="button"
                                        class="rounded-md bg-white text-gray-400 hover:text-gray-500"
                                        @click="showModal = false">
                                        <span class="sr-only">Close</span>
                                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                    </button>
                                </div>
                                <div>
                                    <!--  -->
                                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">{{ $t('Current') }}</DialogTitle>

                                    <div v-if="selectedOffer !== null" class="mt-5">
                                        <div class="ring-1 ring-gray-300 sm:mx-0 sm:rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"
                                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                            Type
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

                                                            <div class="relative rounded-md shadow-sm">
                                                                <input disabled :value="selectedOffer.site.cost"
                                                                    v-money3="coins[selectedOffer.site.cost_coin]" type="text" name="offer-cost"
                                                                    id="offer-cost"
                                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                                <div class="absolute inset-y-0 right-0 flex items-center">
                                                                    <label for="offer-cost_coin" class="sr-only">Moeda</label>
                                                                    <select disabled :value="selectedOffer.site.cost_coin" id="offer-cost_coin"
                                                                        name="offer-cost_coin"
                                                                        class="bg-invisible focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                        <option value="BRL">BRL</option>
                                                                        <option value="EUR">EUR</option>
                                                                        <option value="USD">USD</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </td>

                                                        <td class="px-3 py-3.5 text-sm text-gray-500">

                                                            <div class="relative rounded-md shadow-sm">
                                                                <input disabled :value="selectedOffer.site.sale"
                                                                    v-money3="coins[selectedOffer.site.sale_coin]" type="text" name="offer-sale"
                                                                    id="offer-sale"
                                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                                <div class="absolute inset-y-0 right-0 flex items-center">
                                                                    <label for="offer-sale_coin" class="sr-only">Moeda</label>
                                                                    <select disabled :value="selectedOffer.site.sale_coin" id="offer-sale_coin"
                                                                        name="offer-sale_coin"
                                                                        class="bg-invisible focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                        <option value="BRL">BRL</option>
                                                                        <option value="EUR">EUR</option>
                                                                        <option value="USD">USD</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>

                                                    <!--  -->
                                                    <tr v-for="(type, index) in selectedOffer.site.types" :key="`offer-${index}`">
                                                        <td
                                                            :class="'border-t border-transparent relative py-4 pl-4 pr-3 text-sm sm:pl-6'">
                                                            <div class="font-medium text-gray-900">
                                                                {{ type.name }}
                                                            </div>

                                                            <div class="absolute -top-px left-6 right-0 h-px bg-gray-200" />
                                                        </td>

                                                        <td :class="['border-t border-gray-200 relative px-3 py-3.5 text-sm text-gray-500']">

                                                            <div
                                                                :class="['relative rounded-md shadow-sm']">
                                                                <input disabled :value="selectedOffer.site.types[index].pivot.cost"
                                                                    v-money3="coins[type.cost_coin]" type="text"
                                                                    :name="`offer-cost-${index}`"
                                                                    :id="`offer-cost-${index}`"
                                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                                <div class="absolute inset-y-0 right-0 flex items-center">
                                                                    <label :for="`offer-cost_coin-${index}`" class="sr-only">Moeda</label>
                                                                    <select disabled :value="selectedOffer.site.types[index].pivot.cost_coin" :id="`offer-cost_coin-${index}`"
                                                                        :name="`offer-cost_coin-${index}`"
                                                                        class="bg-invisible focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                        <option value="BRL">BRL</option>
                                                                        <option value="EUR">EUR</option>
                                                                        <option value="USD">USD</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </td>

                                                        <td :class="['border-t border-transparent relative px-3 py-3.5 text-sm text-gray-500']">
                                                            <div
                                                                :class="['relative rounded-md shadow-sm']">
                                                                <input disabled v-model.lazy="selectedOffer.site.types[index].pivot.sale"
                                                                    v-money3="coins[type.sale_coin]" type="text" 
                                                                    :name="`offer-sale-${index}`"
                                                                    :id="`offer-sale-${index}`"
                                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                                <div class="absolute inset-y-0 right-0 flex items-center">
                                                                    <label :for="`offer-sale_coin-${index}`" class="sr-only">Moeda</label>
                                                                    <select disabled v-model="selectedOffer.site.types[index].pivot.sale_coin" :id="`offer-sale_coin-${index}`"
                                                                        :name="`offer-sale_coin-${index}`"
                                                                        class="bg-invisible focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
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

                                    <!--  -->
                                    <DialogTitle as="h3" class="mt-10 text-lg font-medium leading-6 text-gray-900">{{ $t('New') }}</DialogTitle>

                                    <div v-if="selectedOffer !== null" class="mt-5">
                                        <div class="ring-1 ring-gray-300 sm:mx-0 sm:rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"
                                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                            Type
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

                                                            <div class="relative rounded-md shadow-sm">
                                                                <input disabled :value="selectedOffer.cost"
                                                                    v-money3="coins[selectedOffer.cost_coin]" type="text" name="offer-cost"
                                                                    id="offer-cost"
                                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                                <div class="absolute inset-y-0 right-0 flex items-center">
                                                                    <label for="offer-cost_coin" class="sr-only">Moeda</label>
                                                                    <select disabled :value="selectedOffer.cost_coin" id="offer-cost_coin"
                                                                        name="offer-cost_coin"
                                                                        class="bg-invisible focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                        <option value="BRL">BRL</option>
                                                                        <option value="EUR">EUR</option>
                                                                        <option value="USD">USD</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </td>

                                                        <td class="px-3 py-3.5 text-sm text-gray-500">

                                                            <div class="relative rounded-md shadow-sm">
                                                                <input disabled :value="selectedOffer.sale"
                                                                    v-money3="coins[selectedOffer.sale_coin]" type="text" name="offer-sale"
                                                                    id="offer-sale"
                                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                                <div class="absolute inset-y-0 right-0 flex items-center">
                                                                    <label for="offer-sale_coin" class="sr-only">Moeda</label>
                                                                    <select disabled :value="selectedOffer.sale_coin" id="offer-sale_coin"
                                                                        name="offer-sale_coin"
                                                                        class="bg-invisible focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                        <option value="BRL">BRL</option>
                                                                        <option value="EUR">EUR</option>
                                                                        <option value="USD">USD</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>

                                                    <!--  -->
                                                    <tr v-for="(type, index) in selectedOffer.types" :key="`offer-${index}`">
                                                        <td
                                                            :class="'border-t border-transparent relative py-4 pl-4 pr-3 text-sm sm:pl-6'">
                                                            <div class="font-medium text-gray-900">
                                                                {{ type.name }}
                                                            </div>

                                                            <div class="absolute -top-px left-6 right-0 h-px bg-gray-200" />
                                                        </td>

                                                        <td :class="['border-t border-gray-200 relative px-3 py-3.5 text-sm text-gray-500']">

                                                            <div
                                                                :class="['relative rounded-md shadow-sm']">
                                                                <input disabled :value="selectedOffer.types[index].pivot.cost"
                                                                    v-money3="coins[type.cost_coin]" type="text"
                                                                    :name="`offer-cost-${index}`"
                                                                    :id="`offer-cost-${index}`"
                                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                                <div class="absolute inset-y-0 right-0 flex items-center">
                                                                    <label :for="`offer-cost_coin-${index}`" class="sr-only">Moeda</label>
                                                                    <select disabled :value="selectedOffer.types[index].pivot.cost_coin" :id="`offer-cost_coin-${index}`"
                                                                        :name="`offer-cost_coin-${index}`"
                                                                        class="bg-invisible focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                                        <option value="BRL">BRL</option>
                                                                        <option value="EUR">EUR</option>
                                                                        <option value="USD">USD</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </td>

                                                        <td :class="['border-t border-transparent relative px-3 py-3.5 text-sm text-gray-500']">
                                                            <div
                                                                :class="['relative rounded-md shadow-sm']">
                                                                <input disabled v-model.lazy="selectedOffer.types[index].pivot.sale"
                                                                    v-money3="coins[type.sale_coin]" type="text" 
                                                                    :name="`offer-sale-${index}`"
                                                                    :id="`offer-sale-${index}`"
                                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                                                <div class="absolute inset-y-0 right-0 flex items-center">
                                                                    <label :for="`offer-sale_coin-${index}`" class="sr-only">Moeda</label>
                                                                    <select disabled v-model="selectedOffer.types[index].pivot.sale_coin" :id="`offer-sale_coin-${index}`"
                                                                        :name="`offer-sale_coin-${index}`"
                                                                        class="bg-invisible focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-2 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
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

                                </div>

                                <div class="mt-5 flex flex-row justify-end space-x-2">

                                    <button type="button" class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto sm:text-sm" @click="showModal = false">{{ $t('Back') }}</button>
                                    
                                    <form @submit.prevent="accept(selectedOffer.id)">
                                        <button class="px-4 py-2 rounded-md bg-green-500 hover:bg-green-600 transition-colors text-white text-base font-medium shadow-sm sm:text-sm">Aprovar</button>
                                    </form>

                                    <form @submit.prevent="reject(selectedOffer.id)">
                                        <button class="px-4 py-2 rounded-md bg-red-500 hover:bg-red-600 transition-colors text-white text-base font-medium shadow-sm sm:text-sm">Recusar</button>
                                    </form>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <AppLayoutNew title="Sites">
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
                                            scope="col">{{ $t('Current Seller') }}</th>

                                        <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('New Seller') }}</th>

                                        <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ $t('Date') }}</th>

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
                                            {{ offer.site.seller.name ?? '-' }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ offer.seller.name ?? '-' }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ new Date(offer.created_at).toLocaleDateString('pt-BR') }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            <div class="flex justify-end items-center space-x-2">

                                                <button @click="showOffer(offer)" class="px-2 py-1 font-medium rounded-md bg-blue-500 hover:bg-blue-600 transition-colors text-white">Visualizar oferta</button>

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
        </AppLayoutNew>
    </AppSuspense>
</template>
        
