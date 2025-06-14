<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label class="block font-bold text-sm text-text pb-1">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
