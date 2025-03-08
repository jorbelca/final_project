<script setup>
import BudgetForm from "@/Components/Budgets/BudgetForm.vue";
import PageHeader from "@/Components/PageHeader.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    clients: Array,
    costs: Array,
    budget: Object,
});
</script>

<template>
    <AppLayout :title="'Budget ' + props.budget.id">
        <template #header>
            <PageHeader
                :title="'Edition Budget  ' + props.budget.id"
                :links="[
                    { text: '◀ List of Budgets ', route: 'budgets.index' },
                ]"
            >
            </PageHeader>
        </template>
        <BudgetForm
            :clients="props.clients"
            :costs="props.costs"
            :budget="props.budget"
        />
    </AppLayout>
</template>
