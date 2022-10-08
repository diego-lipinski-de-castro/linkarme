<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { computed, ref, watch } from 'vue'

const props = defineProps({

});


</script>
        
<template>
    <AppLayout title="Adicionar site">
        <template #uppermenu>
            
        </template>

        <template #header>
            <div
                class="flex justify-between items-center px-4 sm:px-6 lg:mx-auto lg:px-8 pt-6 lg:border-t lg:border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Adicionar site
                </h2>
            </div>
        </template>
    </AppLayout>
</template>
        