<script setup>
defineProps({
    white: Boolean,
});

</script>
        
<template>
    <img v-if="white" src="@/assets/logo-linking.svg" alt="Linkarme">
    <img v-else src="@/assets/logo.svg" alt="Linkarme">
</template>