<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import AppSuspense from '../Layouts/AppSuspense.vue';
import { Link } from '@inertiajs/vue3';
import {
    CloudArrowUpIcon,
    HandThumbUpIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
    data: Array,
    list: Array,
    news: Array,
    recommended: Array,
})
</script>

<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Dashboard')">
            <div v-show="false">
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-x-6 sm:gap-y-6 rounded-md overflow-hidden shadow-sm sm:rounded-none sm:shadow-none">
                    <Link v-for="(item, index) in data" :key="index" :href="item.link"
                        class="px-8 py-6 bg-white overflow-hidden shadow-sm sm:rounded-md border-b sm:border-none sm:hover:shadow-lg transition-all">
                    <span class="text-2xl">{{ item.value }} {{ item.label }}</span>

                    <div class="mt-2 flex items-center text-sm font-semibold text-blue-500">
                        <span>{{ $t('Visualizar') }}</span>

                        <span class="ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                    </div>
                    </Link>
                </div>

                <!--  -->
                <div v-for="(i, ii) in list" :key="ii" class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div v-for="(j, jj) in i" :key="jj" class=" overflow-hidden rounded-md bg-white shadow h-fit py-5">

                        <div class="px-5">
                            <span class="block font-bold text-gray-900">{{ j.label }}</span>
                        </div>

                        <div
                            class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle border-b border-gray-300 border-opacity-50">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th v-for="(column, c) in j.columns" :key="c"
                                            class="whitespace-nowrap bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">{{ column }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-300 divide-opacity-50">
                                    <tr v-for="(k, kkk) in j.value" :key="kkk">
                                        <td v-for="(attribute, a) in j.attributes" :key="a"
                                            :class="['whitespace-nowrap px-6 py-4 text-sm', a != 0 ? 'text-gray-500' : '']">
                                            {{ k[attribute] }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- <div class="text-right px-5 mt-5">
                            <Link href="#" class="px-4 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-medium">
                                {{ $t('View all') }}
                            </Link>
                        </div> -->
                    </div>

                </div>
                <!--  -->
            </div>

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-x-12">

                <div class="overflow-hidden rounded-md bg-white shadow h-fit py-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <CloudArrowUpIcon class="h-8 w-8" />
                        </div>

                        <div>
                            <span class="block font-bold text-teal-500">{{ $t('Brand new opportunities') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('Latest additions onto our catalog')
                                }}</span>
                        </div>
                    </div>

                    <div
                        class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle border-b border-gray-300 border-opacity-50">
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
                        <Link :href="route('client.sites.index', { _query: { 'filter[new]': 'true', 'sort': 'new' } })"
                            class="px-4 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-medium">
                        {{ $t('View all') }}</Link>
                    </div>
                </div>

                <div class="overflow-hidden rounded-md bg-white shadow h-fit py-5">
                    <div class="flex items-center space-x-2 px-5">
                        <div class="w-10">
                            <HandThumbUpIcon class="h-8 w-8" />
                        </div>

                        <div>
                            <span class="block font-bold text-purple-600">{{ $t('Our team recommendations') }}</span>
                            <span class="block text-xs text-gray-400">{{ $t('Websites that deserve your attention')
                                }}</span>
                        </div>
                    </div>

                    <div
                        class="mt-5 min-w-full overflow-hidden overflow-x-auto align-middle border-b border-gray-300 border-opacity-50">
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
                        <Link
                            :href="route('client.sites.index', { _query: { 'filter[recommended]': 'true', 'sort': 'recommended' } })"
                            class="px-4 py-2 rounded-md bg-blue-900 hover:bg-opacity-75 transition-colors text-white text-sm font-medium">
                        {{ $t('View all') }}</Link>
                    </div>
                </div>

            </div>
        </AppLayoutNew>
    </AppSuspense>
</template>
