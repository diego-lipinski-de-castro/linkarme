<script setup>
    const emits = defineEmits(['onClick'])

    console.log(emits)

    const props = defineProps({
        current: String,
        column: String
    })

    const handleClick = () => {
        emits('onClick', props.current == props.column ? (props.current = `-${props.column}`) : (props.current = props.column))
    }
</script>

<template>
    <button @click="handleClick"
        :class="[{
            'rotate-180': current == `-${column}`,
        }, (current == column || current == `-${column}`) ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300': 'invisible text-gray-400 group-hover:visible group-focus:visible', 'rounded ml-2 cursor-pointer']">
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
            aria-hidden="true">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </button>
</template>