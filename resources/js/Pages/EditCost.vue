<script setup>
import Form from "@/Components/Form.vue";

const props = defineProps({
    cost: Object,
});
</script>

<template>
    <Form title="Cost" :cost="props.cost"></Form>
</template>
