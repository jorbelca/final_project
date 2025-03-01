<script setup>
import { ref } from "vue";
import {
    ChevronDoubleDownIcon,
    ChevronDoubleUpIcon,
} from "@heroicons/vue/24/solid";

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
            class="bg-gray-200 px-4 py-1 rounded flex items-center"
        >
            Content
            <ChevronDoubleDownIcon v-if="!isOpen" class="w-5 h-4 font-bold" />
            <ChevronDoubleUpIcon v-else class="w-5 h-4" />
        </button>

        <transition name="fade">
            <div v-if="isOpen">
                <div
                    v-for="(item, key) in content"
                    :key="key"
                    class="flex flex-row justify-between border-b border-gray-200"
                >
                    <div class="flex flex-row gap-1">
                        <span class="text-xs">{{ item.quantity }} x </span>
                        <span class="text-xs text-gray-500">{{
                            item.description
                        }}</span>
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
            <div class="flex flex-row gap-1">
                <span class="text-xs">{{ item.quantity }} x </span>
                <span class="text-xs text-gray-500">{{
                    item.description
                }}</span>
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
