<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DarkModeBtn from "@/Components/Buttons/DarkModeBtn.vue";
import Logo from "@/Components/Logo/Logo.vue";
import Prices from "./Prices.vue";
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
    <Head title="BudgetApp - Gestor de Presupuestos" />

    <div class="bg-gray-100 text-gray-800 dark:bg-green-950 dark:text-gray-300">
        <div class="relative min-h-screen flex flex-col items-center">
            <div class="relative w-full">
                <!-- Header -->
                <header
                    class="fixed top-0 left-0 sm:w-[calc(100%-15px)] w-full flex justify-between items-center py-1 sm:py-2 md:py-3 px-3 sm:px-5 md:px-8 bg-white/10 dark:bg-gray-900/80 backdrop-blur-md z-50 shadow-md"
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
                                Registro
                            </Link> </template
                        ><DarkModeBtn />
                    </nav>
                </header>

                <!-- Main Content -->
                <main
                    class="relative w-full h-screen overflow-y-scroll snap-y snap-mandatory scroll-smooth"
                >
                    <!-- Sección 1 -->
                    <section
                        class="min-h-screen flex flex-col justify-center items-center snap-start bg-gradient-to-br from-white to-gray-200 dark:from-gray-900 dark:to-green-950 relative overflow-hidden py-12 px-4 sm:px-6"
                    >
                        <div
                            class="absolute inset-0 opacity-10 bg-pattern"
                        ></div>
                        <div
                            class="z-10 w-full max-w-6xl px-4 sm:px-6 py-6 sm:py-10 text-center"
                        >
                            <h1
                                class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 dark:text-white mb-4 sm:mb-5 tracking-tight"
                            >
                                Gestión de presupuestos profesionales con IA
                                integrada
                            </h1>
                            <p
                                class="text-base sm:text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-4 sm:mb-8 leading-relaxed"
                            >
                                Ahorra hasta un 40% de tiempo en la creación de
                                presupuestos. Nuestra plataforma integra IA,
                                análisis de costes y gestión de clientes en una
                                solución todo-en-uno para profesionales
                                independientes y pequeñas empresas.
                                <a
                                    class="block mt-2 font-medium text-blue-600 dark:text-blue-400"
                                    href="/register"
                                    >¡Empieza hoy mismo!</a
                                >
                            </p>

                            <ImagesLoginRegister :landing="true" />
                        </div>
                    </section>

                    <!-- Sección 2: IA -->
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

                    <!-- Sección 3: Estadísticas -->
                    <section
                        class="min-h-screen flex flex-col justify-center items-center snap-start px-4 sm:px-8 py-16 bg-gradient-to-br from-amber-400 to-amber-600 dark:from-amber-800 dark:to-amber-950 text-white relative overflow-hidden"
                    >
                        <div class="max-w-4xl mx-auto text-center">
                            <h2
                                class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6 sm:mb-8"
                            >
                                Visualiza tus datos con claridad
                            </h2>

                            <div
                                class="hover:-translate-y-2 transition-all duration-200 z-10 w-[90%] sm:w-[80%] md:w-[70%] mx-auto"
                            >
                                <img
                                    src="/capturas/stats.webp"
                                    alt="Estadísticas"
                                    class="w-full h-auto rounded-lg shadow-xl border-2 border-white/30"
                                    onerror="this.src='/capturas/backup/stats.png'"
                                />
                            </div>

                            <p
                                class="text-lg sm:text-xl md:text-2xl leading-relaxed max-w-2xl mx-auto"
                            >
                                Nuestros
                                <span class="font-bold"
                                    >paneles estadísticos</span
                                >
                                te muestran gráficos detallados de tus ingresos,
                                gastos y rentabilidad por proyecto. Toma
                                decisiones basadas en datos reales.
                                <br />
                                <span class="font-bold">
                                    Conoce el rendimiento de tus presupuestos de
                                    un vistazo
                                </span>
                            </p>

                            <div class="mt-8 flex justify-center gap-4">
                                <div
                                    class="bg-white/20 rounded-lg p-3 backdrop-blur-sm hover:scale-105 transition-transform"
                                >
                                    <svg
                                        class="w-12 h-12"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
                                        />
                                    </svg>
                                </div>
                                <div
                                    class="bg-white/20 rounded-lg p-3 backdrop-blur-sm hover:scale-105 transition-transform"
                                >
                                    <svg
                                        class="w-12 h-12"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"
                                        />
                                    </svg>
                                </div>
                                <div
                                    class="bg-white/20 rounded-lg p-3 backdrop-blur-sm hover:scale-105 transition-transform"
                                >
                                    <svg
                                        class="w-12 h-12"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Sección 4: Gestión de Costes -->
                    <section
                        class="min-h-screen flex flex-col justify-center items-center snap-start bg-gradient-to-br from-blue-400 to-blue-600 dark:from-blue-800 dark:to-blue-950 text-white relative overflow-hidden px-4 sm:px-8 py-8 sm:py-16"
                    >
                        <div class="max-w-4xl mx-auto text-center mt-4">
                            <h2
                                class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6 md:mb-2 md:mt-8"
                            >
                                Gestiona tus costes con precisión
                            </h2>

                            <p
                                class="text-base sm:text-lg md:text-xl lg:text-1xl leading-relaxed max-w-3xl mx-auto mb-6 sm:mb-10"
                            >
                                Obtén
                                <span class="font-semibold">control total</span>
                                sobre tus gastos y optimiza tu rentabilidad.
                                <span class="font-semibold"
                                    >Sube tus archivos PDF y CSV</span
                                >
                                para importar datos de forma rápida y eficiente.
                            </p>

                            <div class="relative mt-0 mb-0 px-4">
                                <!-- Screenshot background -->
                                <div
                                    class="relative w-full max-w-xl mx-auto h-48 sm:h-64 md:h-80 lg:h-96"
                                >
                                    <!-- First card/image -->
                                    <div
                                        class="absolute top-0 left-1/3 transform -translate-x-1/2 hover:-translate-y-2 transition-all duration-300 z-10 w-[65%] sm:w-[65%] md:w-[60%]"
                                    >
                                        <img
                                            src="/capturas/costes.webp"
                                            alt="Gestión de costes screenshot"
                                            class="w-full h-auto rounded-lg shadow-xl border-2 border-white/30"
                                            onerror="this.src='/capturas/backup/costes.png'"
                                        />
                                    </div>

                                    <!-- Second card/image -->
                                    <div
                                        class="absolute top-20 sm:top-24 md:top-28 left-2/3 transform -translate-x-1/2 hover:-translate-y-2 transition-all duration-300 z-0 w-[60%] sm:w-[60%] md:w-[55%]"
                                    >
                                        <img
                                            src="/capturas/costes-archivo.webp"
                                            alt="Gestión de costes screenshot"
                                            class="w-full h-auto rounded-lg shadow-xl border-2 border-white/30"
                                            onerror="this.src='/capturas/backup/costes-archivo.png'"
                                        />
                                    </div>
                                </div>

                                <!-- Icons container -->
                                <div
                                    class="flex justify-center items-center gap-4 sm:gap-6 md:gap-8 relative z-20"
                                >
                                    <div
                                        class="transition-transform hover:scale-110 duration-300"
                                    >
                                        <img
                                            src="/imgs/pdf.svg"
                                            alt="PDF Upload"
                                            class="w-12 sm:w-16 md:w-20 lg:w-24 rounded-lg shadow-lg dark:opacity-90"
                                        />
                                    </div>

                                    <div
                                        class="transition-transform hover:scale-110 duration-300 mt-10 sm:mt-16 md:mt-20"
                                    >
                                        <img
                                            src="/imgs/coins.svg"
                                            alt="Coins"
                                            class="w-16 sm:w-20 md:w-28 lg:w-32 rounded-lg shadow-lg dark:opacity-95"
                                        />
                                    </div>

                                    <div
                                        class="transition-transform hover:scale-110 duration-300"
                                    >
                                        <img
                                            src="/imgs/csv.svg"
                                            alt="CSV Upload"
                                            class="w-12 sm:w-16 md:w-20 lg:w-24 rounded-lg shadow-lg dark:opacity-90"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Sección 5: Gestión de Clientes -->
                    <section
                        class="min-h-screen flex flex-col justify-center items-center snap-start bg-gradient-to-br from-purple-400 to-purple-600 dark:from-purple-800 dark:to-purple-950 text-white relative overflow-hidden px-4 sm:px-8 py-16"
                    >
                        <div class="max-w-4xl mx-auto text-center">
                            <h2
                                class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6"
                            >
                                Centraliza tu cartera de clientes
                            </h2>
                            <p
                                class="text-lg sm:text-xl md:text-2xl leading-relaxed max-w-3xl mx-auto mb-4 sm:mb-6"
                            >
                                Gestiona tu cartera de clientes de forma
                                <span class="font-semibold">eficiente</span>.
                                Accede al historial de presupuestos y fortalece
                                tus relaciones comerciales desde un único lugar.
                            </p>
                        </div>
                        <!-- Screenshot background -->
                        <div
                            class="relative w-full max-w-3xl mx-auto h-64 sm:h-80 md:h-96"
                        >
                            <!-- Main screenshot - positioned prominently -->
                            <div
                                class="absolute top-0 inset-0 hover:-translate-y-2 transition-all duration-300 z-10 w-full"
                            >
                                <img
                                    src="/capturas/clients.webp"
                                    alt="Gestión de clientes screenshot"
                                    class="w-full h-auto rounded-lg shadow-xl border-2 border-white/30"
                                    onerror="this.src='/capturas/backup/clients.png'"
                                />
                            </div>

                            <!-- Client icon positioned on top of screenshot -->
                            <div
                                class="absolute -bottom-10 sm:-bottom-12 md:-bottom-16 lg:-bottom-20 left-1/2 transform -translate-x-1/2 z-20 transition-transform hover:scale-110 duration-300"
                            >
                                <img
                                    src="/imgs/clients.svg"
                                    alt="Logo de Clientes"
                                    class="w-32 sm:w-36 md:w-40 rounded-lg shadow-lg dark:opacity-95 mt-10"
                                />
                            </div>
                        </div>
                    </section>

                    <!-- Sección 6: Planes -->
                    <section
                        class="min-h-screen flex flex-col justify-center items-center snap-start bg-gradient-to-br from-red-400 to-red-600 dark:from-red-800 dark:to-red-950 text-white relative overflow-hidden px-4 sm:px-8 py-16"
                    >
                        <div class="max-w-4xl mx-auto text-center">
                            <h2
                                class="text-xl sm:text-2xl md:text-3xl font-bold mb-6 sm:mb-8"
                            >
                                Con Planes adaptados a tus necesidades
                            </h2>

                            <p
                                class="text-lg sm:text-sm md:text-normal leading-relaxed sm:leading-snug max-w-3xl mx-auto mb-6"
                            >
                                Enfocados en
                                <span class="font-semibold"
                                    >profesionales independientes</span
                                >, tenemos el plan perfecto para ti. Escala tus
                                herramientas a medida que crece tu negocio.
                            </p>
                            <div class="relative mb-20 sm:mb-0">
                                <Prices />
                            </div>
                        </div>

                        <!-- Footer -->
                        <footer
                            class="w-full bg-gray-900 dark:bg-gray-950 text-gray-400 py-4 px-4 sm:px-8 absolute bottom-0 left-0"
                        >
                            <div
                                class="container mx-auto flex flex-col md:flex-row justify-center items-center text-sm"
                            >
                                <p class="text-center mb-2 md:mb-0">
                                    <!-- Información del desarrollador -->
                                    Desarrollado por
                                    <a
                                        href="https://github.com/jorbelca"
                                        class="hover:text-gray-300 transition duration-300"
                                    >
                                        @jorbelca
                                    </a>

                                    © 2025 <br />Proyecto académico sin fines
                                    comerciales.
                                </p>
                            </div>

                            <!-- Separador -->
                            <hr class="w-full border-gray-700 my-2" />

                            <!-- Enlaces legales -->
                            <div
                                class="flex flex-wrap justify-center gap-x-4 sm:gap-x-6 gap-y-1 mb-2 md:mb-0"
                            >
                                <a
                                    href="/terms"
                                    class="hover:text-white transition duration-300 text-xs sm:text-sm"
                                    >Política de Cookies</a
                                >
                                <a
                                    href="/terms"
                                    class="hover:text-white transition duration-300 text-xs sm:text-sm"
                                    >Tratamiento de Datos</a
                                >
                                <a
                                    href="/terms"
                                    class="hover:text-white transition duration-300 text-xs sm:text-sm"
                                    >Aviso Legal</a
                                >
                            </div>
                        </footer>
                    </section>
                </main>
            </div>
        </div>
    </div>
</template>
