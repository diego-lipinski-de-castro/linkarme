<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';
import Welcome from '@/Components/Welcome.vue';
import AppSuspense from '../Layouts/AppSuspense.vue';
import { Link } from '@inertiajs/vue3';
import { GoogleCharts } from 'google-charts';
import { onMounted } from 'vue';
import svgMap from 'svgmap';
import 'svgmap/dist/svgMap.min.css';

const props = defineProps({
    sitesByCountry: Array,
    sitesByCountrySvg: Array,
})

const drawChart = () => {
    const data = google.visualization.arrayToDataTable(props.sitesByCountry);

    const options = {
        backgroundColor: 'transparent',
    };

    const chart = new google.visualization.GeoChart(document.getElementById('map_div'));

    chart.draw(data, options);
}

const drawSvgMap = () => {

    const data = {}

    props.sitesByCountrySvg.forEach((item) => {
        data[item[0]] = {
            sites: item[1]
        }
    })

    new svgMap({
        targetElementID: 'svg_map',
        flagType: 'emoji',
        data: {
            data: {
                sites: {
                    name: 'Sites per country:',
                },
            },
            applyData: 'sites',
            values: data,
        }
    });
}

onMounted(() => {
    setTimeout(() => {
        // GoogleCharts.load(drawChart, {
        //     'packages': ['geochart'],
        //     // 'language': 'pt',
        // });

        drawSvgMap()
    }, 200)
})

</script>

<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Analytics')">
            <div class="px-2">
                <h1 class="mt-6 text-white font-bold text-4xl">{{ $t('Analytics') }}</h1>

                <div class="mt-24 px-2">

                    <div>
                        <span class="text-black font-bold text-xl">{{ $t('Sites by country') }}</span>

                        <div class="mt-5 flex justify-center">
                            <div class="w-fit bg-black bg-opacity-10 p-5 rounded-md">
                                <div id="map_div" style="width: 60vw;"></div>
                            </div>
                        </div>

                        <div class="mt-5 flex justify-center">
                            <div class="w-fit bg-black bg-opacity-10 p-5 rounded-md">
                                <div id="svg_map" style="width: 60vw;"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </AppLayoutNew>
    </AppSuspense>
</template>
