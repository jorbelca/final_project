<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";

const edit = window.location.pathname.includes("edit");

const props = defineProps({
    client: Object,
});

const formDataClient = useForm({
    name: props.client ? props.client.name : "",
    email: props.client ? props.client.email : "",
    company_name: props.client ? props.client.company_name : "",
    image_url: props.client ? props.client.image_url : "",
});

const submitForm = () => {
    try {
        if (edit) {
            formDataClient.put(`/clients/${props.client.id}`);
        } else {
            formDataClient.post("/clients");
        }
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
    <main class="mb-10">
        <form class="flex flex-col gap-4 p-7" @submit.prevent="submitForm">
            <div class="flex flex-wrap gap-4 justify-center">
                <div>
                    <InputLabel>Client Email</InputLabel>
                    <TextInput
                        v-model="formDataClient.email"
                        type="text"
                        placeholder="Email of the client"
                    />
                </div>
                <div>
                    <InputLabel>Client Name</InputLabel>
                    <TextInput
                        v-model="formDataClient.name"
                        type="text"
                        placeholder="Name of the client"
                    />
                </div>

                <div>
                    <InputLabel>Company</InputLabel>
                    <TextInput
                        v-model="formDataClient.company_name"
                        type="text"
                        placeholder="Company name"
                    />
                </div>
                <div>
                    <div class="flex flex-row items-center gap-2 justify-start">
                        <img
                            v-if="edit && props.client?.image_url"
                            :src="props.client?.image_url"
                            alt="Client Image"
                            class="w-14 h-14"
                        />
                        <div>
                            <InputLabel>Logo</InputLabel>
                            <TextInput
                                v-model="formDataClient.image_url"
                                class="text-text dark:bg-hover border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Url of the logo"
                            >
                            </TextInput>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center pt-10">
                <PrimaryButton
                    class="w-md justify-center"
                    :class="
                        edit
                            ? 'bg-yellow-500 hover:bg-yellow-600'
                            : 'bg-green-400 hover:bg-green-500'
                    "
                    type="submit"
                >
                    {{ edit ? "Edit" : "Create" }}
                </PrimaryButton>
            </div>
        </form>
    </main>
</template>
