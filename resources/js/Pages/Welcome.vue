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
});
</script>

<template>
    <Head title="Welcome" />

    <div class="bg-gray-100 text-gray-800 dark:bg-green-950 dark:text-gray-300">
        <div class="relative min-h-screen flex flex-col items-center ">
            <div class="relative w-full">
                <!-- Header -->
                <header
                    class="fixed top-0 left-0 w-full
                    flex justify-between items-center py-2 px-10 bg-white/10 dark:bg-gray-900/80 backdrop-blur-md z-50 shadow-md"
                >
                    <Logo />
                    <nav v-if="canLogin" class="flex gap-4 items-center">
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
                            <DarkModeBtn />
                        </template>
                    </nav>
                </header>

                <!-- Main Content -->
                <main
                    class="relative w-full h-screen overflow-y-scroll snap-y snap-mandatory scroll-smooth"
                >
                    <!-- Sección 1 -->
                    <section
                        class="h-screen flex flex-col justify-center items-center snap-start bg-gradient-to-r from-gray-100 to-gray-300 dark:from-gray-800 dark:to-gray-900"
                    >
                        <h2
                            class="text-4xl font-bold text-gray-800 dark:text-gray-200"
                        >
                            Simplify your budgets
                        </h2>
                        <p
                            class="mt-2 text-lg text-gray-600 dark:text-gray-400 text-center"
                        >
                            Create, manage, and send professional budgets with
                            ease. With the power of AI.
                        </p>
                        <div class="relative mt-10 flex justify-center">
                            <img
                                src="/capturas/app.png"
                                alt="Example App"
                                class="rounded-lg shadow-2xl border border-gray-300 dark:border-gray-700 max-w-[500px] max-h-[400px]"
                            />
                            <img
                                src="/capturas/budget.png"
                                alt="Example Budget"
                                class="absolute top-10 -right-10 rounded-lg shadow-xl border border-gray-300 dark:border-gray-700 max-w-[500px] max-h-[400px]"
                            />
                        </div>
                    </section>

                    <!-- Sección 2 -->
                    <section
                        class="h-screen flex justify-center items-center snap-start bg-green-500 text-white"
                    >
                        <h1 class="text-5xl font-bold">With AI</h1>
                    </section>

                    <!-- Sección 3 -->
                    <section
                        class="h-screen flex justify-center items-center snap-start bg-blue-500 text-white"
                    >
                        <h1 class="text-5xl font-bold">Manage your budgets</h1>
                    </section>

                    <!-- Sección 4 -->
                    <section
                        class="h-screen flex justify-center items-center snap-start bg-purple-500 text-white"
                    >
                        <h1 class="text-5xl font-bold">Manage your clients</h1>
                    </section>
                </main>
            </div>
        </div>
    </div>
</template>
