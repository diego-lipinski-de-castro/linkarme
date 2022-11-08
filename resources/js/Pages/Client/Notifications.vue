<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import SiteUpdated from '@/Components/Notifications/SiteUpdated.vue';
import SiteAdded from '@/Components/Notifications/SiteAdded.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, onMounted, ref, watch } from 'vue'
import unionBy from 'lodash/unionBy'
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
    Switch, SwitchGroup, SwitchLabel,
} from '@headlessui/vue'

import {
    Bars3CenterLeftIcon,
    BellIcon,
    ClockIcon,
    CogIcon,
    CreditCardIcon,
    DocumentChartBarIcon,
    HomeIcon,
    QuestionMarkCircleIcon,
    ScaleIcon,
    ShieldCheckIcon,
    UserGroupIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'
import {
    ArrowLongLeftIcon,
    ArrowLongRightIcon,
    CheckCircleIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    ChevronUpIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/20/solid'

import { useTranslation } from "i18next-vue";
import AppSuspense from '../../Layouts/AppSuspense.vue';

const props = defineProps({
    title: String,
    notifications: Object,
    unreadNotifications: Array,
});

const links = computed(() => {
    const _links = props.notifications.links
    _links.shift()
    _links.pop()
    return _links
})

</script>
    
<template>
    <AppSuspense>
        <ClientLayout title="Notificações">

            <div>
                <div v-if="unreadNotifications.length > 0" class="flex justify-between items-center pt-6">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $t('New notifications') }}
                    </h2>
                </div>

                <ul v-if="unreadNotifications.length > 0" role="list"
                    class="mt-6 divide-y divide-gray-200 bg-white rounded-md shadow">
                    <li v-for="(notification, index) in unreadNotifications" :key="index">
                        <SiteAdded v-if="notification.type == 'App\\Notifications\\SiteAdded'" :notification="notification"/>
                        <SiteUpdated v-if="notification.type == 'App\\Notifications\\SiteUpdated'" :notification="notification"/>
                    </li>
                </ul>

                <div v-if="notifications.data.length > 0" class="flex justify-between items-center pt-12">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $t('Old notifications') }}
                    </h2>
                </div>

                <ul v-if="notifications.data.length > 0" role="list"
                    class="mt-6 divide-y divide-gray-200 bg-white rounded-md shadow">
                    <li v-for="(notification, index) in notifications.data" :key="index">
                        <SiteAdded v-if="notification.type == 'App\\Notifications\\SiteAdded'" :notification="notification"/>
                        <SiteUpdated v-if="notification.type == 'App\\Notifications\\SiteUpdated'" :notification="notification"/>
                    </li>
                </ul>

                <nav v-if="notifications.data.length > 0"
                    class="mt-6 flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                    <div class="-mt-px flex w-0 flex-1">
                        <Link :href="notifications.prev_page_url"
                            class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                        <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                        {{ $t('Previous') }}
                        </Link>
                    </div>
                    <div class="hidden md:-mt-px md:flex">
                        <Link v-for="(link, index) in links" :key="index" :href="link.url"
                            :class="[link.active ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'inline-flex items-center border-t-2  px-4 pt-4 text-sm font-medium ']">
                        {{ link.label }}
                        </Link>
                    </div>
                    <div class="-mt-px flex w-0 flex-1 justify-end">
                        <Link :href="notifications.next_page_url"
                            class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                        {{ $t('Next') }}
                        <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                        </Link>
                    </div>
                </nav>
            </div>
        </ClientLayout>
    </AppSuspense>
</template>
    