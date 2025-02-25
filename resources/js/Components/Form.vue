<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "./Buttons/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";

const edit = window.location.pathname.includes("edit");

const props = defineProps({
    title: String,
    client: Object,
    cost: Object,
});

const formDataClients = useForm({
    name: props.client ? props.client.name : "",
    email: props.client ? props.client.email : "",
    company_name: props.client ? props.client.company_name : "",
    image_url: props.client ? props.client.image_url : "",
});

const formDataCosts = useForm({
    description: props.cost ? props.cost.description : "",
    cost: props.cost ? props.cost.cost : "",
    unit: props.cost ? props.cost.unit : "",
    periodicity: props.cost ? props.cost.periodicity : "",
});

const submitForm = async () => {
    try {
        if (edit) {
            if (props.title === "Client") {
                await formDataClients.put(`/clients/${props.client.id}`);
            } else {
                await formDataCosts.put(`/costs/${props.cost.id}`);
            }
        } else {
            if (props.title === "Client") {
                await formDataClients.post("/clients");
            } else {
                await formDataCosts.post("/costs");
            }
        }
    } catch (error) {
        console.error(error);
    }
};

const periodicity = ["unit", "monthly", "yearly", "daily", "weekly"];
</script>

<template>
    <AppLayout :title="edit ? 'Edit' : 'Create'">
        <template #header
            ><h2 class="font-semibold text-sm text-amber-500">
                <template v-if="props.title === 'Client'">
                    <a :href="route('clients.index')">
                        ◀ List of {{ props.title }}s</a
                    >
                </template>
                <template v-else>
                    <a :href="route('costs.index')"
                        >◀ List of {{ props.title }}s</a
                    >
                </template>
            </h2>
            <div class="flex align-center justify-center gap-5 items-end">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ edit ? "Edit" : "Create" }} a {{ props.title }}
                </h2>
            </div>
        </template>
        <main class="mb-10">
            <form class="flex flex-col gap-4 p-7" @submit.prevent="submitForm">
                <template v-if="props.title === 'Client'">
                    <div class="flex flex-wrap gap-5">
                        <div>
                            <InputLabel>Name</InputLabel>
                            <TextInput
                                v-model="formDataClients.name"
                                type="text"
                            />
                        </div>
                        <div>
                            <InputLabel>Email</InputLabel>
                            <TextInput
                                v-model="formDataClients.email"
                                type="email"
                            />
                        </div>
                        <div>
                            <InputLabel>Company Name</InputLabel>
                            <TextInput
                                v-model="formDataClients.company_name"
                                type="text"
                            />
                        </div>
                        <div>
                            <InputLabel>Image Url</InputLabel>
                            <TextInput
                                v-model="formDataClients.image_url"
                                type="url"
                            />
                        </div>
                    </div>
                </template>
                <template v-if="props.title === 'Cost'">
                    <div class="flex flex-wrap gap-4">
                        <div>
                            <InputLabel>Description</InputLabel>
                            <TextInput
                                v-model="formDataCosts.description"
                                type="text"
                            />
                        </div>
                        <div>
                            <InputLabel>Cost</InputLabel>
                            <TextInput
                                v-model="formDataCosts.cost"
                                type="number"
                                placeholder="$"
                            />
                        </div>
                        <div>
                            <InputLabel>Unit</InputLabel>
                            <TextInput
                                v-model="formDataCosts.unit"
                                type="text"
                                placeholder="piece,m3,kg,etc"
                            />
                        </div>
                        <div>
                            <InputLabel>Periodicity</InputLabel>
                            <select v-model="formDataCosts.periodicity">
                                <option
                                    v-for="item in periodicity"
                                    :key="item"
                                    :value="item"
                                >
                                    {{ item }}
                                </option>
                            </select>
                        </div>
                    </div>
                </template>
                <div>
                    <template v-if="edit">
                        <div class="flex justify-center pt-20">
                            <PrimaryButton
                                class="w-1/5 justify-center bg-yellow-500 hover:bg-yellow-600"
                                type="submit"
                                >Edit</PrimaryButton
                            >
                        </div>
                    </template>
                    <template v-else>
                        <div class="flex justify-center pt-20">
                            <PrimaryButton
                                class="w-1/5 justify-center bg-green-400 hover:bg-green-500"
                                type="submit"
                                >Create</PrimaryButton
                            >
                        </div>
                    </template>
                </div>
            </form>
        </main>
    </AppLayout>
</template>
