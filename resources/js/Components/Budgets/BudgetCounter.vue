<script setup>
import {
    CheckCircleIcon,
    ClockIcon,
    XCircleIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    budgets: Array,
    admin: Boolean,
});

const getBudgetCounts = (budgets) => {
    return budgets.reduce((acc, budget) => {
        acc[budget.state] = (acc[budget.state] || 0) + 1;
        return acc;
    }, {});
};

const budgetCounts = getBudgetCounts(props.budgets);
</script>

<template>

    <div :class="admin ? 'flex flex-col ' : 'flex flex-row gap-2'">
        <span v-if="budgetCounts.draft" class="flex items-center gap-1 py-1">
            <ClockIcon class="w-4 h-4 text-gray-500 dark:text-gray-200" />
            <span>{{ budgetCounts.draft }}</span>
        </span>

        <span v-if="budgetCounts.approved" class="flex items-center gap-1 py-1">
            <CheckCircleIcon class="w-4 h-4 text-green-500" />
            <span>{{ budgetCounts.approved }}</span>
        </span>

        <span v-if="budgetCounts.rejected" class="flex items-center gap-1 py-1">
            <XCircleIcon class="w-4 h-4 text-red-500" />
            <span>{{ budgetCounts.rejected }}</span>
        </span>
    </div>
</template>
