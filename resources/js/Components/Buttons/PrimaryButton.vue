<script setup>
import { computed } from "vue";

const props = defineProps({
    type: {
        type: String,
        default: "submit",
    },
    color: {
        type: String,
        default: "blue", // Color por defecto si no se pasa por props
    },
});

// Computed para obtener la clase de color
const buttonColor = computed(
    () =>
        `bg-${props.color}-500 hover:bg-${props.color}-700 active:bg-${props.color}-900`
);
</script>

<template>
    <button
        :type="type"
        :class="`inline-flex items-center justify-center px-4 py-2 ${buttonColor} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150`"
    >
        <slot />
    </button>
</template>
