<script setup >
import { ref } from "vue";
import { showDescription } from "./helpers";

defineProps({
    content: {
        type: Array,
        required: true,
    },
    isMobile: {
        type: Boolean,
        required: false,
        default: false,
    },
});


const isOpen = ref(false);
</script>

<template>
    <div v-if="isMobile">
        <button
            @click="isOpen = !isOpen"
            class="bg-gray-200 px-4 py-1 rounded flex items-center dark:bg-gray-600 text-text dark:border-gray-600"
        >
            Contenido
            <span class="ml-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 transition-transform duration-300 dark:text-white"
                    :class="isOpen ? 'rotate-180' : ''"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </span>
        </button>

        <transition name="fade">
            <div v-if="isOpen">
                <div
                    v-for="(item, key) in content"
                    :key="key"
                    class="flex flex-row"
                >
                    <div
                        class="flex flex-row gap-2 items-center whitespace-nowrap"
                    >
                        <span class="text-xs whitespace-nowrap"
                            >{{ item.quantity }}x</span
                        >
                        <span
                            v-html="showDescription(item)"
                            class="flex-shrink-0"
                        ></span>
                        <span class="text-xs">{{ item.cost }}$</span>
                        <span class="text-xs font-semibold"
                            >= {{ item.quantity * item.cost }}$</span
                        >
                    </div>
                </div>
            </div>
        </transition>
    </div>
    <div v-else>
        <div v-for="(item, key) in content" :key="key" class="flex flex-row">
            <div class="flex flex-row gap-2 items-center whitespace-nowrap">
                <span class="text-xs whitespace-nowrap"
                    >{{ item.quantity }}x</span
                >
                <span
                    v-html="showDescription(item)"
                    class="flex-shrink-0"
                ></span>
                <span class="text-xs">{{ item.cost }}$</span>
                <span class="text-xs font-semibold"
                    >= {{ item.quantity * item.cost }}$</span
                >
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Animación de entrada y salida */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
