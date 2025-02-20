<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Logo from "@/Components/Logo.vue";
import ReturnBtn from "@/Components/Btns/ReturnBtn.vue";
import DarkModeBtn from "@/Components/Btns/DarkModeBtn.vue";
defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Log in" />
    <DarkModeBtn></DarkModeBtn>

    <div
        class="font-[sans-serif] bg-white flex flex-col items-center pt-[10rem] md:h-screen p-4 dark:bg-green-950"
    >
        <div class="pb-10">
            <Logo />
        </div>

        <div
            class="shadow-[0_2px_16px_-3px_rgba(6,81,237,0.3)] max-w-6xl max-md:max-w-lg rounded-md p-6 dark:bg-green-700 dark:bg-opacity-25"
        >
            <div class="grid md:grid-cols-2 items-center gap-8">
                <div class="max-md:order-1">
                    <div class="relative mt-10 flex justify-center p-10 mr-10">
                        <img
                            src="/capturas/app.png"
                            alt="Example App"
                            class="rounded-lg shadow-2xl border border-gray-300 dark:border-gray-700 max-w-[250px] max-h-[250px]"
                        />
                        <!-- Imagen superpuesta (Budget) -->
                        <img
                            src="/capturas/budget.png"
                            alt="Example Budget"
                            class="absolute top-10 -right-10 rounded-lg shadow-xl border border-gray-300 dark:border-gray-700 max-w-[300px] max-h-[300px]"
                        />
                    </div>
                </div>

                <form
                    class="md:max-w-md w-full mx-auto"
                    @submit.prevent="submit"
                >
                    <div class="relative flex items-center">
                        <div class="w-full">
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>
                    </div>

                    <div class="relative flex items-center">
                        <div class="mt-4 w-full">
                            <InputLabel for="password" value="Password" />
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="mt-1 block w-full"
                                required
                                autocomplete="current-password"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>
                    </div>

                    <div
                        class="flex flex-wrap items-center justify-between gap-4 mt-6"
                    >
                        <div class="flex items-center">
                            <div class="block mt-4">
                                <label class="flex items-center">
                                    <Checkbox
                                        v-model:checked="form.remember"
                                        name="remember"
                                    />
                                    <span class="ms-2 text-sm text-gray-600"
                                        >Remember me</span
                                    >
                                </label>
                            </div>
                        </div>
                        <div>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Forgot your password?
                            </Link>
                        </div>
                    </div>

                    <div class="mt-12">
                        <PrimaryButton
                            class="ms-4"
                            :class="{
                                'flex justify-around w-full shadow-xl py-2.5 px-4 text-sm font-semibold tracking-wide rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none': true,
                                'opacity-25': form.processing,
                            }"
                            :disabled="form.processing"
                            >Log in
                        </PrimaryButton>
                        <p class="text-gray-800 text-sm text-center mt-6">
                            Don't have an account
                            <a
                                href="javascript:void(0);"
                                class="text-blue-600 font-semibold hover:underline ml-1 whitespace-nowrap"
                                >Register here</a
                            >
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <ReturnBtn />
    </div>
</template>
