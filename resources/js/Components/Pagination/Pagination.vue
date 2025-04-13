<script setup>
import { computed } from "vue";

const props = defineProps({
    meta: Object, // Recibe `budgets.meta`
    pageSizeOptions: {
        type: Array,
        default: () => [4, 8, 12, 16, 20],
    },
});

const emit = defineEmits(["page-change ", "page-size-change"]);

const pages = computed(() => {
    if (!props.meta) return [];
    let links = props.meta.links;
    return links.slice(1, links.length - 1); // Quitamos "Previous" y "Next"
});
const currentPageSize = computed(() => {
    if (!props.meta) return 0;
    return props.meta.per_page;
});
</script>

<template>
    <nav class="flex justify-center mt-4">
        <select
            class="pl-3 pr-8 py-1 border rounded border-gray-300 dark:border-gray-700 text-text bg-gray-50 hover:bg-gray-200 dark:hover:bg-gray-600 dark:bg-black dark:active:bg-gray-700 cursor-pointer absolute left-0 sm:ml-10 ml-8"
            @change="emit('page-size-change', parseInt($event.target.value))"
            :value="currentPageSize"
            default="currentPageSize"
        >
            <option
                v-for="(size, index) in pageSizeOptions"
                :key="index"
                :value="size"
            >
                {{ size }}
            </option>
        </select>

        <button
            v-for="(page, index) in pages"
            :key="index"
            class="px-3 py-1 mx-1 border rounded text-text"
            :class="[
                page.active
                    ? 'bg-gray-200 dark:bg-gray-700 cursor-default' // Active page styles (light & dark)
                    : 'hover:bg-gray-200 dark:hover:bg-gray-600 dark:bg-black dark:active:bg-gray-800', // Inactive page styles (light & dark)
            ]"
            v-html="page.label"
            @click="emit('page-change', page.url)"
            :disabled="!page.url || page.active || pages.length === 1"
        ></button>
    </nav>
</template>
