<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import Welcome from '@/Components/Welcome.vue';
import AppSuspense from '../Layouts/AppSuspense.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { GoogleCharts } from 'google-charts';
import { onMounted } from 'vue';

const props = defineProps({
    sitesByCountry: Array,
})

const drawChart = () => {
    const data = google.visualization.arrayToDataTable(props.sitesByCountry);

    const options = {
        backgroundColor: 'transparent',
    };

    const chart = new google.visualization.GeoChart(document.getElementById('map_div'));

    chart.draw(data, options);
}

onMounted(() => {
    GoogleCharts.load(drawChart, {
        'packages': ['geochart'],
        // 'language': 'pt',
    });
})

</script>

<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Analytics')">
            <template #header>
                <div
                    class="w-full flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $t('Analytics') }}
                    </h2>
                </div>
            </template>

            <div>
                <div class="flex justify-center">
                    <div class="w-fit bg-black bg-opacity-10 p-5 rounded-md">
                        <div id="map_div" style="height: 50vh;"></div>
                    </div>
                </div>
            </div>
        </AppLayoutNew>
    </AppSuspense>
</template>
