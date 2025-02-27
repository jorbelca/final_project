<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import { router, useForm } from "@inertiajs/vue3";
import { watch } from "vue";

const edit = window.location.pathname.includes("edit");

const props = defineProps({
    client: Object,
    exists: Boolean,
    email: String,
});

const formDataClient = useForm({
    name: props.client ? props.client.name : "",
    email: props.client ? props.client.email : "",
    company_name: props.client ? props.client.company_name : "",
    image_url: props.client ? props.client.image_url : null,
});

const submitForm = () => {
    try {
        if (edit) {
            formDataClient.post(`/clients/update/${props.client.id}`, {
                forceFormData: true,
            });
        } else {
            formDataClient.post("/clients", { forceFormData: true });
        }
    } catch (error) {
        console.error(error);
    }
};

const onFileChange = (e) => {
    const file = e.target.files[0];
    formDataClient.image_url = file;
};

const clientExists = () => {
    try {
        if (!edit) {
            router.post(`/clients/exists/`, { email: formDataClient.email });
        }
    } catch (error) {
        console.error(error);
    }
};

watch(() => {
    if (props.exists) {
        if (
            window.confirm(
                `The client with the email ${props.email} already exists. Do you want to vinculate with it?`
            )
        ) {
            router.post("/clients/vinculate", {
                email: formDataClient.email,
            });
        }
    }
});
</script>

<template>
    <main class="mb-10 container mx-auto">
        <form
            class="flex flex-col gap-4 p-7 form-wrapper shadow-xl rounded-xl w-full"
            @submit.prevent="submitForm"
            enctype="multipart/form-data"
        >
            <div class="flex flex-wrap gap-4 justify-center">
                <div>
                    <InputLabel>Email</InputLabel>
                    <TextInput
                        v-model="formDataClient.email"
                        type="email"
                        placeholder="Email of the client"
                        @blur="clientExists"
                    />
                </div>
                <div>
                    <InputLabel>Name</InputLabel>
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
                            <input
                                type="file"
                                @change="onFileChange"
                                class="text-text bg-transparent focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Client Logo"
                            />
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
