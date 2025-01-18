<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "./PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    title: String,
});

const formData = useForm({
    name: "",
    email: "",
    company_name: "",
    image_url: "",
});

const submitForm = () => {
    formData.post("/clients");
};
</script>

<template>
    <AppLayout title="Create">
        <template #header>
            <div class="flex align-center gap-5">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create a {{ props.title }}
                </h2>
                <h2 class="font-semibold text-l text-green-500">
                    <a :href="route('clients.index')"
                        >List of {{ props.title }}s</a
                    >
                </h2>
            </div>
        </template>
        <main>
            <form class="flex flex-col gap-4 p-7" @submit.prevent="submitForm">
                <div class="flex flex-wrap gap-4">
                    <div>
                        <InputLabel>Name</InputLabel>
                        <TextInput v-model="formData.name" type="text" />
                    </div>
                    <div>
                        <InputLabel>Email</InputLabel>
                        <TextInput v-model="formData.email" type="email" />
                    </div>
                    <div>
                        <InputLabel>Company Name</InputLabel>
                        <TextInput
                            v-model="formData.company_name"
                            type="text"
                        />
                    </div>
                    <div>
                        <InputLabel>Image Url</InputLabel>
                        <TextInput v-model="formData.image_url" type="url" />
                    </div>
                </div>
                <div>
                    <PrimaryButton class="w-1/5" type="submit"
                        >Crear
                    </PrimaryButton>
                </div>
            </form>
        </main>
    </AppLayout>
</template>
