<script setup>
import ClientLayoutNew from '@/Layouts/ClientLayoutNew.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { ref } from 'vue'
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Switch, SwitchGroup, SwitchLabel,
} from '@headlessui/vue'
import {
    Cog6ToothIcon, EllipsisVerticalIcon,
} from '@heroicons/vue/24/outline'

import AppSuspense from '../../../Layouts/AppSuspense.vue';

const props = defineProps({
    title: String,
    projects: Array,
});
</script>

<template>
    <AppSuspense>
        <ClientLayoutNew :title="$t('Projects')">
            <div>
                <div class="rounded-md bg-white px-5 py-6 shadow sm:px-6">
                    <div class="flex flex-col min-h-48">

                        <div class="flex items-center space-x-2">
                            <div class="w-10">
                                <Cog6ToothIcon class="h-8 w-8"/>
                            </div>

                            <div>
                                <h2 class="text-xl font-bold leading-tight">{{ $t('Your projects') }}</h2>
                                <span class="block text-xs text-gray-400">{{ $t('Find here your selections') }}</span>
                            </div>
                        </div>

                        <ul role="list" class="mt-6 grid grid-cols-1 sm:grid-cols-4 gap-5 sm:gap-6">
                            <li v-for="(project, index) in projects" :key="index" class="col-span-1 rounded-md shadow-sm flex">
                                <Link href="#" class="w-full flex hover:opacity-75 transition-opacity">
                                    <div :style="{ 'background-color': project.color }" class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md">{{ project.name }}</div>

                                    <div class="flex flex-1 items-center justify-between truncate bg-white border-t border-b border-gray-200">
                                        <div class="flex-1 truncate px-4 py-2 text-sm">
                                            <a :href="project.href" class="font-medium text-gray-900 hover:text-gray-600">{{ project.name }}</a>
                                            <p class="text-gray-500">{{ project.sites_count }} {{ project.sites_count == 1 ? $t('website') : $t('websites') }}</p>
                                        </div>
                                    </div>
                                </Link>

                                <div class="pr-2 border-t border-b border-r border-gray-200 rounded-r-md">
                                    <button type="button" class="inline-flex h-full w-8 items-center justify-center rounded-full bg-white bg-transparent text-gray-400 hover:text-gray-500 focus:outline-none">
                                        <span class="sr-only">Open options</span>
                                        <EllipsisVerticalIcon class="h-5 w-5" aria-hidden="true" />
                                    </button>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </ClientLayoutNew>
    </AppSuspense>
</template>
