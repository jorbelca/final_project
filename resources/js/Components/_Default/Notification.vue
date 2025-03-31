<script setup>
import { ref, onMounted } from "vue";

let props = defineProps({
    message: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        required: true, // Solo permitirá "success" o "error"
    },
});

const isVisible = ref(true);

onMounted(() => {
    setTimeout(() => {
        isVisible.value = false;
    }, 3000); // Desaparece después de 3 segundos
});

const getClass = () => {
    return props.type === "success"
        ? "bg-green-500 text-white"
        : "bg-red-500 text-white";
};
</script>

<template>
    <div
        v-if="isVisible"
        :class="`fixed top-5 right-5 px-4 py-2 rounded shadow-lg ${getClass()}`"
    >
        <p>{{ message }}</p>
    </div>
</template>

<style scoped>
.notification {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
