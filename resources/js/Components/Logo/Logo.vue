<script setup>
import { computed } from "vue";
import Logo from "./coreLogo.vue";

const props = defineProps({
    size: {
        type: String,
        default: "md",
        validator: (value) => ["sm", "md", "lg", "xl"].includes(value),
    },
    variant: {
        type: String,
        default: "glass",
        validator: (value) => ["glass", "minimal", "frosted"].includes(value),
    },
    clickable: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["click"]);

const sizeClasses = computed(() => {
    const sizes = {
        sm: "gap-2 p-3 min-w-[100px] text-sm",
        md: "gap-3 p-4 sm:p-5 min-w-[140px]",
        lg: "gap-4 p-5 md:p-7 min-w-[180px] text-lg",
        xl: "gap-5 p-6 md:p-8 min-w-[220px] text-xl",
    };
    return sizes[props.size];
});

const variantClasses = computed(() => {
    const variants = {
        glass: "rounded-2xl bg-white/10 dark:bg-white/5 backdrop-blur-xl border border-white/20 dark:border-white/10 shadow-xl dark:shadow-2xl",
        minimal:
            "rounded-xl bg-white/5 dark:bg-white/3 backdrop-blur-lg border border-white/10 dark:border-white/5 shadow-lg",
        frosted:
            "rounded-3xl bg-white/15 dark:bg-white/8 backdrop-blur-2xl border border-white/30 dark:border-white/15 shadow-2xl",
    };
    return variants[props.variant];
});


const interactiveClasses = computed(() => {
    return props.clickable
        ? "cursor-pointer hover:bg-white/15 dark:hover:bg-white/10 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 ease-out"
        : "hover:bg-white/12 dark:hover:bg-white/8 hover:shadow-xl hover:scale-[1.02] transition-all duration-500 ease-out";
});

const handleClick = () => {
    if (props.clickable) {
        emit("click");
    }
};
</script>

<template>
    <div
        :class="[
            'flex items-center justify-center relative group',
            sizeClasses,
            variantClasses,
            interactiveClasses,
        ]"
        @click="handleClick"
        :role="clickable ? 'button' : undefined"
        :tabindex="clickable ? '0' : undefined"
        @keydown.enter="handleClick"
        @keydown.space.prevent="handleClick"
    >
        <!-- Efecto de brillo sutil -->
        <div
            class="absolute inset-0 rounded-2xl bg-gradient-to-r from-transparent via-white/10 dark:via-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"
        ></div>

        <div class="relative z-10 flex items-center gap-3">
            <Logo />
        </div>

        <!-- Indicador sutil para estados activos -->
        <div
            v-if="clickable"
            class="absolute top-2 right-2 w-1.5 h-1.5 bg-gray-400 dark:bg-gray-300 rounded-full opacity-50 group-hover:opacity-100 transition-opacity duration-300"
        ></div>
    </div>
</template>
