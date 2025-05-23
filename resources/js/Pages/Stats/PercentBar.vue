<script setup>
import { defineProps } from "vue";
defineProps({
    percent: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <div v-if="percent" class="w-full">
        <div class="flex items-center gap-1">
            <div class="w-full h-2 rounded-full bg-gray-200 dark:bg-gray-700">
                <div
                    :style="{ width: percent + '%' }"
                    class="h-2 rounded-full bg-green-400"
                ></div>
            </div>
            <span class="text-text text-sm font-bold"> {{ percent }}% </span>
        </div>
    </div>
</template>
