<script setup>
import ClientLayoutNew from '@/Layouts/ClientLayoutNew.vue';
import DeleteUserForm from '@/Pages/Client/Profile/PartialsNew/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Client/Profile/PartialsNew/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Client/Profile/PartialsNew/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Client/Profile/PartialsNew/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Client/Profile/PartialsNew/UpdateProfileInformationForm.vue';
import UpdateCompanyForm from '@/Pages/Client/Profile/PartialsNew/UpdateCompanyForm.vue';
import UpdateEmailForm from '@/Pages/Client/Profile/PartialsNew/UpdateEmailForm.vue';
import UpdateContactForm from '@/Pages/Client/Profile/PartialsNew/UpdateContactForm.vue';
import UpdateInvoiceForm from '@/Pages/Client/Profile/PartialsNew/UpdateInvoiceForm.vue';
import { Link } from '@inertiajs/inertia-vue3';
import {
    UserCircleIcon,
    KeyIcon,
    CurrencyDollarIcon,
} from '@heroicons/vue/24/outline'

defineProps({
    sessions: Array,
});

</script>

<template>
    <AppSuspense>
        <ClientLayoutNew :title="$t('Profile')">
            
            <div>
                <div class="max-w-7xl mx-auto rounded-md bg-white shadow">
                    
                    <div class="grid grid-cols-5">
                        
                        <div class="col-span-3 px-5 py-6 sm:px-6 flex flex-col space-y-2">
                            <span class="text-sm text-gray-900">{{ $t('Your current Linking consultant') }}</span>
                            <span class="flex items-center space-x-2">
                                <img class="h-8 w-8 rounded-full" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                                <span class="text-sm text-gray-500">{{ 'Max Pires' }}&nbsp;|&nbsp;{{ 'maxpires@linking.com' }}&nbsp;|&nbsp;{{ '+55 (47) 98888 8888' }}</span>
                            </span>
                        </div>

                        <div class="col-span-2 px-5 py-6 sm:px-6 border-l border-gray-300 border-opacity-50 flex flex-col">
                            <span class="text-sm font-bold text-blue-700">{{ $t('So far, you have') }}</span>
                            <span class="text-sm text-gray-500">377 {{ $t('orders') }}</span>
                            <span class="text-sm text-gray-500">228 {{ $t('sites used') }}</span>
                            <span class="text-sm text-gray-500">3921 {{ $t('sites never used') }}</span>
                        </div>

                    </div>
                    
                    <hr>
                    
                    <div class="px-5 py-6 sm:px-6 flex justify-between">
                        <Link href="#" class="px-8 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-bold">
                            {{ $t('Your orders') }}
                        </Link>

                        <Link href="#" class="px-8 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-bold">
                            {{ $t('Your projects') }}
                        </Link>

                        <Link href="#" class="px-8 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-bold">
                            {{ $t('Your favorites') }}
                        </Link>

                        <Link href="#" class="px-8 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-bold">
                            {{ $t('Your interests') }}
                        </Link>
                    </div>

                </div>

                <div class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <UserCircleIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Account settings') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('This is all about you :)') }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-5">

                    <div class="px-5 py-5">
                        <UpdateProfileInformationForm :user="$page.props.user" class="ml-12 mr-16"/>
                    </div>

                    <hr>

                    <div class="px-5 bg-stone-100 py-5">
                        <UpdateCompanyForm :user="$page.props.user" class="ml-12 mr-16"/>
                    </div>

                    <hr>

                    <div class="px-5 py-5">
                        <UpdateEmailForm :user="$page.props.user" class="ml-12 mr-16"/>
                    </div>

                    <hr>

                    <div class="px-5 bg-stone-100 py-5">
                        <UpdateContactForm :user="$page.props.user" class="ml-12 mr-16"/>
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
                            <span class="block text-xs text-gray-400">{{ $t('Your password must be at least 8 characters long') }}</span>
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
        </ClientLayoutNew>
    </AppSuspense>
</template>
