<script setup>
import BudgetForm from "@/Components/BudgetForm.vue";
import { defineProps } from "vue";
const props = defineProps({
    costs: Array,
    clients: Array,
});
</script>

<template>
    <BudgetForm
        title="Budget"
        :clients="props.clients"
        :costs="props.costs"
    ></BudgetForm>
</template>
