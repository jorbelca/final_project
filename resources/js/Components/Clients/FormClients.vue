<script setup>
import InputLabel from "@/Components/_Default/InputLabel.vue";
import TextInput from "@/Components/_Default/TextInput.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import ProcessingMessage from "../UI/ProcessingMessage.vue";
import { router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const loading = ref(false);
const edit = window.location.pathname.includes("edit");

const props = defineProps({
    client: Object,
});

const formDataClient = useForm({
    name: props.client ? props.client.name : "",
    email: props.client ? props.client.email : "",
    company_name: props.client ? props.client.company_name : "",
    image_url: props.client ? props.client.image_url : null,
});

const submitForm = () => {
    loading.value = true;
    try {
        if (edit) {
            formDataClient.post(`/clients/update/${props.client.id}`, {
                forceFormData: true,
                onFinish: () => {
                    loading.value = false;
                },
            });
        } else {
            formDataClient.post("/clients", {
                forceFormData: true,
                onFinish: () => {
                    loading.value = false;
                },
            });
        }
    } catch (error) {
        console.error(error);
        loading.value = false;
    }
};
const onFileChange = (e) => {
    const file = e.target.files[0];
    formDataClient.image_url = file;
};

const clientExists = () => {
    if (!edit) {
        fetch("/sanctum/csrf-cookie", {
            method: "GET",
            credentials: "include",
        }).then(() => {
            fetch("/api/clients/exists", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-XSRF-TOKEN": getCookie("XSRF-TOKEN"),
                },
                credentials: "include",
                body: JSON.stringify({ email: formDataClient.email }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.exists) vinculateClient();
                })
                .catch((error) => console.error("Error:", error));
        });
    }
};
const getCookie = (name) => {
    let matches = document.cookie.match(
        new RegExp(
            "(?:^|; )" +
                name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") +
                "=([^;]*)"
        )
    );
    return matches ? decodeURIComponent(matches[1]) : undefined;
};
const vinculateClient = () => {
    if (
        window.confirm(
            `El cliente con el correo electrónico ${formDataClient.email} ya existe. ¿Desea vincularse con él?`
        )
    ) {
        loading.value = true;
        router.post(
            "/clients/vinculate",
            {
                email: formDataClient.email,
            },
            {
                onFinish: () => {
                    loading.value = false;
                },
            }
        );
    }
};
</script>

<template>
    <main class="mb-10 container mx-auto">
        <ProcessingMessage :loading="loading" />
        <form
            class="flex flex-col gap-4 p-7 form-wrapper shadow-xl rounded-xl w-full"
            @submit.prevent="submitForm"
            enctype="multipart/form-data"
        >
            <div class="flex flex-wrap gap-4 justify-center items-center">
                <div>
                    <InputLabel>Email</InputLabel>
                    <TextInput
                        v-model="formDataClient.email"
                        type="email"
                        placeholder="Email del cliente"
                        @blur="clientExists"
                    />
                </div>
                <div>
                    <InputLabel>Nombre</InputLabel>
                    <TextInput
                        v-model="formDataClient.name"
                        type="text"
                        placeholder="Nombre del cliente"
                    />
                </div>

                <div>
                    <InputLabel>Empresa</InputLabel>
                    <TextInput
                        v-model="formDataClient.company_name"
                        type="text"
                        placeholder="Nombre de la empresa"
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
                                :disabled="loading"
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
                    :disabled="loading"
                >
                    {{ edit ? "Editar" : "Crear" }}
                </PrimaryButton>
            </div>
        </form>
    </main>
</template>
