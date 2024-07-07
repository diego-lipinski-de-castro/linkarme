<script setup>
import ClientLayoutNew from '@/Layouts/ClientLayoutNew.vue';
import SiteUpdated from '@/Components/Notifications/SiteUpdated.vue';
import SiteAdded from '@/Components/Notifications/SiteAdded.vue';
import SiteDeleted from '@/Components/Notifications/SiteDeleted.vue';
import SiteRestored from '@/Components/Notifications/SiteRestored.vue';
import {
    ArrowLeftIcon,
    ArrowRightIcon,
    ChevronRightIcon,
    HomeIcon,
} from '@heroicons/vue/20/solid'

import AppSuspense from '../../Layouts/AppSuspense.vue';

const props = defineProps({
    title: String,
    notifications: Array,
    unreadNotifications: Array,
    coins: Object,
    paginator: Object,
});

</script>
    
<template>
    <AppSuspense>
        <ClientLayoutNew :title="$t('Notifications')">

            <div class="rounded-md bg-white px-5 py-4 shadow sm:px-6">
                <div class="flex flex-col">

                    <nav class="flex">
                        <ol role="list" class="flex items-center space-x-4">
                            <li>
                                <div>
                                    <a href="#" class="text-gray-400 hover:text-gray-500 transition-colors">
                                        <HomeIcon class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
                                        <span class="sr-only">Home</span>
                                    </a>
                                </div>
                            </li>

                            <li>
                                <div class="flex items-center">
                                    <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                                    <a href="#" class="ml-4 text-sm text-gray-400 hover:text-gray-500 transition-colors">{{ $t('Notifications') }}</a>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <hr class="my-4">

                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $t('Important notifications') }}</h2>
                        <span class="text-sm text-gray-400">{{ $t('Be sure to read this before start working') }}</span>
                    </div>

                </div>
            </div>

            <div class="mt-5">
                <div v-if="unreadNotifications.length > 0" class="flex justify-between items-center mt-6">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $t('New notifications') }}
                    </h2>
                </div>

                <ul v-if="unreadNotifications.length > 0" role="list"
                    class="mt-6 divide-y divide-gray-200 bg-white rounded-md shadow">
                    <li v-for="(notification, index) in unreadNotifications" :key="index">
                        <SiteAdded v-if="notification.type == 'App\\Notifications\\SiteAdded'" :notification="notification"/>
                        <SiteDeleted v-if="notification.type == 'App\\Notifications\\SiteDeleted'" :notification="notification"/>
                        <SiteRestored v-if="notification.type == 'App\\Notifications\\SiteRestored'" :notification="notification"/>
                        <SiteUpdated v-if="notification.type == 'App\\Notifications\\SiteUpdated'" :notification="notification" :coins="coins"/>
                    </li>
                </ul>

                <!--  -->
                <div v-if="notifications.length > 0" :class="['flex justify-between items-center', {
                    'mt-6': unreadNotifications.length > 0,
                }]">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $t('Old notifications') }}
                    </h2>
                </div>

                <ul v-if="notifications.length > 0" role="list"
                    class="mt-6 divide-y divide-gray-200 bg-white rounded-md shadow">
                    <li v-for="(notification, index) in notifications" :key="index">
                        <SiteAdded v-if="notification.type == 'App\\Notifications\\SiteAdded'" :notification="notification"/>
                        <SiteDeleted v-if="notification.type == 'App\\Notifications\\SiteDeleted'" :notification="notification"/>
                        <SiteRestored v-if="notification.type == 'App\\Notifications\\SiteRestored'" :notification="notification"/>
                        <SiteUpdated v-if="notification.type == 'App\\Notifications\\SiteUpdated'" :notification="notification" :coins="coins"/>
                    </li>
                </ul>

                <div v-if="paginator" class="mt-6 flex justify-end">
                    <nav class="flex items-center space-x-2" aria-label="Pagination">
                        <a v-if="paginator.prev_page_url" :href="paginator.prev_page_url" class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700">
                            <ArrowLeftIcon class="h-5 w-5" aria-hidden="true" />
                            <span class="sr-only">Previous</span>
                        </a>

                        <a v-if="paginator.next_page_url" :href="paginator.next_page_url" class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700">
                            <ArrowRightIcon class="h-5 w-5" aria-hidden="true" />
                            <span class="sr-only">Next</span>
                        </a>
                    </nav>
                </div>

            </div>
        </ClientLayoutNew>
    </AppSuspense>
</template>
    