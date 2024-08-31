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
} from '@headlessui/vue'
import {
    Cog6ToothIcon,
    EllipsisVerticalIcon,
    PencilIcon,
    PlusCircleIcon,
    TrashIcon,
} from '@heroicons/vue/24/outline'
import AppSuspense from '../../../Layouts/AppSuspense.vue';
import CreateProjectForm from  './Create.vue'
import EditProjectForm from  './Edit.vue'

const props = defineProps({
    title: String,
    projects: Array,
});

const destroy = (project) => {
    Inertia.delete(route('client.projects.destroy', project))
}

const open = ref(false)
const edit = ref(false)

const selected = ref(null)

const onSelect = (project) => {
    selected.value = project;
    edit.value = true;
}
</script>

<template>
    <AppSuspense>
        <ClientLayoutNew :title="$t('Projects')">

            <CreateProjectForm :show="open" @close="open = false"/>
            <EditProjectForm :show="edit" @close="edit = false" :project="selected"/>

            <div>
                <div class="rounded-md bg-white px-5 py-6 shadow sm:px-6">
                    <div class="flex flex-col">

                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <div class="w-10">
                                    <Cog6ToothIcon class="h-8 w-8"/>
                                </div>

                                <div>
                                    <h2 class="text-xl font-bold leading-tight">{{ $t('Your projects') }}</h2>
                                    <span class="block text-xs text-gray-400">{{ $t('Find here your selections') }}</span>
                                </div>
                            </div>

                            <button @click="open = true" class="text-gray-700 transition-transform hover:scale-110">
                                <PlusCircleIcon class="h-8 w-8"/>
                            </button>
                        </div>

                        <ul role="list" class="mt-6 grid grid-cols-1 sm:grid-cols-4 gap-5 sm:gap-6">
                            <li v-for="(project, index) in projects" :key="index" class="col-span-1 rounded-md shadow-sm flex">
                                <Link :href="route('client.sites.list', { _query: { 'filter[project]': project.id  } })" class="w-full flex hover:opacity-75 transition-opacity">
                                    <div :style="{ 'background-color': project.color }" class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md">{{ project.short_name }}</div>

                                    <div class="flex flex-1 items-center justify-between truncate bg-white border-t border-b border-gray-200">
                                        <div class="flex-1 truncate px-4 py-2 text-sm">
                                            <a :href="project.href" class="font-medium text-gray-900 hover:text-gray-600">{{ project.name }}</a>
                                            <p class="text-gray-500">{{ project.sites_count }} {{ project.sites_count == 1 ? $t('website') : $t('websites') }}</p>
                                        </div>
                                    </div>
                                </Link>

                                <div class="pr-2 border-t border-b border-r border-gray-200 rounded-r-md">
                                    <Menu as="div" class="h-full relative flex-shrink-0">
                                        <MenuButton class="inline-flex h-full w-8 items-center justify-center rounded-full bg-white bg-transparent text-gray-400 hover:text-gray-500 focus:outline-none">
                                            <span class="sr-only">Open options</span>
                                            <EllipsisVerticalIcon class="h-5 w-5" aria-hidden="true" />
                                        </MenuButton>

                                        <transition enter-active-class="transition ease-out duration-100"
                                            enter-from-class="transform opacity-0 scale-95"
                                            enter-to-class="transform opacity-100 scale-100"
                                            leave-active-class="transition ease-in duration-75"
                                            leave-from-class="transform opacity-100 scale-100"
                                            leave-to-class="transform opacity-0 scale-95">
                                            <MenuItems class="absolute right-0 z-10 -mt-2 w-28 origin-top-right rounded-md overflow-hidden bg-white border border-gray-300 border-opacity-50 shadow-sm focus:outline-none">
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="onSelect(project)" :class="[active ? 'bg-gray-100' : '', 'w-full flex items-center space-x-4 py-2 px-4 text-sm text-gray-500']">
                                                        <PencilIcon class="h-4 w-4"/>
                                                        <span>{{ $t('Edit') }}</span>
                                                    </button>
                                                </MenuItem>

                                                <MenuItem v-slot="{ active }">
                                                    <button @click="destroy(project.id)" :class="[active ? 'bg-gray-100' : '', 'w-full flex items-center space-x-4 py-2 px-4 text-sm text-red-500']">
                                                        <TrashIcon class="h-4 w-4"/>
                                                        <span>{{ $t('Delete') }}</span>
                                                    </button>
                                                </MenuItem>
                                            </MenuItems>
                                        </transition>
                                    </Menu>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </ClientLayoutNew>
    </AppSuspense>
</template>
