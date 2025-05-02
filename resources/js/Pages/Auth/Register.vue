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

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="font-[sans-serif] bg-gray-50 min-h-screen">
        <div class="flex justify-end pt-4 pe-4">
            <DarkModeBtn />
        </div>
        <div
            class="grid md:grid-cols-2 lg:grid-cols-2 items-center lg:gap-10 gap-4"
        >
            <div
                class="hidden max-md:order-1 h-screen min-h-full bg-green-200 md:flex items-center flex-col pt-60"
            >
                <div>
                    <Logo />
                </div>
                <ImagesLoginRegister />
            </div>
            <div class="md:hidden flex justify-center mb-4 pt-10">
                <Logo />
            </div>

            <form
                class="lg:col-span-1 max-w-lg w-full p-6 mx-auto"
                @submit.prevent="submit"
            >
                <div class="mb-10">
                    <h3 class="text-green-700 text-4xl font-extrabold">
                        Registro
                    </h3>
                    <div class="flex items-center justify-end mt-0">
                        <Link
                            :href="route('login')"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Ya registrado?
                        </Link>
                    </div>
                </div>

                <div>
                    <InputLabel for="name" value="Nombre" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        aria-placeholder="username"
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Contraseña" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel
                        for="password_confirmation"
                        value="Confirmar Contraseña"
                    />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                        @keydown.enter="submit"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <div
                    v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
                    class="mt-4"
                >
                    <InputLabel for="terms">
                        <div class="flex items-center">
                            <Checkbox
                                id="terms"
                                v-model:checked="form.terms"
                                name="terms"
                                required
                            />

                            <div class="ms-2">
                                I agree to the
                                <a
                                    target="_blank"
                                    :href="route('terms.show')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >Terms of Service</a
                                >
                                and
                                <a
                                    target="_blank"
                                    :href="route('policy.show')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >Privacy Policy</a
                                >
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.terms" />
                    </InputLabel>
                </div>

                <div class="mt-8">
                    <button
                        type="submit"
                        class="w-full py-2.5 px-5 text-sm tracking-wide rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none"
                        :disabled="form.processing"
                    >
                        Registrar
                    </button>
                </div>

                <div class="my-4 flex items-center gap-4">
                    <hr class="w-full border-gray-300" />
                    <p class="text-sm text-gray-800 text-center">o</p>
                    <hr class="w-full border-gray-300" />
                </div>

                <ReturnBtn />
            </form>
        </div>
    </div>
</template>

<style>
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
