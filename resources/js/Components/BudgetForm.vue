<script setup>
import { computed, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "./PrimaryButton.vue";
import InputLabel from "./InputLabel.vue";
import TextInput from "./TextInput.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const edit = window.location.pathname.includes("edit");

const props = defineProps({
    title: String,
    costs: Array,
    clients: Array,
    budget: Object,
});

let selectedCost = "";
let quantity = 1;

const formData = useForm({
    client_id: props.budget ? props.budget.client_id : null,
    content: props.budget ? JSON.parse(props.budget.content) : [],
    taxes: props.budget ? props.budget.taxes : 0,
    discount: props.budget ? props.budget.discount : 0,
    user_id: props.budget ? props.budget.user_id : null,
});

const submitForm = () => {
    // Convertir los valores de content antes de enviarlos
    const formattedContent = formData.content.map((item) => ({
        description: item.description,
        cost: parseFloat(item.cost), // Asegurar que sea un número
        quantity: parseInt(item.quantity, 10), // Convertir a entero
    }));

    // Crear una copia de formData con los datos convertidos
    let formattedData = {
        ...formData,
        content: formattedContent,
    };

    try {
        if (edit) {
            formattedData.put(`/budgets/${props.budget.id}`, {});
            return;
        }
        formattedData.post("/budgets", {
            preserveScroll: true,
        });
    } catch (error) {
        console.log(error);
    }
};

const addCost = () => {
    const cost = props.costs.find((cost) => cost.id === selectedCost);

    if (cost) {
        formData.content.push({
            description: cost.description,
            cost: parseFloat(cost.cost), // Convertir a número
            quantity: parseInt(quantity, 10), // Convertir a entero
        });
        selectedCost = "";
        quantity = 1;
    }
};

const computedTotal = computed(() => {
    if (!formData.content.length) {
        return 0;
    }
    let subtotal = formData.content.reduce((sum, cost) => {
        return sum + cost.quantity * cost.cost;
    }, 0);
    let totalWithTaxes = subtotal + (subtotal * formData.taxes) / 100;
    let totalWithDiscount =
        totalWithTaxes - (totalWithTaxes * formData.discount) / 100;
    return totalWithDiscount.toFixed(2);
});

const deleteContent = (index) => {
    formData.content.splice(index, 1);
};
</script>

<template>
    <AppLayout title="Create">
        <template #header>
            <div class="flex align-center gap-5">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <template v-if="edit"> Edit </template>
                    <template v-else> Create </template>
                    a {{ props.title }}
                </h2>
                <h2 class="font-semibold text-l text-green-500">
                    <a :href="route('budgets.index')"
                        >List of {{ props.title }}s</a
                    >
                </h2>
            </div>
        </template>
        <main>
            <form class="flex flex-col gap-4 p-7" @submit.prevent="submitForm">
                <InputLabel>Client </InputLabel>
                <select
                    class="rounded-lg"
                    name="client"
                    id="client"
                    v-model="formData.client_id"
                >
                    <option value="">Select a client</option>
                    <option value="null">NONE</option>
                    <option
                        v-for="client in props.clients"
                        :key="client.id"
                        :value="client.id"
                    >
                        {{ client.name }}
                    </option>
                </select>

                <InputLabel>Content of the Budget</InputLabel>
                <template v-if="formData.content.length > 0">
                    <div
                        v-for="(content, index) in formData.content"
                        :key="index"
                    >
                        <div class="flex justify-between">
                            <div>
                                {{ content.quantity }} x
                                {{ content.description }} - {{ content.cost }} $
                            </div>
                            <div class="flex flex-row gap-6">
                                <div>
                                    <b>
                                        {{ content.quantity * content.cost }}
                                        $</b
                                    >
                                </div>
                                <button
                                    v-on:click="deleteContent(index)"
                                    class="text-red-600/100 font-bold"
                                >
                                    X
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <div class="flex flex-row flex-wrap gap-3">
                    <PrimaryButton @click.prevent="addCost"
                        >Add cost</PrimaryButton
                    >
                    <TextInput
                        class="w-1/6"
                        type="number"
                        placeholder="quantity"
                        v-model="quantity"
                    />
                    <select
                        class="rounded-lg"
                        name="costs"
                        id="costs"
                        v-model="selectedCost"
                    >
                        <option value="" disabled>Select a cost</option>
                        <option
                            v-for="cost in props.costs"
                            :key="cost.id"
                            :value="cost.id"
                        >
                            {{ cost.description }} - {{ cost.cost }}
                        </option>
                    </select>
                </div>

                <div class="flex flex-wrap justify-between gap-5">
                    <div class="flex flex-wrap gap-10">
                        <div>
                            <InputLabel>Tax</InputLabel>
                            <TextInput
                                v-model="formData.taxes"
                                type="number"
                                min="0"
                                max="99"
                            />
                        </div>
                        <div>
                            <InputLabel>Discount</InputLabel>
                            <TextInput
                                v-model="formData.discount"
                                type="number"
                                min="0"
                                max="99"
                            />
                        </div>
                    </div>
                    <div class="flex flex-row self-end">
                        <p>
                            <b class="text-lg font-extrabold"
                                >Total: {{ computedTotal }}</b
                            >
                        </p>
                    </div>
                </div>
                <template v-if="edit">
                    <div class="flex justify-center pt-20">
                        <PrimaryButton
                            class="w-1/5 justify-center bg-yellow-500 hover:bg-yellow-600"
                            type="submit"
                            >Editar</PrimaryButton
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
            </form>
        </main>
    </AppLayout>
</template>
