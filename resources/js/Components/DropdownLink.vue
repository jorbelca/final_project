<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: String,
    as: String,
});
</script>

<template>
    <div>
        <button
            v-if="as == 'button'"
            type="submit"
            class="block w-full px-4 py-2 text-start text-sm leading-5 text-text hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out dark:bg-slate-800 dark:hover:bg-hover"
        >
            <slot />
        </button>

        <a
            v-else-if="as == 'a'"
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-text hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out  dark:bg-slate-800 dark:hover:bg-hover"
        >
            <slot />
        </a>

        <Link
            v-else
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-text hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out  dark:bg-slate-800 dark:hover:bg-hover"
        >
            <slot />
        </Link>
    </div>
</template>
