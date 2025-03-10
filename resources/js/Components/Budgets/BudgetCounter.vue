<script setup>
import {
    CheckCircleIcon,
    ClockIcon,
    XCircleIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    budgetCount: Object, // Se recibe desde Laravel
    admin: Boolean,
});
</script>

<template>
    <div :class="admin ? 'flex flex-col ' : 'flex flex-row gap-2'">
        <span v-if="budgetCount.draft" class="flex items-center gap-1 py-1">
            <ClockIcon class="w-4 h-4 text-gray-500 dark:text-gray-200" />
            <span>{{ budgetCount.draft || 0 }}</span>
        </span>

        <span v-if="budgetCount.approved" class="flex items-center gap-1 py-1">
            <CheckCircleIcon class="w-4 h-4 text-green-500" />
            <span>{{ budgetCount.approved || 0 }}</span>
        </span>

        <span v-if="budgetCount.rejected" class="flex items-center gap-1 py-1">
            <XCircleIcon class="w-4 h-4 text-red-500" />
            <span>{{ budgetCount.rejected || 0 }}</span>
        </span>
    </div>
</template>
