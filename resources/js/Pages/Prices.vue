<script setup>
import plansData from "../plans.json";
import { ref } from "vue";

const plans = ref(plansData);
</script>

<template>
    <div class="grid sm:gap-2 gap-6 sm:grid-cols-3">
        <div
            v-for="(plan, index) in plans"
            :key="plan.id"
            :class="[
                'border rounded-xl p-3 shadow-md hover:shadow-xl transition duration-300 ease-in-out flex flex-col items-center mx-10 sm:m-0',
                'dark:bg-gray-800 dark:border-gray-700',
                index === 2 && plans.length === 3
                    ? 'border-indigo-400 dark:border-indigo-500 scale-105 bg-indigo-50 dark:bg-gray-700 shadow-lg'
                    : 'border-gray-200 bg-white',
            ]"
        >
            <h2 class="text-2xl font-bold text-gray-800 mb-3 dark:text-white">
                {{ plan.name }}
            </h2>
            <p
                class="text-3xl font-extrabold text-gray-900 mb-5 dark:text-gray-100"
            >
                {{ plan.price }}
            </p>
            <ul class="space-y-2 mb-6 flex-grow">
                <li
                    v-for="(value, key) in plan.features"
                    :key="key"
                    class="flex items-center text-gray-600 dark:text-gray-300"
                >
                    <svg
                        v-if="
                            value === true ||
                            (typeof value === 'number' && value > 0) ||
                            (typeof value === 'string' &&
                                !['0', '1', '2'].includes(key))
                        "
                        class="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        ></path>
                    </svg>
                    <svg
                        v-else-if="
                            value === false ||
                            (typeof value === 'number' && value === 0)
                        "
                        class="w-5 h-5 text-red-500 mr-2 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                    <svg
                        v-else
                        class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        ></path>
                    </svg>
                    <span v-if="typeof value === 'string'">{{ value }}</span>
                    <span v-else-if="typeof value === 'number'"
                        >{{ key }}: {{ value }}</span
                    >
                    <span v-else>{{ key }}</span>
                </li>
            </ul>
            <a
                :class="[
                    'w-full font-semibold px-6 py-3 rounded-lg transition duration-150 ease-in-out mt-auto',
                    index === 1 && plans.length === 3
                        ? 'bg-indigo-700 hover:bg-indigo-800 text-white focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-50 dark:bg-indigo-600 dark:hover:bg-indigo-700'
                        : 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 dark:bg-indigo-700 dark:hover:bg-indigo-800',
                ]"
                href="user/profile"
            >
                Elige
            </a>
        </div>
    </div>
</template>
<style scoped>
@media (max-width: 640px) {
    .grid > div {
        @apply p-4 mx-4 scale-100;
    }

    .grid > div h2 {
        @apply text-lg;
    }

    .grid > div p {
        @apply text-xl mb-3;
    }

    .grid > div ul {
        @apply space-y-1 mb-4;
    }

    .grid > div a {
        @apply px-4 py-2 text-sm;
    }
}
</style>
