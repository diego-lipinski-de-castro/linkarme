<script setup>
import ClientLayoutNew from '@/Layouts/ClientLayoutNew.vue';
import SiteUpdated from '@/Components/Notifications/SiteUpdated.vue';
import SiteAdded from '@/Components/Notifications/SiteAdded.vue';
import SiteDeleted from '@/Components/Notifications/SiteDeleted.vue';
import SiteRestored from '@/Components/Notifications/SiteRestored.vue';
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue'
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import {
    ScaleIcon,
    FireIcon,
    BellAlertIcon,
    CloudArrowUpIcon,
    HandThumbUpIcon,
    FlagIcon,
    Cog6ToothIcon,
} from '@heroicons/vue/24/outline'
import {
    BanknotesIcon,
    BuildingOfficeIcon,
    CheckCircleIcon,
    ChevronRightIcon,
} from '@heroicons/vue/20/solid'
import { useTranslation } from "i18next-vue";
import AppSuspense from '../../Layouts/AppSuspense.vue';

const { t } = useTranslation();

const props = defineProps({
    title: String,
    
    coins: Object,

    notifications: Array,
    
    orders: Number,
    
    usedCount: Number,
    unusedCount: Number,
    
    news: Array,
    recommended: Array,

    favorites: Array,
    interests: Array,

    projects: Array,
});

const cards = [
    { label: t('Orders'), href: route('client.orders.index'), amount: props.orders },
    { label: t('Sites used'), href: route('client.sites.index', { _query: { 'filter[used]': 'true' } }), amount: props.usedCount },
    { label: t('Sites never used'), href: route('client.sites.index', { _query: { 'filter[used]': 'false' } }), amount: props.unusedCount },
]

const greeting = computed(() => {
    const hour = new Date().getHours();
    const welcomeTypes = ['Good morning', 'Good afternoon', 'Good evening'];
    let welcomeText = '';

    if (hour < 12) welcomeText = welcomeTypes[0];
    else if (hour < 18) welcomeText = welcomeTypes[1];
    else welcomeText = welcomeTypes[2];

    return t(welcomeText)
})

const toggleFavorite = async (site) => {
    router.post(route('client.sites.favorite', site), null, {
        preserveScroll: true,
    })
}

</script>
        
