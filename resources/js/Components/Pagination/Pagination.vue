<script setup>
import { computed } from "vue";

const props = defineProps({
    meta: Object, // Recibe `budgets.meta`
});

const emit = defineEmits(["page-change"]);

const pages = computed(() => {
    if (!props.meta) return [];
    let links = props.meta.links;
    return links.slice(1, links.length - 1); // Quitamos "Previous" y "Next"
});
</script>

<template>
    <nav class="flex justify-center mt-4">
        <button
            v-for="(page, index) in pages"
            :key="index"
            class="px-3 py-1 mx-1 border rounded"
            :class="{
                'bg-gray-300 text-black': page.active && !darkMode,
                'bg-gray-700 text-white': page.active && darkMode,
            }"
            v-html="page.label"
            @click="emit('page-change', page.url)"
            :disabled="!page.url"
        ></button>
    </nav>
</template>
