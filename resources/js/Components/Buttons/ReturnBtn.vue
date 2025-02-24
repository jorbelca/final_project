<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link
        class="min-w-[300px] flex justify-center group [transform:translateZ(0)] mt-10 px-6 py-3 rounded-md bg-transparent overflow-hidden relative before:absolute before:bg-sky-600 before:bottom-0 before:left-0 before:h-full before:w-full before:-translate-x-full hover:before:translate-x-0 before:transition before:ease-in-out before:duration-500 cursor-pointer"
        href="/"
    >
        <span
            class="relative z-0 text-black group-hover:text-gray-200 dark:text-white dark:group-hover:text-gray-200 transition ease-in-out duration-500 text-l"
        >
            ⬅ Return to Index
        </span>
    </Link>
</template>
