<script>
export const stateOptions = {
    draft: "Borrador",
    approved: "Aprobado",
    rejected: "Rechazado",
};
</script>
<script setup>
import { CheckIcon, ClockIcon, XMarkIcon } from "@heroicons/vue/24/solid";

import { computed } from "vue";

const props = defineProps({
    status: String,
    admin: Boolean,
});

// Clases de color según el estado
const statusClasses = computed(() => {
    return (
        {
            draft: "bg-gray-100 text-gray-500",
            approved: "bg-green-500 text-white",
            rejected: "bg-red-500 text-white",
        }[props.status] || "bg-gray-300 text-black"
    );
});

// Iconos según el estado
const statusIcons = {
    draft: ClockIcon,
    approved: CheckIcon,
    rejected: XMarkIcon,
};
</script>

<template>
    <div
        class="status-tile px-1 py-1 rounded-full flex items-center gap-1 max-w-20"
        :class="statusClasses"
    >
        <component :is="statusIcons[status]" class="w-4 h-4" />
        <span>{{ stateOptions[props.status] }}</span>
    </div>
</template>

<style>
.status-tile {
    display: inline-flex;
    font-size: 0.70rem; /* Tamaño del texto */
    white-space: nowrap; /* Evita que el texto salte de linea */
}
</style>
