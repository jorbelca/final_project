<script setup>
import BudgetForm from "@/Components/BudgetForm.vue";

const props = defineProps({
    budget: Object,
});
</script>

<template>
    <BudgetForm title="Cost" :budget="props.budget"></BudgetForm>
</template>
