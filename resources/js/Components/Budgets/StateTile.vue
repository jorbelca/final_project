<script setup>
import { computed } from "vue";

const props = defineProps({
    status: String,
    admin: Boolean,
});

// Clases de color según el estado
const statusClasses = computed(() => {
    return {
        draft: "bg-gray-100 text-gray-500",
        approved: "bg-green-500 text-white",
        rejected: "bg-red-500 text-white",
    }[props.status] || "bg-gray-300 text-black";
});

// Iconos según el estado
const statusIcons = {
    draft: "🕗",
    approved: "✔",
    rejected: "X",
};
</script>

<template>
    <div class="status-tile px-2 py-1 rounded-full flex items-center gap-1" :class="statusClasses">
        <span>{{ statusIcons[status] }}</span>
        <span v-if="admin">{{ status }}</span>
    </div>
</template>

<style>
.status-tile {
    display: inline-flex;
    font-size: 0.875rem; /* Tamaño del texto */
    white-space: nowrap; /* Evita que el texto se divida */
}
</style>
