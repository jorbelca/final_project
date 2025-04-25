<script setup>
import { computed } from "vue";
const isForm =
    window.location.pathname.includes("create") ||
    window.location.pathname.includes("edit");

const props = defineProps({
    title: String,
    links: Array,
    padding: Number,
});

const computedLinks = computed(() => {
    return props.links || [];
});
</script>

<template #header>
    <div :class="[
      'items-center w-full',
      computedLinks.length > 0 ? 'grid grid-cols-3' : 'flex'
    ]">
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
                    v-if="
                        !isForm &&
                        !link.text.includes('archivo') &&
                        !link.text.includes('Subir')
                    "
                    class="text-green-500"
                    :href="route(link.route)"
                    >{{ link.text }}</a
                >
                <a
                    v-if="
                        link.text.includes('archivo') ||
                        link.text.includes('Subir')
                    "
                    class="text-blue-500"
                    :href="route(link.route)"
                    >Archivo
                </a>
            </h2>
        </div>
        <!-- Título central -->
        <div class="flex-grow text-center" :class="`pr-${props.padding}`">
            <h2 class="font-semibold text-xl text-text leading-tight">
                {{ title }}
            </h2>
        </div>

        <slot />
    </div>
</template>
