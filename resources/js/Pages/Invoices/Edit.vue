<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, getCurrentInstance } from 'vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import { PencilIcon, PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { useCoinStore } from '@/stores/coin'
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import {
    ArrowTopRightOnSquareIcon,
} from '@heroicons/vue/24/outline';

const FilePond = vueFilePond(FilePondPluginFileValidateType);

const { invoice } = defineProps({
    coins: Object,
    invoice: Object,
});

const form = useForm({
    value: null,
    value_coin: 'BRL',

    discount: null,
    discount_coin: 'BRL',

    paid: false,

    bank: null,
    payment_link:  '',
});

const uploadInvoice = (
    fieldName,
    file,
    metadata,
    load,
    error,
    progress,
    abort
) => {
    console.log(fieldName, file)
}

const submit = () => {
    form.put(route('invoices.update', invoice.id), {
        onError(error) {
            console.log(error)
        }
    });
}
</script>
        
<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Edit invoice')">
            <div class="max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                <div class="flex items-center space-x-2 px-5">
                    <div class="w-10">
                        <PencilIcon class="h-6 w-6" />
                    </div>

                    <div>
                        <span class="block font-bold">{{ $t('Edit invoice') }}</span>
                        <span class="text-sm text-gray-700">#{{ invoice.number }}</span>
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 py-5">
                    <div class="ml-12 mr-16">
                        <form @submit.prevent="submit" class="grid grid-cols-1 gap-x-12 gap-y-6">

                            <div>

                                <pre class=" whitespace-pre-line">
                                    {{ new Date(invoice.created_at).toLocaleString() }}
                                </pre>

                                <span class="mt-2 flex items-center space-x-2">
                                    <template v-for="order in invoice.orders" :key="order.number">
                                        <a :href="route('orders.edit', order.id)" target="_blank" class="flex space-x-1 text-sm text-blue-500 hover:text-blue-700">
                                            <span>#{{ order.number }}</span>
                                            <ArrowTopRightOnSquareIcon class="size-4" />
                                        </a>
                                    </template>
                                </span>

                            </div>

                            <div>
                                <label for="calculated" class="block text-sm font-medium text-gray-700">{{ $t('Valor calculado') }}</label>

                                <div class="mt-1">
                                    {{ invoice.calculated_value }}
                                </div>
                            </div>

                            <div>
                                <label for="value" class="block text-sm font-medium text-gray-700">{{ $t('Valor') }}</label>

                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input v-model.lazy="form.value" v-money3="coins[form.value_coin]" type="text" name="value" id="value"
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <label for="value_coin" class="sr-only">Moeda</label>
                                        <select v-model="form.value_coin" id="value_coin" name="value_coin"
                                            class="focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                            <option value="BRL">BRL</option>
                                            <option value="EUR">EUR</option>
                                            <option value="USD">USD</option>
                                            <option value="GBP">GBP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="discount" class="block text-sm font-medium text-gray-700">{{ $t('Desconto') }}</label>

                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input v-model.lazy="form.discount" v-money3="coins[form.discount_coin]" type="text" name="discount" id="discount"
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" />

                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <label for="discount_coin" class="sr-only">Moeda</label>
                                        <select v-model="form.discount_coin" id="discount_coin" name="discount_coin"
                                            class="focus:ring-blue-500 focus:border-blue-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                            <option value="BRL">BRL</option>
                                            <option value="EUR">EUR</option>
                                            <option value="USD">USD</option>
                                            <option value="GBP">GBP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <InputLabel for="paid" :value="$t('Paid')" />
                                <Checkbox id="paid" v-model:checked="form.paid" name="paid" />
                                <InputError class="mt-2" :message="form.errors.paid" />
                            </div>

                            <div>
                                <label for="bank" class="block text-sm font-medium text-gray-700">{{ $t('Banco') }}</label>
                                <div class="mt-1">
                                    <select v-model="form.bank" id="bank" name="bank"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option :value="null">Selecione</option>
                                        <option value="MERCURY">Mercury</option>
                                        <option value="PAYPAL">Paypal</option>
                                    </select>
                                </div>

                                <InputError class="mt-2" :message="form.errors.bank" />
                            </div>

                            <div>
                                <InputLabel for="payment_link" :value="$t('Link de pagamento')" />
                                <TextInput id="payment_link" v-model="form.payment_link" type="text" class="mt-1 block w-full" />
                                <InputError :message="form.errors.payment_link" class="mt-2" />
                            </div>

                            <div>
                                <file-pond :server="{ process: uploadInvoice }"
                                    :instantUpload="false" :allowProcess="false" :allowRevert="false"
                                    :dropOnPage="true" :dropOnElement="false" />
                            </div>

                            <div class="self-end mb-1 flex">
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
        