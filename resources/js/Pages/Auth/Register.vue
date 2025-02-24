<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
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
    <DarkModeBtn />
    <div class="font-[sans-serif] bg-gray-50 min-h-screen">
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
                    <h3 class="text-gray-800 text-4xl font-extrabold">
                        Register
                    </h3>
                    <div class="flex items-center justify-end mt-0">
                        <Link
                            :href="route('login')"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Already registered?
                        </Link>
                    </div>
                </div>

                <div>
                    <InputLabel for="name" value="Name" />
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

                <div class="mt-4">
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm Password"
                    />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
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
                        class="w-full py-2.5 px-5 text-sm tracking-wide rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none"
                        :disabled="form.processing"
                    >
                        Register
                    </button>
                </div>

                <div class="my-4 flex items-center gap-4">
                    <hr class="w-full border-gray-300" />
                    <p class="text-sm text-gray-800 text-center">or</p>
                    <hr class="w-full border-gray-300" />
                </div>

                <button
                    type="button"
                    class="w-full flex items-center justify-center gap-4 py-2.5 px-5 text-sm tracking-wide text-gray-800 border border-gray-300 rounded-md bg-gray-50 hover:bg-gray-100 focus:outline-none"
                    disabled
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20px"
                        class="inline"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="#fbbd00"
                            d="M120 256c0-25.367 6.989-49.13 19.131-69.477v-86.308H52.823C18.568 144.703 0 198.922 0 256s18.568 111.297 52.823 155.785h86.308v-86.308C126.989 305.13 120 281.367 120 256z"
                            data-original="#fbbd00"
                        />
                        <path
                            fill="#0f9d58"
                            d="m256 392-60 60 60 60c57.079 0 111.297-18.568 155.785-52.823v-86.216h-86.216C305.044 385.147 281.181 392 256 392z"
                            data-original="#0f9d58"
                        />
                        <path
                            fill="#31aa52"
                            d="m139.131 325.477-86.308 86.308a260.085 260.085 0 0 0 22.158 25.235C123.333 485.371 187.62 512 256 512V392c-49.624 0-93.117-26.72-116.869-66.523z"
                            data-original="#31aa52"
                        />
                        <path
                            fill="#3c79e6"
                            d="M512 256a258.24 258.24 0 0 0-4.192-46.377l-2.251-12.299H256v120h121.452a135.385 135.385 0 0 1-51.884 55.638l86.216 86.216a260.085 260.085 0 0 0 25.235-22.158C485.371 388.667 512 324.38 512 256z"
                            data-original="#3c79e6"
                        />
                        <path
                            fill="#cf2d48"
                            d="m352.167 159.833 10.606 10.606 84.853-84.852-10.606-10.606C388.668 26.629 324.381 0 256 0l-60 60 60 60c36.326 0 70.479 14.146 96.167 39.833z"
                            data-original="#cf2d48"
                        />
                        <path
                            fill="#eb4132"
                            d="M256 120V0C187.62 0 123.333 26.629 74.98 74.98a259.849 259.849 0 0 0-22.158 25.235l86.308 86.308C162.883 146.72 206.376 120 256 120z"
                            data-original="#eb4132"
                        />
                    </svg>

                    Register with google
                </button>
                <ReturnBtn />
            </form>
        </div>
    </div>
</template>
