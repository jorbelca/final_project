<script setup>

defineProps({
    credits: {
        type: Number,
        default: 0,
    },
});
</script>
<template>
    <div
        class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-2 mb-3 shadow-md inline-flex items-center"
    >
        <div class="p-1 bg-white bg-opacity-30 rounded-full mr-2">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 text-white"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
        </div>
        <div>
            <p
                class="text-xs text-white font-medium border-b border-gray-300 dark:border-gray-600 pb-1"
            >
                Créditos:
                <span class="text-sm font-bold">{{ credits ?? 0 }}</span>
            </p>
        </div>
    </div>
    <!-- Mensaje de error si no hay créditos -->
    <small v-if="credits == 0" class="text-red-600">
        No tienes creditos dirigete a la pantalla de
        <a href="user/profile"><u>Perfil</u></a></small
    >
</template>
