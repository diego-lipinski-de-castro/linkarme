<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import { useTranslation } from "i18next-vue";

const { notification } = defineProps({
    notification: Object,
});

</script>
    
<template>
    <div class="flex space-x-3 px-4 py-4 sm:px-6">
        <div class="flex-1 space-y-1">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium">{{ notification.data.site_url }}</h3>
                <p class="text-sm text-gray-500">{{ new
                        Date(notification.created_at).toLocaleString()
                }}</p>
            </div>
            <p class="text-sm text-gray-500">
                {{ $t('Was restored') }}
            </p>
        </div>
    </div>
</template>
    