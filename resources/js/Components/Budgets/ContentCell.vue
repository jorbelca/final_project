<script setup>
import { ref } from "vue";
import { showDescription } from "./helpers";

defineProps({
    content: {
        type: Array,
        required: true,
    },
    isMobile: {
        type: Boolean,
        default: false,
    },
});

const isOpen = ref(false);
</script>

<template>
    <div v-if="isMobile" class="flex flex-col items-center gap-2">
        <button
            @click="isOpen = !isOpen"
            class="bg-gray-200 px-2 py-1 rounded flex items-center transition-all duration-300 dark:bg-gray-600 text-text dark:border-gray-600"
            :class="{ '-ml-4': isOpen }"
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
            <div
                v-if="isOpen"
                class="bg-gray-200 p-1 rounded flex flex-col gap-2 dark:bg-gray-600 text-text dark:border-gray-600"
            >
                <div
                    v-for="(item, key) in content"
                    :key="key"
                    class="flex gap-1 items-center whitespace-nowrap"
                >
                    <span class="text-xs">{{ item.quantity }}x</span>
                    <span
                        v-html="showDescription(item)"
                        class="flex-shrink-0 text-xs"
                    ></span>
                    <span class="text-xs">{{ item.cost }}€</span>
                    <span class="text-xs font-semibold"
                        >= {{ item.quantity * item.cost }}€</span
                    >
                </div>
            </div>
        </transition>
    </div>

    <div v-else>
        <div
            v-for="(item, key) in content"
            :key="key"
            class="flex gap-1 items-center whitespace-nowrap"
        >
            <span class="text-xs">{{ item.quantity }}x</span>
            <span v-html="showDescription(item)" class="flex-shrink-0"></span>
            <span class="text-xs">{{ item.cost }}€</span>
            <span class="text-xs font-semibold"
                >= {{ item.quantity * item.cost }}€</span
            >
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
