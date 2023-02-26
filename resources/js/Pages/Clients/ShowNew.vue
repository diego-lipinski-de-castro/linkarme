<script setup>
import AppLayoutNew from '@/Layouts/AppLayoutNew.vue';

defineProps({
    client: Object,
});

</script>

<template>
    <AppSuspense>
        <AppLayoutNew :title="$t('Show client')">
            
            <div>
                <div class="max-w-7xl mx-auto rounded-md bg-white shadow">
                    
                    

                </div>

                <div class="mt-10 max-w-7xl mx-auto rounded-md bg-white shadow overflow-hidden pt-5">
                    
                </div>
            </div>
        </AppLayoutNew>
    </AppSuspense>
</template>
