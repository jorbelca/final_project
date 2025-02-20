<script setup>
import DarkModeBtn from "@/Components/Btns/DarkModeBtn.vue";
import Logo from "@/Components/Logo.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <Head title="Welcome" />

    <div class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
        <div class="relative min-h-screen flex flex-col items-center pt-10">
            <DarkModeBtn />
            <div class="relative w-full max-w-2xl px-6 lg:max-w-6xl">
                <!-- Header -->
                <header class="flex justify-between items-center py-6">
                    <div
                        class="flex items-center justify-center gap-4 p-6 rounded-xl border-2 border-gray-300 dark:border-gray-700 shadow-lg bg-gradient-to-r from-white to-gray-100 dark:from-gray-800 dark:to-gray-900"
                    >
                        <Logo />
                    </div>
                    <nav v-if="canLogin" class="flex gap-4 w-1/4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="text-lg font-medium text-gray-700 dark:text-gray-300 hover:text-blue-500 transition"
                        >
                            App
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-lg font-medium text-gray-700 dark:text-gray-300 hover:text-blue-500 transition"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="text-lg font-medium text-gray-700 dark:text-gray-300 hover:text-blue-500 transition"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <!-- Main Content -->
                <main class="relative mt-10 mx-auto w-[500px]">
                    <h2
                        class="text-3xl font-semibold text-gray-800 dark:text-gray-200 text-center"
                    >
                        Simplify your budgets
                    </h2>
                    <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                        Create, manage, and send professional budgets with ease.
                        With the power of the AI
                    </p>

                    <div class="relative mt-10 flex justify-center">
                        <img
                            src="/capturas/app.png"
                            alt="Example App"
                            class="rounded-lg shadow-2xl border border-gray-300 dark:border-gray-700 max-w-[500px] max-h-[400px]"
                        />
                        <!-- Imagen superpuesta (Budget) -->
                        <img
                            src="/capturas/budget.png"
                            alt="Example Budget"
                            class="absolute top-10 -right-10 rounded-lg shadow-xl border border-gray-300 dark:border-gray-700 max-w-[500px] max-h-[400px]"
                        />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
