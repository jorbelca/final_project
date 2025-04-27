<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link
        class="min-w-[300px] flex items-center justify-center gap-2 mt-10 px-6 py-3 rounded-lg
               relative overflow-hidden transition-all duration-300 ease-out
               border border-gray-300 dark:border-gray-700
               bg-white dark:bg-gray-800
               hover:bg-purple-600 dark:hover:bg-purple-700
               shadow-sm hover:shadow-md
               group"
        href="/"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300 group-hover:text-white transition-colors duration-300" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
        </svg>
        <span
            class="text-gray-800 dark:text-gray-200 group-hover:text-white font-medium transition-colors duration-300"
        >
            Home
        </span>
    </Link>
</template>
