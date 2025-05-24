<script setup>
defineProps({
    status: {
        type: String,
        required: true,
    },
});
defineEmits(["status-changed"]);
</script>

<template>
    <div
        :class="{
            'status-dot-answered': status === 'active' || status === 'answered',
            'status-dot-pending': status === 'inactive' || status === 'pending',
        }"
        class="w-3 h-3 rounded-full"
    ></div>
</template>

<style>
/* Transiciones suaves */
.transition-colors {
    transition-property: color, background-color, border-color;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Puntos de estado pulsantes */
.status-dot-answered {
    @apply bg-green-500;
    animation: pulse-green 2s infinite;
}

.status-dot-pending {
    @apply bg-red-500;
    animation: pulse-red 2s infinite;
}

@keyframes pulse-green {
    0%,
    100% {
        @apply bg-green-500;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    }
    50% {
        @apply bg-green-400;
        box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
    }
}

@keyframes pulse-red {
    0%,
    100% {
        @apply bg-red-500;
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    }
    50% {
        @apply bg-red-400;
        box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
    }
}
</style>
