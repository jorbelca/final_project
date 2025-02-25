<script setup>
defineProps({
    type: {
        type: String,
        default: "submit",
    },
});
</script>

<template>
    <button
        :color="color"
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-color border border-transparent rounded-md font-semibold text-xs text-text uppercase tracking-widest hover:bg-color-700 focus:bg-color-700 active:bg-color-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
