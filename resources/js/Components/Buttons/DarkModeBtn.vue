<script setup>
import { ref, onMounted } from "vue";

// Estado del tema (true = dark, false = light)
const isDark = ref(false);

// Función para alternar el tema
const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add("dark");
        localStorage.setItem("theme", "dark");
    } else {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("theme", "light");
    }
};

// Al montar el componente, recuperar el tema guardado
onMounted(() => {
    const savedTheme = localStorage.getItem("theme");
    if (
        savedTheme === "dark" ||
        (!savedTheme &&
            window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        isDark.value = true;
        document.documentElement.classList.add("dark");
    }
});
</script>

<template>
    <button
        @click="toggleTheme"
        class="right-0 top-0 pt-0 pr-0 text-gray-500 dark:text-gray-400 rounded-lg text-sm "
    >
        <svg
            v-if="!isDark"
            class="w-5 h-5"
            fill="currentColor"
            viewBox="0 0 20 20"
        >
            <!-- Ícono de luna (modo oscuro) -->
            <path
                d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"
            ></path>
        </svg>
        <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <!-- Ícono de sol (modo claro) -->
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
            ></path>
        </svg>
    </button>
</template>
