<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import Welcome from '@/Components/Welcome.vue';
import AppSuspense from '../Layouts/AppSuspense.vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    data: Array,
    list: Array,
})

console.log(props.list)

</script>

<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Dashboard')">
            <div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 sm:gap-y-6 rounded-md overflow-hidden shadow-sm sm:rounded-none sm:shadow-none">
                    <Link v-for="(item, index) in data" :key="index" :href="item.link" class="px-8 py-6 bg-white overflow-hidden shadow-sm sm:rounded-md border-b sm:border-none sm:hover:shadow-lg transition-all">
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
                                        <th
                                            v-for="(column, c) in j.columns" :key="c"
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
        </AppLayoutNew>
    </AppSuspense>
</template>
