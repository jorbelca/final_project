<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/_Default/AuthenticationCard.vue";

import InputError from "@/Components/_Default/InputError.vue";
import InputLabel from "@/Components/_Default/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/_Default/TextInput.vue";
import Logo from "@/Components/Logo/coreLogo.vue";
import ReturnBtn from "@/Components/Buttons/ReturnBtn.vue";

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Forgot Password" />

    <AuthenticationCard>
        <template #logo>
            <div
                class="border-green-700 border-2 rounded-md p-5 bg-slate-200 max-w-[175px] mb-10 shadow-[0_2px_16px_-3px_rgba(6,81,237,0.3)]"
            >
                <Logo />
            </div>
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
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
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
        <div class="mt-2">
            <ReturnBtn />
        </div>
    </AuthenticationCard>
</template>
