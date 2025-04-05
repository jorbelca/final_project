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
    <DarkModeBtn />
    <div class="font-[sans-serif] bg-gray-50 min-h-screen">
        <div
            class="grid md:grid-cols-2 lg:grid-cols-3 items-center lg:gap-10 gap-4"
        >
            <div class="md:hidden flex justify-center mb-4 pt-10">
                <Logo />
            </div>
            <form
                class="lg:col-span-2 max-w-lg w-full p-6 mx-auto"
                @submit.prevent="submit"
            >
                <div class="mb-10">
                    <h3 class="text-primary text-4xl font-extrabold">Log In</h3>
                    <div class="flex items-center justify-end mt-0">
                        <Link
                            :href="route('register')"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            You don't have an account ?
                        </Link>
                    </div>
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
                    <InputLabel for="password" value="Password" />
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
                <div class="mt-8">
                    <button
                        type="submit"
                        class="w-full py-2.5 px-5 text-sm tracking-wide rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none"
                        :disabled="form.processing"
                    >
                        Enter
                    </button>
                </div>

                <div class="my-4 flex items-center gap-4">
                    <hr class="w-full border-gray-300" />
                    <p class="text-sm text-gray-800 text-center">or</p>
                    <hr class="w-full border-gray-300" />
                </div>

             
                <ReturnBtn />
            </form>
            <div
                class="hidden max-md:order-1 h-screen min-h-full bg-green-200 md:flex items-center flex-col pt-60"
            >
                <div>
                    <Logo />
                </div>
                <ImagesLoginRegister />
            </div>
        </div>
    </div>
</template>
