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

// Computed para obtener la clase de color según la prop
const buttonColor = computed(() => {
    const colors = {
        blue: 'bg-blue-500 hover:bg-blue-600 active:bg-blue-900 dark:bg-blue-600 dark:hover:bg-blue-700 dark:active:bg-blue-800',
        red: 'bg-red-500 hover:bg-red-600 active:bg-red-900 dark:bg-red-600 dark:hover:bg-red-700 dark:active:bg-red-800',
        green: 'bg-green-500 hover:bg-green-600 active:bg-green-900 dark:bg-green-600 dark:hover:bg-green-700 dark:active:bg-green-800',
        yellow: 'bg-yellow-500 hover:bg-yellow-600 active:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:active:bg-yellow-800',
        indigo: 'bg-indigo-500 hover:bg-indigo-600 active:bg-indigo-900 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:active:bg-indigo-800',
    };
    return colors[props.color] || colors['blue'];
});
</script>

<template>
    <button
        :type="type"
        :class="`inline-flex items-center justify-center px-4 py-2 ${buttonColor} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150`"
    >
        <slot />
    </button>
</template>
