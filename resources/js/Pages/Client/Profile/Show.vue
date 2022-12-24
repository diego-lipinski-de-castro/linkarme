<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import DeleteUserForm from '@/Pages/Client/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Client/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Client/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Client/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Client/Profile/Partials/UpdateProfileInformationForm.vue';
</script>

<template>
    <AppSuspense>
        <ClientLayout :title="$t('Profile')">
            <!-- <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Profile
                </h2>
            </template> -->

            <div>
                <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                    <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                        <UpdateProfileInformationForm :user="$page.props.user" />

                        <SectionBorder />
                    </div>

                    <div v-if="$page.props.jetstream.canUpdatePassword">
                        <UpdatePasswordForm class="mt-10 sm:mt-0" />
                    </div>
                </div>
            </div>
        </ClientLayout>
    </AppSuspense>
</template>
