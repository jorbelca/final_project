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

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};

const csrf_token = () => {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    return metaTag ? metaTag.getAttribute("content") : "";
};
</script>

<template>
    <Head title="Registro - Budget App" />

    <div
        class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 relative"
    >
        <!-- Dark Mode Button -->
        <div class="absolute top-6 right-6 z-20">
            <DarkModeBtn />
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
                            Crea tu cuenta
                        </h1>
                        <p
                            class="text-gray-600 dark:text-gray-400 text-sm lg:text-base"
                        >
                            Únete y comienza a gestionar tus finanzas de manera
                            inteligente
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

                        <!-- Name Field -->
                        <div class="space-y-2">
                            <InputLabel
                                for="name"
                                value="Nombre completo"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            />
                            <div class="relative">
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="Tu nombre completo"
                                    required
                                    autofocus
                                    autocomplete="name"
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
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                            <InputError
                                :message="form.errors.name"
                                class="text-sm"
                            />
                        </div>

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
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
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
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="Mínimo 8 caracteres"
                                    required
                                    autocomplete="new-password"
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

                        <!-- Password Confirmation Field -->
                        <div class="space-y-2">
                            <InputLabel
                                for="password_confirmation"
                                value="Confirmar contraseña"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            />
                            <div class="relative">
                                <TextInput
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    :type="
                                        showPasswordConfirmation
                                            ? 'text'
                                            : 'password'
                                    "
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="Repite tu contraseña"
                                    required
                                    autocomplete="new-password"
                                    @keydown.enter="submit"
                                />
                                <button
                                    type="button"
                                    @click="
                                        showPasswordConfirmation =
                                            !showPasswordConfirmation
                                    "
                                    class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
                                >
                                    <svg
                                        v-if="!showPasswordConfirmation"
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
                                :message="form.errors.password_confirmation"
                                class="text-sm"
                            />
                        </div>

                        <!-- Terms and Conditions -->
                        <div
                            v-if="
                                $page.props.jetstream
                                    .hasTermsAndPrivacyPolicyFeature
                            "
                            class="space-y-2"
                        >
                            <label
                                class="flex items-start space-x-3 cursor-pointer"
                            >
                                <Checkbox
                                    id="terms"
                                    v-model:checked="form.terms"
                                    name="terms"
                                    required
                                    class="mt-1 rounded border-gray-300 text-green-600 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                                />
                                <div
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                >
                                    Acepto los
                                    <a
                                        target="_blank"
                                        :href="route('terms.show')"
                                        class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 font-medium transition-colors duration-200"
                                    >
                                        Términos de Servicio
                                    </a>
                                    y la
                                    <a
                                        target="_blank"
                                        :href="route('policy.show')"
                                        class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 font-medium transition-colors duration-200"
                                    >
                                        Política de Privacidad
                                    </a>
                                </div>
                            </label>
                            <InputError
                                :message="form.errors.terms"
                                class="text-sm"
                            />
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            <span v-if="!form.processing">Crear Cuenta</span>
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
                                <span>Creando cuenta...</span>
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

                        <!-- Login Link -->
                        <div class="text-center">
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400"
                                >¿Ya tienes una cuenta?
                            </span>
                            <Link
                                :href="route('login')"
                                class="text-sm text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 font-medium transition-colors duration-200"
                            >
                                Inicia sesión aquí
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
                class="hidden lg:flex bg-gradient-to-br from-red-200 to-blue-600 dark:from-green-800 dark:to-blue-800 relative overflow-hidden"
            >
                <div
                    class="flex flex-col justify-center items-center w-full p-12 relative z-10"
                >
                    <div class="mb-8">
                        <Logo />
                    </div>
                    <div class="text-center text-white space-y-6">
                        <h2 class="text-3xl font-bold">
                            Comienza tu viaje financiero
                        </h2>
                        <p class="text-lg opacity-90 max-w-md">
                            Únete a miles de usuarios que ya gestionan sus
                            presupuestos de manera inteligente y eficiente.
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
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
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

/* Dark mode */
.dark .bg-gray-50 {
    @apply bg-gray-900;
}

.dark h3.text-green-700 {
    @apply text-green-400;
}

.dark .text-gray-600,
.dark .text-gray-800,
.dark .text-gray-900 {
    @apply text-gray-300;
}

.dark .bg-green-200 {
    @apply bg-green-800;
}

.dark hr.border-gray-300 {
    @apply border-gray-700;
}

.dark .hover\:text-gray-900:hover {
    @apply hover:text-gray-100;
}
</style>
