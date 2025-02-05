<script setup>
import StateTile from "@/Components/StateTile.vue";

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
            <StateTile :admin="admin" status="draft" />
            <span>{{ budgetCounts.draft }}</span>
        </span>

        <span v-if="budgetCounts.approved" class="flex items-center gap-1 py-1">
            <StateTile :admin="admin" status="approved" />
            <span>{{ budgetCounts.approved }}</span>
        </span>

        <span v-if="budgetCounts.rejected" class="flex items-center gap-1 py-1">
            <StateTile :admin="admin" status="rejected" />
            <span>{{ budgetCounts.rejected }}</span>
        </span>
    </div>
</template>
