<script setup>
import ClientLayoutNew from '@/Layouts/ClientLayoutNew.vue';
import DeleteUserForm from '@/Pages/Client/Profile/PartialsNew/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Client/Profile/PartialsNew/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Client/Profile/PartialsNew/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Client/Profile/PartialsNew/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Client/Profile/PartialsNew/UpdateProfileInformationForm.vue';
import { Link } from '@inertiajs/inertia-vue3';

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
            </div>

            <div>
                <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                    <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                        <UpdateProfileInformationForm :user="$page.props.user" />
                        <SectionBorder />
                    </div>

                    <div v-if="$page.props.jetstream.canUpdatePassword">
                        <UpdatePasswordForm class="mt-10 sm:mt-0" />
                        <SectionBorder />
                    </div>

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
            </div>
        </ClientLayoutNew>
    </AppSuspense>
</template>
