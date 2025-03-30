<script setup>
import { computed } from "vue";
const isForm =
    window.location.pathname.includes("create") ||
    window.location.pathname.includes("edit");

const props = defineProps({
    title: String,
    links: Array,
    padding: { Number, default: 14 },
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
                class="font-semibold hover:underline text-primary pt-1"
            >
                <a
                    v-if="isForm"
                    class="text-yellow-500"
                    :href="route(link.route)"
                    >{{ link.text }}</a
                >
                <a
                    v-if="!isForm && !link.text.includes('archivo')"
                    class="text-green-500"
                    :href="route(link.route)"
                    >{{ link.text }}</a
                >
                <a
                    v-if="link.text.includes('archivo')"
                    class="text-blue-500"
                    :href="route(link.route)"
                    >{{ link.text }}</a
                >
            </h2>
        </div>
        <!-- Título central -->
        <div class="flex-grow text-center" :class="` pr-28`">
            <h2 class="font-semibold text-xl text-text leading-tight">
                {{ title }}
            </h2>
        </div>

        <slot />
    </div>
</template>
