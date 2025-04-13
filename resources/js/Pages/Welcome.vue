<script setup>
import { Head, Link } from "@inertiajs/vue3"; 
import DarkModeBtn from "@/Components/Buttons/DarkModeBtn.vue";
import Logo from "@/Components/Logo/Logo.vue";

import ImagesLoginRegister from "./Auth/ImagesLoginRegister.vue";

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
        <div class="relative min-h-screen flex flex-col items-center">
            <div class="relative w-full">
                <!-- Header -->
                <header
                    class="fixed top-0 left-0 sm:w-[calc(100%-15px)] w-full flex justify-between items-center py-1 pr-10 pl-4 bg-white/10 dark:bg-gray-900/80 backdrop-blur-md z-50 shadow-md"
                >
                    <Logo />
                    <nav v-if="canLogin" class="flex gap-4 items-center">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('budgets.index')"
                            class="text-lg font-medium text-gray-700 dark:text-gray-300 hover:text-blue-500 transition"
                        >
                            App
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-sm sm:text-lg font-medium text-gray-700 dark:text-gray-300 hover:text-blue-500 transition"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="text-sm sm:text-lg font-medium text-gray-700 dark:text-gray-300 hover:text-blue-500 transition"
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
                        class="min-h-screen flex flex-col justify-center items-center snap-start bg-gradient-to-br from-white to-gray-200 dark:from-gray-900 dark:to-green-950 relative overflow-hidden py-16 px-4 sm:px-6"
                    >
                        <div
                            class="absolute inset-0 opacity-10 bg-pattern"
                        ></div>
                        <div
                            class="z-10 w-full max-w-3xl px-4 sm:px-6 py-8 sm:py-12 text-center"
                        >
                            <h2
                                class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 dark:text-white mb-4 sm:mb-6 tracking-tight"
                            >
                                Simplifica tus presupuestos
                            </h2>
                            <p
                                class="text-lg sm:text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-6 sm:mb-10 leading-relaxed"
                            >
                                Con nuestra aplicación, podrás gestionar tus
                                presupuestos de manera fácil y rápida.
                                <a
                                    class="block mt-2 font-medium text-blue-600 dark:text-blue-400"
                                    href="/register"
                                    >¡Empieza hoy mismo!</a
                                >
                            </p>

                            <ImagesLoginRegister />
                        </div>
                    </section>

                    <!-- Sección 2 -->
                    <section
                        class="min-h-screen flex flex-col justify-center items-center snap-start px-4 sm:px-8 py-16 bg-gradient-to-br from-green-400 to-green-600 dark:from-green-800 dark:to-green-950 text-white relative overflow-hidden"
                    >
                        <div class="max-w-4xl mx-auto text-center">
                            <h2
                                class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6 sm:mb-8"
                            >
                                Inteligencia Artificial a tu servicio
                            </h2>

                            <div class="relative mb-6 sm:mb-8">
                                <img
                                    src="/imgs/ai.gif"
                                    alt="Inteligencia Artificial"
                                    class="w-40 sm:w-48 md:w-56 mx-auto rounded-lg shadow-xl dark:opacity-90"
                                />
                            </div>

                            <p
                                class="text-lg sm:text-xl md:text-2xl leading-relaxed max-w-2xl mx-auto"
                            >
                                Nuestra
                                <span class="font-bold"
                                    >integracion con IA</span
                                >
                                te permite crear presupuestos de manera rápida y
                                eficiente. Solo necesitas ingresar los datos
                                necesarios y nuestra IA se encargará del resto.
                                <br />
                                <span class="font-bold"
                                    >¡Olvídate de los cálculos tediosos!</span
                                >
                            </p>
                        </div>
                    </section>

                    <!-- Sección 3: Gestión de Costes -->
                    <section
                        class="min-h-screen flex flex-col justify-center items-center snap-start bg-gradient-to-br from-blue-400 to-blue-600 dark:from-blue-800 dark:to-blue-950 text-white relative overflow-hidden px-4 sm:px-8 py-16"
                    >
                        <div class="max-w-4xl mx-auto text-center">
                            <h2
                                class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6 sm:mb-8"
                            >
                                Gestiona tus costes con precisión
                            </h2>

                            <div class="relative mb-6 sm:mb-10">
                                <!-- <img
                                    src="/imgs/costs.svg"
                                    alt="Gestión de Costes"
                                    class="w-40 sm:w-48 md:w-56 mx-auto rounded-lg shadow-xl dark:opacity-90"
                                    onerror="this.src='/imgs/default-costs.png'"
                                /> -->
                            </div>

                            <p
                                class="text-lg sm:text-xl md:text-2xl leading-relaxed max-w-3xl mx-auto"
                            >
                                Obtén
                                <span class="font-semibold">control total</span>
                                sobre tus gastos y optimiza tu rentabilidad.
                                Nuestras herramientas te permiten visualizar,
                                analizar y reducir costes innecesarios en cada
                                proyecto.
                            </p>
                        </div>
                    </section>

                    <!-- Sección 4: Gestión de Clientes -->
                    <section
                        class="min-h-screen flex flex-col justify-center items-center snap-start bg-gradient-to-br from-purple-400 to-purple-600 dark:from-purple-800 dark:to-purple-950 text-white relative overflow-hidden px-4 sm:px-8 py-16"
                    >
                        <div class="max-w-4xl mx-auto text-center">
                            <h2
                                class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6 sm:mb-8"
                            >
                                Centraliza tu cartera de clientes
                            </h2>

                            <div class="relative mb-6 sm:mb-10">
                                <!-- <img
                                    src="/imgs/clients.svg"
                                    alt="Gestión de Clientes"
                                    class="w-40 sm:w-48 md:w-56 mx-auto rounded-lg shadow-xl dark:opacity-90"
                                    onerror="this.src='/imgs/default-clients.png'"
                                /> -->
                            </div>

                            <p
                                class="text-lg sm:text-xl md:text-2xl leading-relaxed max-w-3xl mx-auto"
                            >
                                Gestiona tu cartera de clientes de forma
                                <span class="font-semibold">eficiente</span>.
                                Accede al historial de presupuestos y fortalece
                                tus relaciones comerciales desde un único lugar.
                            </p>
                        </div>
                    </section>

                    <!-- Sección 5: Planes -->
                    <section
                        class="min-h-screen flex flex-col justify-center items-center snap-start bg-gradient-to-br from-red-400 to-red-600 dark:from-red-800 dark:to-red-950 text-white relative overflow-hidden px-4 sm:px-8 py-16"
                    >
                        <div class="max-w-4xl mx-auto text-center">
                            <h2
                                class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6 sm:mb-8"
                            >
                                Planes adaptados a tus necesidades
                            </h2>

                            <div class="relative mb-6 sm:mb-10">
                                <!-- <img
                                    src="/imgs/plans.svg"
                                    alt="Planes Disponibles"
                                    class="w-40 sm:w-48 md:w-56 mx-auto rounded-lg shadow-xl dark:opacity-90"
                                    onerror="this.src='/imgs/default-plans.png'"
                                /> -->
                            </div>

                            <p
                                class="text-lg sm:text-xl md:text-2xl leading-relaxed max-w-3xl mx-auto"
                            >
                                Enfocados en
                                <span class="font-semibold"
                                    >profesionales independientes</span
                                >, tenemos el plan perfecto para ti. Escala tus
                                herramientas a medida que crece tu negocio.
                            </p>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
</template>
