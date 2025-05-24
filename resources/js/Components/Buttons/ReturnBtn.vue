<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link
        class="inline-flex items-center gap-3 px-6 py-3 rounded-xl
               relative overflow-hidden transition-all duration-300 ease-out
               border-2 border-transparent
               bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700
               hover:from-indigo-500 hover:to-purple-600 dark:hover:from-indigo-600 dark:hover:to-purple-700
               shadow-lg hover:shadow-xl hover:-translate-y-0.5
               backdrop-blur-sm
               group"
        href="/"
    >
        <!-- Animated background overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-600/10
                    opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        <!-- Home icon with animation -->
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-gray-600 dark:text-gray-300 group-hover:text-white
                   transition-all duration-300 group-hover:scale-110 relative z-10"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>

        <!-- Text with subtle animation -->
        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200
                     group-hover:text-white transition-all duration-300
                     relative z-10">
            Volver al inicio
        </span>

        <!-- Arrow indicator -->
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4 text-gray-500 dark:text-gray-400 group-hover:text-white
                   transition-all duration-300 group-hover:translate-x-1 relative z-10"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
        </svg>
    </Link>
</template>
