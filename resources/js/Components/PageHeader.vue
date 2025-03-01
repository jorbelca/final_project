<script setup>
import { computed } from "vue";

const props = defineProps({
    title: String,
    links: Array,
});

const computedLinks = computed(() => {
    return props.links || [];
});
</script>

<template #header>
    <div class="flex justify-between items-center w-full">
        <!-- Enlaces a la izquierda -->
        <div class="flex flex-col text-xs sm:text-sm">
            <h2
                v-for="(link, index) in computedLinks"
                :key="index"
                class="font-semibold hover:underline"
                :class="index === 0 ? 'text-green-500' : 'text-primary pt-1'"
            >
                <a :href="route(link.route)">{{ link.text }}</a>
            </h2>
        </div>

        <!-- Título central -->
        <div class="flex-grow text-center pr-14">
            <h2 class="font-semibold text-xl text-text leading-tight">
                {{ title }}
            </h2>
        </div>

        <slot />
    </div>
</template>
