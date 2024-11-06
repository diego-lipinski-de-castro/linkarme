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

const { invoice } = defineProps({
    invoice: Object,
});

const form = useForm({

});

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
                    </div>
                </div>

                <hr class="my-5">

                <div class="px-5 py-5">
                    <div class="ml-12 mr-16">
                        <form @submit.prevent="submit" class="grid grid-cols-1 sm:grid-cols-6 gap-6">

                            <div class="col-span-6">

                                <pre class=" whitespace-pre-line">
                                    Gerado em: {{ new Date(invoice.created_at).toLocaleString() }}
                                </pre>

                                <pre class=" whitespace-pre-line">
                                    Pago: {{ invoice.paid ? 'Sim' : 'Não' }}
                                </pre>

                                <pre class=" whitespace-pre-line">
                                    Orders: {{ invoice.orders.map(order => order.number).join(', ') }}
                                </pre>

                            </div>

                            <div class="col-span-6 self-end mb-1 flex">
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
        