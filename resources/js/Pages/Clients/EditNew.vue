<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import DeleteUserForm from '@/Pages/Clients/PartialsNew/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Clients/PartialsNew/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Clients/PartialsNew/TwoFactorAuthenticationForm.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import {
    UserCircleIcon,
    KeyIcon,
    CurrencyDollarIcon,
    ChatBubbleBottomCenterTextIcon,
EyeIcon,
EyeSlashIcon,
} from '@heroicons/vue/24/outline'
import { Inertia } from '@inertiajs/inertia';
import { ref } from 'vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    client: Object,
    consultants: Array,
    sessions: Array,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const passwordInput = ref(null);
const showPassword = ref(false)

const form = useForm({
    _method: 'PUT',

    seller_id: props.client.seller_id,

    first_name: props.client.first_name,
    last_name: props.client.last_name,
    name: props.client.name,
    birthday: props.client.birthday,
    email: props.client.email,
    photo: null,

    locale: props.client.locale ?? 'en',
    coin: props.client.coin ?? 'USD',
    full: props.client.full ?? false,

    company_name: props.client.company_name,
    website_url: props.client.website_url,
    address_country: props.client.address_country,
    address_state: props.client.address_state,
    address_street_name: props.client.address_street_name,
    address_street_number: props.client.address_street_number,
    address_number: props.client.address_number,
    address_zipcode: props.client.address_zipcode,

    phone: props.client.phone,
    notify_updates_via_email: props.client.notify_updates_via_email,

    contact: props.client.contact,
    contact_where: props.client.contact_where,
    contact_id: props.client.contact_id,
    contact_how: props.client.contact_how,

    invoice_company_name: props.client.invoice_company_name,
    invoice_vat_number: props.client.invoice_vat_number,
    invoice_paypal: props.client.invoice_paypal,
    invoice_address_country: props.client.invoice_address_country,
    invoice_address_state: props.client.invoice_address_state,
    invoice_address_street_name: props.client.invoice_address_street_name,
    invoice_address_street_number: props.client.invoice_address_street_number,
    invoice_address_number: props.client.invoice_address_number,
    invoice_address_zipcode: props.client.invoice_address_zipcode,

    password: '',
});

const update = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('clients.update', props.client.id), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => {
            clearPhotoFileInput()
            form.reset('password');
        },
        onError: (error) => {
            console.log(error)

            if (form.errors.password) {
                form.reset('password');
                passwordInput.value.focus();
            }
        },
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    // Inertia.delete(route('client.current-user-photo.destroy'), {
    //     preserveScroll: true,
    //     onSuccess: () => {
    //         photoPreview.value = null;
    //         clearPhotoFileInput();
    //     },
    // });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};

const generatePassword = () => {
    form.password = Math.random().toString(36).slice(-8);
}
</script>

