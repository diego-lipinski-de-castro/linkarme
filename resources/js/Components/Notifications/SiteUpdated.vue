<script setup>
import { Link } from '@inertiajs/vue3';
import { useTranslation } from "i18next-vue";
import { getCurrentInstance } from 'vue'

const { notification, coins } = defineProps({
    notification: Object,
    coins: Object,
});

const app = getCurrentInstance()

const attributes = {
    sale: (value, frame) => {
        const format = notification.data.audit_modified.sale_coin[frame];

        return app.appContext.config.globalProperties.$filters.currency(value / 100, coins[format]);
    },
    gambling: (value) => value,
    cdb: (value) => value,
    cripto: (value) => value,
    sponsor: (value) => value,
    menu: (value) => value,
    banner: (value) => value,
}
</script>
    
<template>
    <div class="flex space-x-3 px-4 py-4 sm:px-6">
        <div class="flex-1 space-y-1">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium">{{ notification.data.site_url }}</h3>
                <!-- <p class="text-sm text-gray-500">{{ new Date(notification.created_at).toLocaleString() }}</p> -->
                <p class="text-sm text-gray-500">{{ notification.diff }}</p>
            </div>
            <p v-for="(value, attribute) in notification.data.audit_modified" :key="attribute"
                class="text-sm text-gray-500">

                <template v-if="attributes[attribute]">

                    <template v-if="Object.hasOwnProperty(value.old)">
                        <span v-if="!Object.hasOwnProperty(value.new)" class="break-words"
                            v-html="$t('attributes.new', { interpolation: { escapeValue: false }, attribute: `<strong>${$filters.capitalize(attribute)}</strong>`, new: `<strong>${attributes[attribute](value.new, 'new')}</strong>` })">
                        </span>

                        <span v-else class="break-words"
                            v-html="$t('attributes.blank', { interpolation: { escapeValue: false }, attribute: `<strong>${$filters.capitalize(attribute)}</strong>` })">
                        </span>
                    </template>

                    <span v-else-if="Object.hasOwnProperty(value.new)" class="break-words"
                        v-html="$t('attributes.to_blank', { interpolation: { escapeValue: false }, attribute: `<strong>${$filters.capitalize(attribute)}</strong>`, old: `<strong>${attributes[attribute](value.old, 'old')}</strong>` })">
                    </span>

                    <span v-else class="break-words" v-html="$t('attributes.updated', {
                        interpolation: { escapeValue: false },
                        attribute: `<strong>${$filters.capitalize(attribute)}</strong>`, old: `<strong>${attributes[attribute](value.old, 'old')}</strong>`, new: `<strong>${attributes[attribute](value.new, 'new')}</strong>`
                    })">
                    </span>

                </template>
            </p>
        </div>
    </div>
</template>
    