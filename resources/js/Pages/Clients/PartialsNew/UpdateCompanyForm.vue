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

    company_name: props.user.company_name,
    website_url: props.user.website_url,
    address_country: props.user.address_country,
    address_state: props.user.address_state,
    address_street_name: props.user.address_street_name,
    address_street_number: props.user.address_street_number,
    address_number: props.user.address_number,
    address_zipcode: props.user.address_zipcode,
});


const updateProfileInformation = () => {
    form.post(route('clients.update', props.user.id), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
    });
};
</script>

<template>
    <form @submit.prevent="updateProfileInformation" class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
        <div>
            <InputLabel for="company_name" :value="$t('Company name')" />
            <TextInput
                id="company_name"
                v-model="form.company_name"
                type="text"
                class="mt-1 block w-full"
                autocomplete="company_name"
            />
            <InputError :message="form.errors.company_name" class="mt-2" />
        </div>

        <div>
            <InputLabel for="website_url" :value="$t('Website URL')" />
            <TextInput
                id="website_url"
                v-model="form.website_url"
                type="text"
                class="mt-1 block w-full"
                autocomplete="website_url"
            />
            <InputError :message="form.errors.website_url" class="mt-2" />
        </div>
        
        <div>
            <InputLabel for="address_country" :value="$t('Address')" />
            <TextInput
                id="address_country"
                v-model="form.address_country"
                type="text"
                class="mt-1 block w-full"
                autocomplete="address_country"
            />
            <InputError :message="form.errors.address_country" class="mt-2" />
        </div>

        <div>
            <InputLabel for="address_state" :value="$t('State')" />
            <TextInput
                id="address_state"
                v-model="form.address_state"
                type="text"
                class="mt-1 block w-full"
                autocomplete="address_state"
            />
            <InputError :message="form.errors.address_state" class="mt-2" />
        </div>

        <div>
            <InputLabel for="address_street_name" :value="$t('Street name')" />
            <TextInput
                id="address_street_name"
                v-model="form.address_street_name"
                type="text"
                class="mt-1 block w-full"
                autocomplete="address_street_name"
            />
            <InputError :message="form.errors.address_street_name" class="mt-2" />
        </div>

        <div>
            <InputLabel for="address_street_number" :value="$t('Number')" />
            <TextInput
                id="address_street_number"
                v-model="form.address_street_number"
                type="text"
                class="mt-1 block w-full"
                autocomplete="address_street_number"
            />
            <InputError :message="form.errors.address_street_number" class="mt-2" />
        </div>

        <div>
            <InputLabel for="address_number" :value="$t('Apartment, suit, etc')" />
            <TextInput
                id="address_number"
                v-model="form.address_number"
                type="text"
                class="mt-1 block w-full"
                autocomplete="address_number"
            />
            <InputError :message="form.errors.address_number" class="mt-2" />
        </div>

        <div>
            <InputLabel for="address_zipcode" :value="$t('Zip code')" />
            <TextInput
                id="address_zipcode"
                v-model="form.address_zipcode"
                type="text"
                class="mt-1 block w-full"
                autocomplete="address_zipcode"
            />
            <InputError :message="form.errors.address_zipcode" class="mt-2" />
        </div>

        <div class="flex">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>

            <ActionMessage :on="form.recentlySuccessful" class="self-end ml-3">
                Saved.
            </ActionMessage>
        </div>
    </form>
</template>
