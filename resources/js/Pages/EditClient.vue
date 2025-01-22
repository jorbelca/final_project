<script setup>
import Form from "@/Components/Form.vue";
import { onMounted, defineProps } from "vue";

const props = defineProps({
    client: Object,
});
</script>

<template>
    <Form title="Client" :client="props.client"></Form>
</template>
