<script setup>
import { computed, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "../Buttons/PrimaryButton.vue";
import InputLabel from "../_Default/InputLabel.vue";
import TextInput from "../_Default/TextInput.vue";
import ProcessingMessage from "../UI/ProcessingMessage.vue";
import { stateOptions } from "./StateTile.vue";
import { formatMoney } from "./helpers";

const edit = window.location.pathname.includes("edit");
let selectedCost = "";
const quantity = ref(1);
let loading = ref(false);

const props = defineProps({
    IA: Boolean,
    clone: Boolean,
    costs: Array,
    clients: Array,
    budget: Object,
    taxes: Number,
});

const formData = useForm({
    client_id: props.budget ? props.budget.client_id : null,
    content: props.budget
        ? typeof props.budget.content === "string"
            ? JSON.parse(props.budget.content)
            : props.budget.content
        : [],
    taxes: props.budget ? props.budget.taxes : props.taxes,
    discount:
        props.budget && typeof props.budget.discount == "number"
            ? props.budget.discount
            : 0,
    user_id: props.budget ? props.budget.user_id : null,
    state: props.budget ? props.budget.state : "draft",
    notes: props.budget ? props.budget.notes : "",
});

const submitForm = async () => {
    loading.value = true;
    try {
        // Convert content values before sending
        formData.content = formData.content.map((item) => ({
            description: item.description,
            cost: parseFloat(item.cost), // Ensure it's a number
            quantity: parseInt(item.quantity, 10), // Convert to integer
        }));

        if (edit) {
            formData.put(`/budgets/${props.budget.id}`, {
                onFinish: () => {
                    loading.value = false;
                },
            });
        } else {
            formData.post("/budgets", {
                preserveScroll: true,
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

const addCost = () => {
    const cost = props.costs.find((cost) => cost.id === selectedCost);

    if (cost) {
        formData.content.push({
            description: cost.description,
            cost: parseFloat(cost.cost), // Convertir a número
            quantity: parseInt(quantity.value, 10), // Convertir a entero
        });
        selectedCost = "";
        quantity.value = 1;
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
const temporality = {
    monthly: "al mes",
    yearly: "anualmente",
    weekly: "semanalmente",
    daily: "diariamente",
    hourly: "por hora",
    unit: "por servicio / producto",
    biweekly: "cada dos semanas",
    minute: "por minuto",
};
const limitDescription = (description) => {
    if (description.length > 20) {
        return description.substring(0, 20) + "...";
    }
    return description;
};
</script>

<template>
    <main
        class="pb-3 xs:mb-10 container mx-auto max-w-7xl px-2 sm:px-6 lg:px-8"
    >
        <ProcessingMessage :loading="loading" />
        <form
            class="flex flex-col gap-4 p-2 form-wrapper shadow-xl rounded-xl w-full"
            @submit.prevent="submitForm"
        >
            <InputLabel>Cliente </InputLabel>
            <select
                class="text-text dark:bg-hover border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="client"
                id="client"
                v-model="formData.client_id"
            >
                <option value="" disabled>Selecciona el cliente</option>
                <option :value="null">--SIN CLIENTE--</option>
                <option
                    v-for="client in props.clients"
                    :key="client.id"
                    :value="client.id"
                >
                    {{ client.name }}
                </option>
            </select>

            <InputLabel>Contenido del Presupuesto</InputLabel>
            <template v-if="formData.content.length > 0">
                <div class="overflow-auto max-w-[200vw]">
                    <table class="text-text w-full">
                        <thead>
                            <tr class="flex align-center text-sm">
                                <!-- //Processing Message no se muestra, testing -->
                                <td
                                    class="flex justify-between flex-row w-full pr-[10vw]"
                                >
                                    <p>Cantidad</p>
                                    <p>Descripcion</p>
                                    <p>Coste</p>
                                </td>
                                <td class="pr-4">SubTotal</td>
                            </tr>
                        </thead>
                        <tr
                            v-for="(content, index) in formData.content"
                            :key="index"
                        >
                            <td class="flex justify-between items-center gap-5">
                                <div class="flex flex-row w-full">
                                    <TextInput
                                        class="w-[80px]"
                                        type="number"
                                        placeholder="quantity"
                                        v-model="content.quantity"
                                    />
                                    <TextInput
                                        class="w-full"
                                        type="text"
                                        placeholder="quantity"
                                        v-model="content.description"
                                    />
                                    <TextInput
                                        class="w-[70px] sm:w-[100px]"
                                        type="number"
                                        placeholder="quantity"
                                        v-model="content.cost"
                                    />
                                </div>
                                <div
                                    class="flex flex-row justify-start gap-1 sm:gap-4 text-nowrap w-1/6"
                                >
                                    <div>
                                        <b
                                            class="block text-ellipsis whitespace-nowrap min-w-[40px]"
                                        >
                                            {{
                                                content.quantity * content.cost
                                            }}€
                                        </b>
                                    </div>
                                    <button
                                        @click.prevent.stop="
                                            deleteContent(index)
                                        "
                                        class="text-red-600"
                                        type="button"
                                    >
                                        ❌
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </template>
            <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                <div class="flex flex-row gap-5">
                    <TextInput
                        class="w-1/6"
                        type="number"
                        placeholder="quantity"
                        v-model="quantity"
                        min="1"
                    />
                    <select
                        class="w-full text-text dark:bg-hover border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="costs"
                        id="costs"
                        v-model="selectedCost"
                    >
                        <option value="" disabled>Selecciona un coste</option>
                        <option
                            v-for="cost in props.costs"
                            :key="cost.id"
                            :value="cost.id"
                        >
                            {{ limitDescription(cost.description) }} -
                            {{ cost.cost }} €
                            {{ temporality[cost.periodicity] }}
                        </option>
                    </select>
                </div>
                <PrimaryButton @click.prevent="addCost" class="text-center"
                    >Añadir coste</PrimaryButton
                >
            </div>

            <div class="flex flex-wrap justify-between gap-5">
                <div class="flex flex-wrap gap-5">
                    <div>
                        <InputLabel>Impuestos</InputLabel>
                        <TextInput
                            v-model="formData.taxes"
                            type="number"
                            min="0"
                            max="99"
                        />
                    </div>
                    <div>
                        <InputLabel>Descuento</InputLabel>
                        <TextInput
                            v-model="formData.discount"
                            type="number"
                            min="0"
                            max="99"
                        />
                    </div>
                    <div>
                        <template v-if="edit">
                            <InputLabel for="state">Estado</InputLabel>
                            <select
                                class="w-full text-text dark:bg-hover border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                name="state"
                                id="state"
                                v-model="formData.state"
                            >
                                <option
                                    v-for="(state, index) in Object.keys(
                                        stateOptions
                                    )"
                                    :key="index"
                                    :value="state"
                                >
                                    {{ stateOptions[state] }}
                                </option>
                            </select>
                        </template>
                    </div>
                </div>
                <div
                    class="flex flex-row w-full sm:w-1/4 justify-center items-end"
                >
                    <p>
                        <b class="text-text text-lg font-extrabold"
                            >Total: {{ formatMoney(computedTotal) }} €</b
                        >
                    </p>
                </div>
            </div>

            <template v-if="edit">
                <div class="flex justify-center pt-20">
                    <PrimaryButton
                        color="yellow"
                        class="w-1/5 justify-center"
                        type="submit"
                        :disabled="formData.content.length === 0"
                        >Editar</PrimaryButton
                    >
                </div>
            </template>
            <template v-else>
                <div class="flex justify-center pt-20">
                    <PrimaryButton
                        :color="props.IA ? 'red' : clone ? 'blue' : 'green'"
                        class="w-1/5 justify-center"
                        type="submit"
                        :disabled="formData.content.length === 0"
                        >{{
                            props.IA ? "Guardar" : clone ? "Clonar" : "Crear"
                        }}</PrimaryButton
                    >
                </div>
            </template>
        </form>
        <div
            class="mt-6 p-3 sm:p-4 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-xl text-text"
        >
            <h2 class="text-lg font-semibold mb-2">Notas</h2>
            <textarea
                v-model="formData.notes"
                class="w-full min-h-[120px] sm:min-h-[150px] p-2 sm:p-3 border border-gray-300 rounded-md dark:bg-hover resize-none sm:resize-y"
                placeholder="Escribe las notas sobre el presupuesto aquí..."
            ></textarea>
        </div>
    </main>
</template>
