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
import BugForm from '@/Pages/Client/Profile/PartialsNew/BugForm.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue'
import {
    UserCircleIcon,
    KeyIcon,
    CurrencyDollarIcon,
    ChatBubbleBottomCenterTextIcon,
} from '@heroicons/vue/24/outline'

defineProps({
    orders: Number,
    usedCount: Number,
    unusedCount: Number,
    sessions: Array,
});

onMounted(() => {

    const hash = window.location.hash.split('#')[1]

    if(hash) {
        setTimeout(() => {
            const el = document.getElementById(hash)

            console.log('el', el)

            if(el) {
                el.scrollIntoView({
                    behavior: "smooth"
                })
            }
        }, 250)
    }
})

</script>

<template>
    <AppSuspense>
        <ClientLayoutNew :title="$t('Profile')">
            
            <div>
                <div class="max-w-7xl mx-auto rounded-md bg-white shadow">
                    
                    <div class="grid grid-cols-5">
                        
                        <div v-if="false" class="col-span-3 px-5 py-6 sm:px-6 flex flex-col space-y-2 border-r border-gray-300 border-opacity-50">
                            <span class="text-sm text-gray-900">{{ $t('Your current Linking consultant') }}</span>
                            <span class="flex items-center space-x-2">
                                <img class="h-8 w-8 rounded-full" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                                <span class="text-sm text-gray-500">{{ 'Max Pires' }}&nbsp;|&nbsp;{{ 'maxpires@linking.com' }}&nbsp;|&nbsp;{{ '+55 (47) 98888 8888' }}</span>
                            </span>
                        </div>

                        <div class="col-span-3 px-5 py-6 sm:px-6 flex flex-col space-y-2 border-r border-gray-300 border-opacity-50">
                            <span class="text-sm text-gray-900">{{ $t('Your current Linking consultant') }}</span>
                            <span class="flex items-center space-x-2">
                                <span class="text-sm text-gray-400 italic">{{ $t('Coming soon...') }}</span>
                            </span>
                        </div>

                        <div class="col-span-2 px-5 py-6 sm:px-6 flex flex-col">
                            <span class="text-sm font-bold text-blue-700">{{ $t('So far, you have') }}</span>
                            <span class="text-sm text-gray-500">{{ orders }} {{ $t('orders') }}</span>
                            <span class="text-sm text-gray-500">{{ usedCount }} {{ $t('sites used') }}</span>
                            <span class="text-sm text-gray-500">{{ unusedCount }} {{ $t('sites never used') }}</span>
                        </div>

                    </div>
                    
                    <hr>
                    
                    <div class="px-5 py-6 sm:px-6 flex justify-between">
                        <Link :href="route('client.orders.index')" class="px-8 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-bold">
                            {{ $t('Your orders') }}
                        </Link>

                        <Link :href="route('client.projects.index')" class="px-8 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-bold">
                            {{ $t('Your projects') }}
                        </Link>

                        <Link :href="route('client.sites.list', { _query: { 'filter[favorites]': 'true' } })" class="px-8 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-bold">
                            {{ $t('Your favorites') }}
                        </Link>

                        <Link :href="route('client.sites.list', { _query: { 'filter[interests]': 'true' } })" class="px-8 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-bold">
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

                <div id="bug" class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow pt-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <ChatBubbleBottomCenterTextIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Bug report / Suggestions box') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('Help us improve your experience') }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-5">

                    <div class="px-5 py-5">
                        <BugForm class="ml-12 mr-16"/>
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
