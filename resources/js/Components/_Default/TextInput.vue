<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String,
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="text-text dark:bg-hover border-gray-300 dark:border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-700 dark:focus:ring-indigo-700 rounded-md shadow-sm dark:autofill-text"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>

<style>
.dark .dark\:autofill-text:-webkit-autofill,
.dark .dark\:autofill-text:-webkit-autofill:hover,
.dark .dark\:autofill-text:-webkit-autofill:focus {
    -webkit-text-fill-color: inherit;
    -webkit-box-shadow: 0 0 0px 1000px #374151 inset;
    transition: background-color 5000s ease-in-out 0s;
    -webkit-text-fill-color: #e5e7eb;
}
</style>
