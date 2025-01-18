<script setup>
import Form from "@/Components/BudgetForm.vue";
import { defineProps } from "vue";
const props = defineProps({
    costs: Array,
    clients: Array,
});
</script>

<template>
    <Form title="Budget" :clients="props.clients" :costs="props.costs"></Form>
</template>
