<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import Checkbox from "@/Components/_Default/Checkbox.vue";
import InputError from "@/Components/_Default/InputError.vue";
import InputLabel from "@/Components/_Default/InputLabel.vue";
import TextInput from "@/Components/_Default/TextInput.vue";
import Logo from "@/Components/Logo/Logo.vue";
import ReturnBtn from "@/Components/Buttons/ReturnBtn.vue";
import DarkModeBtn from "@/Components/Buttons/DarkModeBtn.vue";
import ImagesLoginRegister from "./ImagesLoginRegister.vue";
import { ref } from "vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
const csrf_token = () => {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    return metaTag ? metaTag.getAttribute("content") : "";
};
</script>

<template>
    <Head title="Iniciar Sesión - Budget App" />

    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 relative"
    >
        <!-- Dark Mode Button -->
        <div class="absolute top-6 right-6 z-20">
            <DarkModeBtn />
        </div>

        <!-- Status Message -->
        <div
            v-if="status"
            class="absolute top-20 left-1/2 transform -translate-x-1/2 z-10"
        >
            <div
                class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg shadow-md"
            >
                {{ status }}
            </div>
        </div>

        <div class="grid lg:grid-cols-2 min-h-screen">
            <!-- Left Side - Form -->
            <div class="flex items-center justify-center p-6 lg:p-12">
                <div class="w-full max-w-md space-y-8">
                    <!-- Logo Mobile -->
                    <div class="lg:hidden flex justify-center">
                        <Logo />
                    </div>

                    <!-- Header -->
                    <div class="text-center lg:text-left">
                        <h1
                            class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2"
                        >
                            Bienvenido de vuelta
                        </h1>
                        <p
                            class="text-gray-600 dark:text-gray-400 text-sm lg:text-base"
                        >
                            Gestiona tus finanzas de manera inteligente
                        </p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- CSRF token -->
                        <input
                            type="hidden"
                            name="_token"
                            :value="csrf_token()"
                        />
                        <!-- Email Field -->
                        <div class="space-y-2">
                            <InputLabel
                                for="email"
                                value="Correo electrónico"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            />
                            <div class="relative">
                                <TextInput
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="tu@email.com"
                                    required
                                    autocomplete="username"
                                />
                                <div class="absolute right-3 top-3">
                                    <svg
                                        class="w-5 h-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                            <InputError
                                :message="form.errors.email"
                                class="text-sm"
                            />
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <InputLabel
                                for="password"
                                value="Contraseña"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            />
                            <div class="relative">
                                <TextInput
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="Tu contraseña"
                                    required
                                    autocomplete="current-password"
                                    @keydown.enter="submit"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
                                >
                                    <svg
                                        v-if="!showPassword"
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        ></path>
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        ></path>
                                    </svg>
                                    <svg
                                        v-else
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                            <InputError
                                :message="form.errors.password"
                                class="text-sm"
                            />
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label
                                class="flex items-center space-x-2 cursor-pointer"
                            >
                                <Checkbox
                                    v-model:checked="form.remember"
                                    name="remember"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                                />
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                    >Recordarme</span
                                >
                            </label>

                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors duration-200"
                            >
                                ¿Olvidaste tu contraseña?
                            </Link>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            <span v-if="!form.processing">Iniciar Sesión</span>
                            <div
                                v-else
                                class="flex items-center justify-center space-x-2"
                            >
                                <svg
                                    class="animate-spin w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                    ></path>
                                </svg>
                                <span>Iniciando...</span>
                            </div>
                        </button>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div
                                    class="w-full border-t border-gray-300 dark:border-gray-600"
                                ></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span
                                    class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400"
                                    >o</span
                                >
                            </div>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center">
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                                >¿No tienes una cuenta?
                            </span>
                            <Link
                                :href="route('register')"
                                class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors duration-200"
                            >
                                Regístrate aquí
                            </Link>
                        </div>

                        <!-- Return Button -->
                        <div class="pt-4 flex justify-center">
                            <ReturnBtn />
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side - Branding -->
            <div
                class="hidden lg:flex bg-gradient-to-br from-slate-200 to-yellow-600 dark:from-blue-800 dark:to-green-800 relative overflow-hidden"
            >
                <div
                    class="flex flex-col justify-center items-center w-full p-12 relative z-10"
                >
                    <div class="mb-8">
                        <Logo />
                    </div>
                    <div class="text-center text-white space-y-6">
                        <h2 class="text-3xl font-bold">
                            Simplifica tus presupuestos
                        </h2>
                        <p class="text-lg opacity-90 max-w-md">
                            La herramienta más intuitiva para gestionar tus
                            presupuestos y alcanzar tus metas financieras.
                        </p>
                    </div>
                    <ImagesLoginRegister />
                </div>

                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg
                        class="w-full h-full"
                        viewBox="0 0 100 100"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <defs>
                            <pattern
                                id="grid"
                                width="10"
                                height="10"
                                patternUnits="userSpaceOnUse"
                            >
                                <path
                                    d="M 10 0 L 0 0 0 10"
                                    fill="none"
                                    stroke="white"
                                    stroke-width="0.5"
                                />
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid)" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Smooth transitions for all interactive elements */
* {
    transition: all 0.2s ease-in-out;
}

/* Focus states for better accessibility */
input:focus,
button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Loading animation */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
