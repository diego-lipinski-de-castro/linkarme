<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import Welcome from '@/Components/Welcome.vue';
import AppSuspense from '../Layouts/AppSuspense.vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    data: Array,
})

</script>

<template>
    <AppSuspense>
        <AppLayoutNew title="Dashboard">
            <template #header>
                <div
                    class="w-full flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Dashboard
                    </h2>
                </div>
            </template>

            <div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 sm:gap-y-6">
                    <Link v-for="(item, index) in data" :key="index" :href="item.link"
                        class="px-8 py-6 bg-white overflow-hidden shadow-sm sm:rounded-md border-b sm:border-none sm:hover:shadow-lg transition-all">
                    <span class="text-2xl">{{ item.value }} {{ item.label }}</span>

                    <div class="mt-2 flex items-center text-sm font-semibold text-blue-500">
                        <span>Visualizar</span>

                        <span class="ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                    </div>
                    </Link>
                </div>
            </div>
        </AppLayoutNew>
    </AppSuspense>
</template>