<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Edit client')">

            <form @submit.prevent="update">
                <div class="max-w-7xl mx-auto rounded-md bg-white shadow">
                    <div class="px-5 py-6 sm:px-6">
                        <span class="text-sm text-gray-900">{{ $t('Current Linking consultant') }}</span>
                        <span class="mt-2 flex items-center space-x-2">
                            <select v-model="form.seller_id" id="consultant" name="consultant" class="block w-full text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">{{ $t('None') }}</option>
                                <option v-for="(consultant, index) in consultants" :key="index" :value="consultant.id">{{ consultant.name }}</option>
                            </select>

                            <PrimaryButton :class="{ 'opacity-25': form.processing }" class="h-[36px]" :disabled="form.processing">
                                Save
                            </PrimaryButton>
                        </span>
                    </div>
                </div>

                <div class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <UserCircleIcon class="h-8 w-8" />
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Account settings') }}</span>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="px-5 py-5">
                        <div class="ml-12 mr-16">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
                                <!-- First Name -->
                                <div>
                                    <InputLabel for="first_name" :value="$t('First name')" />
                                    <TextInput id="first_name" v-model="form.first_name" type="text"
                                        class="mt-1 block w-full" autocomplete="off"/>
                                    <InputError :message="form.errors.first_name" class="mt-2" />
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <InputLabel for="last_name" :value="$t('Last name')" />
                                    <TextInput id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full"
                                    autocomplete="off"/>
                                    <InputError :message="form.errors.last_name" class="mt-2" />
                                </div>


                                <!-- Name -->
                                <div>
                                    <InputLabel for="name" :value="$t('Username (optional)')" />
                                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full"
                                    autocomplete="off"/>
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <!-- Profile Photo -->
                                <div v-if="$page.props.jetstream.managesProfilePhotos">
                                    <!-- Profile Photo File Input -->
                                    <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">

                                    <InputLabel for="photo" :value="$t('Photo')" />

                                    <div class="flex mt-2">
                                        <!-- Current Profile Photo -->
                                        <div v-show="!photoPreview">
                                            <img :src="client.profile_photo_url" :alt="client.name"
                                                class="rounded-full h-8 w-8 object-cover">
                                        </div>

                                        <!-- New Profile Photo Preview -->
                                        <div v-show="photoPreview">
                                            <span class="block rounded-full w-8 h-8 bg-cover bg-no-repeat bg-center"
                                                :style="'background-image: url(\'' + photoPreview + '\');'" />
                                        </div>

                                        <button
                                            class="ml-2 px-2 text-sm text-gray-400 hover:text-gray-500 transition-colors"
                                            type="button" @click.prevent="selectNewPhoto">
                                            {{ $t('Change?') }}
                                        </button>

                                        <button
                                            class="px-2 text-sm text-red-500 hover:text-red-600 rounded-md transition-colors"
                                            v-if="client.profile_photo_path" type="button" @click.prevent="deletePhoto">
                                            Remove Photo
                                        </button>
                                    </div>

                                    <InputError :message="form.errors.photo" class="mt-2" />
                                </div>

                                <!-- Birthday -->
                                <div class="col-span-2">
                                    <InputLabel for="birthday" :value="$t('Birthday')" />
                                    <TextInput id="birthday" v-model="form.birthday" type="text" class="mt-1 block w-full" autocomplete="off"
                                     />
                                    <InputError :message="form.errors.birthday" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="locale" :value="$t('Locale')" />
                                    <div class="mt-1">

                                        <select v-model="form.locale" id="locale" name="locale"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="en">{{ $t('English') }}</option>
                                            <option value="es">{{ $t('Espanhol') }}</option>
                                            <option value="pt">{{ $t('Português') }}</option>
                                        </select>

                                    </div>

                                    <InputError class="mt-2" :message="form.errors.locale" />
                                </div>

                                <div>
                                    <InputLabel for="coin" :value="$t('Coin')" />
                                    <div class="mt-1">

                                        <select v-model="form.coin" id="coin" name="coin"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="BRL">BRL</option>
                                        </select>

                                    </div>

                                    <InputError class="mt-2" :message="form.errors.locale" />
                                </div>

                                <div class="col-span-2">
                                    <InputLabel for="full" :value="$t('Full')" />
                                    <Checkbox id="full" v-model:checked="form.full" name="full" />
                                    <InputError class="mt-2" :message="form.errors.full" />
                                </div>

                                <div class="self-end mb-1 flex">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Save
                                    </PrimaryButton>

                                    <ActionMessage :on="form.recentlySuccessful" class="self-end ml-3">
                                        Saved.
                                    </ActionMessage>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="px-5 bg-stone-100 py-5">
                        <div class="ml-12 mr-16">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
                                <div>
                                    <InputLabel for="company_name" :value="$t('Company name')" />
                                    <TextInput
                                        id="company_name"
                                        v-model="form.company_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                    
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
                                    
                                    />
                                    <InputError :message="form.errors.address_zipcode" class="mt-2" />
                                </div>

                                <div class="flex items-center space-x-2">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Save
                                    </PrimaryButton>

                                    <ActionMessage :on="form.recentlySuccessful">
                                        Saved.
                                    </ActionMessage>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="px-5 py-5">
                        <div class="ml-12 mr-16">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
                                <!-- Email -->
                                <div>
                                    <InputLabel for="email" :value="$t('Email')" />
                                    <TextInput
                                        id="email"
                                        v-model="form.email" autocomplete="off"
                                        type="email"
                                        class="mt-1 block w-full"
                                    />
                                    <InputError :message="form.errors.email" class="mt-2" />

                                    <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                                        <p class="text-sm mt-2">
                                            Your email address is unverified.

                                            <Link
                                                :href="route('verification.send')"
                                                method="post"
                                                as="button"
                                                class="underline text-gray-600 hover:text-gray-900"
                                                @click.prevent="sendEmailVerification"
                                            >
                                                Click here to re-send the verification email.
                                            </Link>
                                        </p>

                                        <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                                            A new verification link has been sent to your email address.
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="phone" :value="$t('Phone')" />
                                    <TextInput
                                        id="phone"
                                        v-model="form.phone" autocomplete="off"
                                        type="phone"
                                        class="mt-1 block w-full"
                                    />
                                    <InputError :message="form.errors.phone" class="mt-2" />
                                </div>

                                <div class="col-span-2">
                                    <InputLabel for="notify_updates_via_email" :value="$t('E-mail notifications')"/>
                                    <div class="mt-1">

                                        <select v-model="form.notify_updates_via_email" id="notify_updates_via_email" name="notify_updates_via_email" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option :value="null">Disabled</option>
                                            <option value="DAILY">Daily</option>
                                            <option value="WEEKLY">Weekly</option>
                                        </select>

                                    </div>

                                    <InputError class="mt-2" :message="form.errors.locale"/>
                                </div>

                                <div class="flex">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Save
                                    </PrimaryButton>

                                    <ActionMessage :on="form.recentlySuccessful" class="self-end ml-3">
                                        Saved.
                                    </ActionMessage>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="px-5 bg-stone-100 py-5">
                        <div class="ml-12 mr-16">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-6">
                                <!-- Email -->
                                <div>
                                    <InputLabel for="contact" :value="$t('Best way to contact is...')" />

                                    <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.contact" value="email" />
                                            <span class="ml-2 text-sm text-gray-600">{{ $t('Email') }}</span>
                                        </label>

                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.contact" value="call" />
                                            <span class="ml-2 text-sm text-gray-600">{{ $t('Phone call') }}</span>
                                        </label>

                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.contact" value="message" />
                                            <span class="ml-2 text-sm text-gray-600">{{ $t('Message app') }}</span>
                                        </label>

                                        <label class="flex items-center">
                                            <input type="radio" v-model="form.contact" value="other" />
                                            <span class="ml-2 text-sm text-gray-600">{{ $t('Other') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-span-2">
                                    <div class="grid col-span-1 sm:grid-col-span-2 gap-6">
                                        <div>
                                            <InputLabel for="contact_where" :value="$t('Which one')" />
                                            <TextInput
                                                id="contact_where"
                                                v-model="form.contact_where"
                                                type="text"
                                                class="mt-1 block w-full disabled:opacity-50"
                                                :disabled="form.contact !== 'other'"
                                            />
                                            <InputError :message="form.errors.contact_where" class="mt-2" />
                                        </div>

                                        <div>
                                            <InputLabel for="contact_id" :value="$t('How to find (ID, username, @, number, etc)')" />
                                            <TextInput
                                                id="contact_id"
                                                v-model="form.contact_id"
                                                type="text"
                                                class="mt-1 block w-full"
                                            />
                                            <InputError :message="form.errors.contact_id" class="mt-2" />
                                        </div>

                                        <div class="col-span-2">
                                            <InputLabel for="contact_how" :value="$t('How')" />
                                            <textarea
                                                id="contact_how"
                                                v-model="form.contact_how"
                                                class="mt-1 block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-300 focus:outline-none focus:ring-indigo-200 sm:text-sm"
                                            />
                                            <InputError :message="form.errors.contact_how" class="mt-2" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-3 flex justify-end items-center space-x-2">
                                    <ActionMessage :on="form.recentlySuccessful">
                                        Saved.
                                    </ActionMessage>

                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Save
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow pt-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <CurrencyDollarIcon class="h-8 w-8" />
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Invoice info') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('Make sure these fields are filled in correctly') }}</span>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="px-5 py-5">
                        <div class="ml-12 mr-16">
                            <div class="grid grid-cols-1 sm:grid-cols-6 gap-x-12 gap-y-6">
                                <div class="col-span-2">
                                    <InputLabel for="invoice_company_name" :value="$t('Company name')" />
                                    <TextInput
                                        id="invoice_company_name"
                                        v-model="form.invoice_company_name"
                                        type="text"
                                        class="mt-1 block w-full"
                                    
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow pt-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <KeyIcon class="h-8 w-8" />
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Change password') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('The password must be at least 8 characters long') }}</span>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="px-5 py-5">
                        <div class="ml-12 mr-16">
                            <div class="grid grid-cols-1 gap-x-12 gap-y-6">
                                <div>
                                    <InputLabel for="password" value="Password" />
                                    
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <TextInput
                                            id="password"
                                            ref="passwordInput"
                                            v-model="form.password"
                                            :type="showPassword ? 'text' : 'password'"
                                            class="block w-full rounded-none rounded-l-md"
                                        
                                        />

                                        <button @click="showPassword = !showPassword" type="button" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            <EyeIcon v-if="!showPassword" class="h-6 w-6"/>
                                            <EyeSlashIcon v-else class="h-6 w-6"/>
                                        </button>

                                        <button @click="generatePassword" type="button" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">{{ $t('Generate') }}</button>
                                    </div>

                                    <InputError :message="form.errors.password" class="mt-2" />
                                </div>

                                <div class="flex items-center space-x-2">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Save
                                    </PrimaryButton>

                                    <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                                        Saved.
                                    </ActionMessage>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </AppLayoutNew>
    </AppSuspense>
</template>
