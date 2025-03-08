<script setup>
import BudgetForm from "@/Components/Budgets/BudgetForm.vue";
import PageHeader from "@/Components/PageHeader.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineProps } from "vue";
const props = defineProps({
    costs: Array,
    clients: Array,
    budget: Object,
    taxes: Number,
});
</script>

<template>
    <AppLayout title="Create a Budget">
        <template #header>
            <PageHeader
                title="Create a Budget"
                :links="[
                    { text: '◀ List of Budgets ', route: 'budgets.index' },
                ]"
                :form="true"
            >
            </PageHeader>
        </template>
        <div>
            <BudgetForm
                :clients="props.clients"
                :costs="props.costs"
                :budget="props.budget"
                :taxes="props.taxes"
            />
        </div>
    </AppLayout>
</template>
