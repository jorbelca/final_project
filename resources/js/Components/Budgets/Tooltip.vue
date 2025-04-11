<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    text: String,
    position: {
        type: String,
        default: "bottom",
        validator: (value) => ["top", "right", "bottom", "left"].includes(value),
    },
});

const isVisible = ref(false);

const tooltipClass = computed(() => {
    const baseClasses = 'absolute w-max bg-gray-700 text-white text-xs rounded px-2 py-1 transition-opacity z-10';
    const visibilityClass = isVisible.value ? 'opacity-100' : 'opacity-0 group-hover:opacity-100';

    const positionClasses = {
        'right': 'left-full top-0 ml-1',
        'left': 'right-full top-0 mr-1',
        'top': 'bottom-full left-1/2 -translate-x-1/2 mb-1',
        'bottom': 'top-full left-1/2 -translate-x-1/2 mt-1'
    };

    return `${baseClasses} ${visibilityClass} ${positionClasses[props.position]}`;
});

function toggleTooltip() {
    isVisible.value = !isVisible.value;
}
</script>

<template>
    <div class="relative group cursor-pointer inline-block" 
         v-if="text" 
         @mouseleave="isVisible = false">
        <div @click="toggleTooltip">
            <slot></slot>
        </div>
        <span :class="tooltipClass" @click.stop>{{ text }}</span>
    </div>
</template>
