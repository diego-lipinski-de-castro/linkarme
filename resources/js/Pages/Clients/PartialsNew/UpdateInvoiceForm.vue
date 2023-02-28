<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    name: props.user.name,
    birthday: props.user.birthday,
    email: props.user.email,
    photo: null,

    invoice_company_name: props.user.invoice_company_name,
    invoice_vat_number: props.user.invoice_vat_number,
    invoice_paypal: props.user.invoice_paypal,
    invoice_address_country: props.user.invoice_address_country,
    invoice_address_state: props.user.invoice_address_state,
    invoice_address_street_name: props.user.invoice_address_street_name,
    invoice_address_street_number: props.user.invoice_address_street_number,
    invoice_address_number: props.user.invoice_address_number,
    invoice_address_zipcode: props.user.invoice_address_zipcode,
});

const updateProfileInformation = () => {
    form.post(route('clients.update', props.user.id), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onError: (error) => console.log(error),
    });
};
</script>

<template>
    <form @submit.prevent="updateProfileInformation" class="grid grid-cols-1 sm:grid-cols-6 gap-x-12 gap-y-6">
        <div class="col-span-2">
            <InputLabel for="invoice_company_name" :value="$t('Company name')" />
            <TextInput
                id="invoice_company_name"
                v-model="form.invoice_company_name"
                type="text"
                class="mt-1 block w-full"
                autocomplete="invoice_company_name"
            />
            <InputError :message="form.errors.invoice_company_name" class="mt-2" />
        </div>

        <div class="col-span-2">
            <InputLabel for="invoice_vat_number" :value="$t('VAT number')" />
            <TextInput
                id="invoice_vat_number"
                v-model="form.invoice_vat_number"
                type="text"
                class="mt-1 block w-full"
                autocomplete="invoice_vat_number"
            />
            <InputError :message="form.errors.invoice_vat_number" class="mt-2" />
        </div>

        <div class="col-span-2">
            <InputLabel for="invoice_paypal" :value="$t('PayPal')" />
            <TextInput
                id="invoice_paypal"
                v-model="form.invoice_paypal"
                type="text"
                class="mt-1 block w-full"
                autocomplete="invoice_paypal"
            />
            <InputError :message="form.errors.invoice_paypal" class="mt-2" />
        </div>
        
        <div class="col-span-3">
            <InputLabel for="invoice_address_country" :value="$t('Address')" />
            <TextInput
                id="invoice_address_country"
                v-model="form.invoice_address_country"
                type="text"
                class="mt-1 block w-full"
                autocomplete="invoice_address_country"
            />
            <InputError :message="form.errors.invoice_address_country" class="mt-2" />
        </div>

        <div class="col-span-3">
            <InputLabel for="invoice_address_state" :value="$t('State')" />
            <TextInput
                id="invoice_address_state"
                v-model="form.invoice_address_state"
                type="text"
                class="mt-1 block w-full"
                autocomplete="invoice_address_state"
            />
            <InputError :message="form.errors.invoice_address_state" class="mt-2" />
        </div>

        <div class="col-span-3">
            <InputLabel for="invoice_address_street_name" :value="$t('Street name')" />
            <TextInput
                id="invoice_address_street_name"
                v-model="form.invoice_address_street_name"
                type="text"
                class="mt-1 block w-full"
                autocomplete="invoice_address_street_name"
            />
            <InputError :message="form.errors.invoice_address_street_name" class="mt-2" />
        </div>

        <div class="col-span-3">
            <InputLabel for="invoice_address_street_number" :value="$t('Number')" />
            <TextInput
                id="invoice_address_street_number"
                v-model="form.invoice_address_street_number"
                type="text"
                class="mt-1 block w-full"
                autocomplete="invoice_address_street_number"
            />
            <InputError :message="form.errors.invoice_address_street_number" class="mt-2" />
        </div>

        <div class="col-span-3">
            <InputLabel for="invoice_address_number" :value="$t('Apartment, suit, etc')" />
            <TextInput
                id="invoice_address_number"
                v-model="form.invoice_address_number"
                type="text"
                class="mt-1 block w-full"
                autocomplete="invoice_address_number"
            />
            <InputError :message="form.errors.invoice_address_number" class="mt-2" />
        </div>

        <div class="col-span-3">
            <InputLabel for="invoice_address_zipcode" :value="$t('Zip code')" />
            <TextInput
                id="invoice_address_zipcode"
                v-model="form.invoice_address_zipcode"
                type="text"
                class="mt-1 block w-full"
                autocomplete="invoice_address_zipcode"
            />
            <InputError :message="form.errors.invoice_address_zipcode" class="mt-2" />
        </div>

        <div class="col-span-6 flex items-center space-x-2">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>

            <ActionMessage :on="form.recentlySuccessful">
                Saved.
            </ActionMessage>
        </div>
    </form>
</template>