<template>
    <AppSuspense>
        <ClientLayoutNew :title="$t('Dashboard')">

            <div class="grid grid-cols-1 sm:grid-cols-6 gap-x-12">

                <div class="col-span-1 overflow-hidden rounded-md bg-white shadow h-fit">
                    <div class="flex items-center space-x-2 p-5">
                        <div class="w-10">
                            <FireIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block text-yellow-400 font-bold">{{ $t('Overview') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('Your account stats') }}</span>
                        </div>
                    </div>

                    <Link v-for="(card, index) in cards" :key="index" :href="card.href" class="border-t border-gray-300 border-opacity-50 block p-5">
                        <span class="text-sm block text-gray-500">{{ card.label }}</span>
                        <span class="block text-2xl font-bold">{{ card.amount }}</span>
                    </Link>
                </div>

                <div class="col-span-3 overflow-hidden rounded-md bg-white shadow h-fit py-5">
                    <div>
                        <div class="flex items-center space-x-2 px-5">
                            <div class="w-10">
                                <BellAlertIcon class="h-8 w-8"/>
                            </div>

                            <div>
                                <span class="block font-bold">{{ $t('Important notifications') }}</span>
                                <span class="block text-xs text-gray-400">{{ $t('Be sure to read this before start working') }}</span>
                            </div>
                        </div>

                        <div v-if="notifications.length == 0" class="mt-5 border-t border-b border-gray-300 border-opacity-50">
                            <span class="block p-5 text-gray-500 text-sm italic">
                                {{ $t("You don't have notifications yet.") }}
                            </span>
                        </div>

                        <ul v-else role="list" class="mt-5 divide-y divide-gray-300 divide-opacity-50 border-t border-b border-gray-300 border-opacity-50">
                            <li v-for="(notification, index) in notifications" :key="index" class="odd:bg-gray-100">
                                <SiteAdded v-if="notification.type == 'App\\Notifications\\SiteAdded'" :notification="notification"/>
                                <SiteDeleted v-if="notification.type == 'App\\Notifications\\SiteDeleted'" :notification="notification"/>
                                <SiteRestored v-if="notification.type == 'App\\Notifications\\SiteRestored'" :notification="notification"/>
                                <SiteUpdated v-if="notification.type == 'App\\Notifications\\SiteUpdated'" :notification="notification" :coins="coins"/>
                            </li>
                        </ul>

                        <div class="text-right mt-5 px-5">
                            <Link :href="route('client.notifications')" class="px-4 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-medium">
                                {{ $t('View all') }}
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="col-span-2">
                    <img src="@/assets/images/linking-pet.png" alt="Linkarme pet"/>
                </div>

            </div>

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-x-12">

                <div class="overflow-hidden rounded-md bg-white shadow h-fit py-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <CloudArrowUpIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block font-bold text-teal-500">{{ $t('Brand new opportunities') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('Latest additions onto our catalog') }}</span>
                        </div>
                    </div>

                    <div class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle border-b border-gray-300 border-opacity-50">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Domain') }}
                                    </th>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('DA') }}
                                    </th>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('DR') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 divide-opacity-50">
                                <tr v-for="(site, index) in news" :key="index">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <Link :href="route('client.sites.show', site.id)"
                                            class="text-gray-500 hover:text-gray-900">
                                        {{ site.real_url }}
                                        </Link>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.da ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.dr ?? '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right px-5 mt-5">
                        <Link :href="route('client.sites.index', { _query: { 'filter[new]': 'true', 'sort': 'new' } })" class="px-4 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-medium">{{ $t('View all') }}</Link>
                    </div>
                </div>

                <div class="overflow-hidden rounded-md bg-white shadow h-fit py-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <HandThumbUpIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block font-bold text-purple-600">{{ $t('Our team recommendations') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('Websites that deserve your attention') }}</span>
                        </div>
                    </div>

                    <div class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle border-b border-gray-300 border-opacity-50">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Domain') }}
                                    </th>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('DA') }}
                                    </th>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('DR') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 divide-opacity-50">
                                <tr v-for="(site, index) in recommended" :key="index">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <Link :href="route('client.sites.show', site.id)"
                                            class="text-gray-500 hover:text-gray-900">
                                        {{ site.real_url }}
                                        </Link>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.da ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.dr ?? '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right px-5 mt-5">
                        <Link :href="route('client.sites.index', { _query: { 'filter[recommended]': 'true', 'sort': 'recommended' } })" class="px-4 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-medium">{{ $t('View all') }}</Link>
                    </div>
                </div>

            </div>

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-x-12">

                <div class="overflow-hidden rounded-md bg-white shadow h-fit py-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <Cog6ToothIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Your projects') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('Find here your selections') }}</span>
                        </div>
                    </div>

                    <div class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle">
                        <table class="w-full">
                            <thead v-if="projects.length > 0">
                                <tr>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Name') }}
                                    </th>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Sites') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 divide-opacity-50">
                                <tr v-if="projects.length == 0">
                                    <td colspan="3" class=" px-6 py-4 text-sm text-gray-500 italic">
                                        <div class="flex flex-col items-center space-y-3">
                                            <span>{{ $t('Create new projects and they will show here') }}</span>
                                            <Link :href="route('client.projects.index')" class="w-fit px-3 py-1.5 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-medium">{{ $t('Create project') }}</Link>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-else v-for="(project, index) in projects" :key="index">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <Link :href="route('client.sites.list', { _query: { 'filter[project]': project.id  } })" class="flex items-center space-x-4 text-gray-500 hover:text-gray-900">
                                            <span :style="{ 'background-color': project.color }" class="block h-2 w-2 rounded-full"></span>
                                            <span>{{ project.name }}</span>
                                        </Link>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ project.sites_count ?? '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="projects.length > 0" class="text-right px-5 mt-5">
                        <Link :href="route('client.projects.index')" class="px-4 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-medium">{{ $t('View all') }}</Link>
                    </div>
                </div>

                <!--  -->
                <div class="overflow-hidden rounded-md bg-white shadow h-fit py-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <FlagIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Your interests') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('Websites that you saved to take a look later') }}</span>
                        </div>
                    </div>

                    <div class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle">
                        <table class="w-full">
                            <thead v-if="interests.length > 0">
                                <tr>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Domain') }}
                                    </th>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('DA') }}
                                    </th>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('DR') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 divide-opacity-50">
                                <tr v-if="interests.length == 0">
                                    <td colspan="3" class=" px-6 py-4 text-sm text-gray-500 italic text-center">
                                        {{ $t('Add websites to your list of interests and they will show here') }}
                                    </td>
                                </tr>
                                <tr v-else v-for="(site, index) in interests" :key="index">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <Link :href="route('client.sites.show', site.id)"
                                            class="text-gray-500 hover:text-gray-900">
                                        {{ site.real_url }}
                                        </Link>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.da ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.dr ?? '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="interests.length > 0" class="text-right px-5 mt-5">
                        <Link :href="route('client.sites.list', { _query: { 'filter[interests]': 'true' } })" class="px-4 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-medium">{{ $t('View all') }}</Link>
                    </div>
                </div>

                <div class="overflow-hidden rounded-md bg-white shadow h-fit py-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <CloudArrowUpIcon class="h-8 w-8"/>
                        </div>

                        <div>
                            <span class="block font-bold">{{ $t('Your favorites') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('The best of the best') }}</span>
                        </div>
                    </div>

                    <div class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle">
                        <table class="w-full">
                            <thead v-if="favorites.length > 0">
                                <tr>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('Domain') }}
                                    </th>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('DA') }}
                                    </th>
                                    <th class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                        scope="col">{{ $t('DR') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 divide-opacity-50">
                                <tr v-if="favorites.length == 0">
                                    <td colspan="3" class=" px-6 py-4 text-sm text-gray-500 italic text-center">
                                        {{ $t('Mark websites as your favorite and they will show here') }}
                                    </td>
                                </tr>
                                <tr v-else v-for="(site, index) in favorites" :key="index">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <Link :href="route('client.sites.show', site.id)"
                                            class="text-gray-500 hover:text-gray-900">
                                        {{ site.real_url }}
                                        </Link>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.da ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ site.dr ?? '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="favorites.length > 0" class="text-right px-5 mt-5">
                        <Link :href="route('client.sites.list', { _query: { 'filter[favorites]': 'true' } })" class="px-4 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-medium">{{ $t('View all') }}</Link>
                    </div>
                </div>
                <!--  -->

            </div>

        </ClientLayoutNew>
    </AppSuspense>
</template>
        