<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-blue-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-900 disabled:opacity-25 transition">
        <slot />
    </button>
</template>
