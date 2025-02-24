<script setup>
import BudgetForm from "@/Components/Budgets/BudgetForm.vue";

const props = defineProps({
    clients: Array,
    costs: Array,
    budget: Object,
});
</script>

<template>
    <BudgetForm
        title="Budget"
        :clients="props.clients"
        :costs="props.costs"
        :budget="props.budget"
    ></BudgetForm>
</template>
