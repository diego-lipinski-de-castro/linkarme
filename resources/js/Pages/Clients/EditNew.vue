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

defineProps({
    client: Object,
    sessions: Array,
});

</script>

<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Edit client')">
            
            <div>
                <div class="max-w-7xl mx-auto rounded-md bg-white shadow">
                    
                    <div v-if="false" class="cw-full px-5 py-6 sm:px-6 flex flex-col space-y-2 border-r border-gray-300 border-opacity-50">
                        <span class="text-sm text-gray-900">{{ $t('Your current Linking consultant') }}</span>
                        <span class="flex items-center space-x-2">
                            <img class="h-8 w-8 rounded-full" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                            <span class="text-sm text-gray-500">{{ 'Max Pires' }}&nbsp;|&nbsp;{{ 'maxpires@linking.com' }}&nbsp;|&nbsp;{{ '+55 (47) 98888 8888' }}</span>
                        </span>
                    </div>

                    <div class="cw-full px-5 py-6 sm:px-6 flex flex-col space-y-2 border-r border-gray-300 border-opacity-50">
                        <span class="text-sm text-gray-900">{{ $t('Your current Linking consultant') }}</span>
                        <span class="flex items-center space-x-2">
                            <span class="text-sm text-gray-400 italic">{{ $t('Coming soon...') }}</span>
                        </span>
                    </div>

                </div>

                <div class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <UserCircleIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Account settings') }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-5">

                    <div class="px-5 py-5">
                        <UpdateProfileInformationForm :user="client" class="ml-12 mr-16"/>
                    </div>

                    <hr>

                    <div class="px-5 bg-stone-100 py-5">
                        <UpdateCompanyForm :user="client" class="ml-12 mr-16"/>
                    </div>

                    <hr>

                    <div class="px-5 py-5">
                        <UpdateEmailForm :user="client" class="ml-12 mr-16"/>
                    </div>

                    <hr>

                    <div class="px-5 bg-stone-100 py-5">
                        <UpdateContactForm :user="client" class="ml-12 mr-16"/>
                    </div>
                </div>

                <div class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow pt-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <CurrencyDollarIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Invoice info') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('Make sure these fields are filled in correctly') }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-5">

                    <div class="px-5 py-5">
                        <UpdateInvoiceForm :user="$page.props.user" class="ml-12 mr-16"/>
                    </div>
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword" class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow pt-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <KeyIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Change password') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('The password must be at least 8 characters long') }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-5">

                    <div class="px-5 py-5">
                        <UpdatePasswordForm class="ml-12 mr-16"/>
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
    </AppSuspense>
</template>
