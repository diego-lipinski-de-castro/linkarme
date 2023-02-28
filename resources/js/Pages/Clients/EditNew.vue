<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import DeleteUserForm from '@/Pages/Clients/PartialsNew/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Clients/PartialsNew/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Clients/PartialsNew/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Clients/PartialsNew/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Clients/PartialsNew/UpdateProfileInformationForm.vue';
import UpdateCompanyForm from '@/Pages/Clients/PartialsNew/UpdateCompanyForm.vue';
import UpdateEmailForm from '@/Pages/Clients/PartialsNew/UpdateEmailForm.vue';
import UpdateContactForm from '@/Pages/Clients/PartialsNew/UpdateContactForm.vue';
import UpdateInvoiceForm from '@/Pages/Clients/PartialsNew/UpdateInvoiceForm.vue';
import { Link } from '@inertiajs/inertia-vue3';
import {
    UserCircleIcon,
    KeyIcon,
    CurrencyDollarIcon,
    ChatBubbleBottomCenterTextIcon,
} from '@heroicons/vue/24/outline'
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    client: Object,
    consultants: Array,
    sessions: Array,
});

const updateConsultant = (event) => {
    Inertia.put(route('clients.update', props.client.id), {
        email: props.client.email,
        seller_id: event.target.value,
    }, {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onError: (error) => console.log(error),
    });
}

</script>

<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Edit client')">

            <div>
                <div class="max-w-7xl mx-auto rounded-md bg-white shadow">

                    <div class="cw-full px-5 py-6 sm:px-6 flex flex-col space-y-2 border-r border-gray-300 border-opacity-50">
                        <span class="text-sm text-gray-900">{{ $t('Current Linking consultant') }}</span>
                        <span class="flex items-center space-x-2">
                            <select @change="updateConsultant" v-model="client.seller_id" id="consultant"
                                name="consultant"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="null">{{ $t('None') }}</option>
                                <option v-for="(consultant, index) in consultants" :key="index" :value="consultant.id">{{ consultant.name }}</option>
                            </select>
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
                        <UpdateProfileInformationForm :user="client" class="ml-12 mr-16" />
                    </div>

                    <hr>

                    <div class="px-5 bg-stone-100 py-5">
                        <UpdateCompanyForm :user="client" class="ml-12 mr-16" />
                    </div>

                    <hr>

                    <div class="px-5 py-5">
                        <UpdateEmailForm :user="client" class="ml-12 mr-16" />
                    </div>

                    <hr>

                    <div class="px-5 bg-stone-100 py-5">
                        <UpdateContactForm :user="client" class="ml-12 mr-16" />
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
                    <UpdateInvoiceForm :user="client" class="ml-12 mr-16" />
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
                        <UpdatePasswordForm :user="client" class="ml-12 mr-16" />
                    </div>
                </div>
            </div>

            <!-- <div>
                    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                        <div>
                            <TwoFactorAuthenticationForm class="mt-10 sm:mt-0" />
                            <SectionBorder />
                        </div>

                        <div>
                            <LogoutOtherBrowserSessionsForm :sessions="sessions" :deleteRoute="route('client.other-browser-sessions.destroy')" class="mt-10 sm:mt-0" />
                            <SectionBorder />
                        </div>

                        <div>
                            <DeleteUserForm class="mt-10 sm:mt-0" />
                    </div>
                    
                </div>
            </div> -->
    </AppLayoutNew>
</AppSuspense></template>
