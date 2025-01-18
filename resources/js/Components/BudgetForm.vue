<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { computed } from "vue";
import PrimaryButton from "./PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    title: String,
    costs: Array,
    clients: Array,
});

const formData = useForm({
    client: "",
    costs: [],
    taxes: 0,
    discount: 0,
    total: 0,
    selectedCost: "",
    quantity: 1,
});

const submitForm = () => {
    console.log(formData);

    formData.post("/budgets");
};

const addCost = () => {
    const cost = props.costs.find((cost) => cost.id === formData.selectedCost);

    if (cost) {
        formData.costs.push({
            description: cost.description,
            cost: cost.cost,
            quantity: formData.quantity,
        });
        formData.selectedCost = "";
        formData.quantity = 1;
    }
};

const computedTotal = computed(() => {
    let subtotal = formData.costs.reduce((sum, cost) => {
        return sum + cost.quantity * cost.cost;
    }, 0);
    let totalWithTaxes = subtotal + (subtotal * formData.taxes) / 100;
    let totalWithDiscount =
        totalWithTaxes - (totalWithTaxes * formData.discount) / 100;
    return totalWithDiscount.toFixed(2);
});
</script>

<template>
    <AppLayout title="Create">
        <template #header>
            <div class="flex align-center gap-5">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create a {{ props.title }}
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
                <select name="client" id="client" v-model="formData.client">
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

                <InputLabel>Costs </InputLabel>
                <div v-for="(cost, index) in formData.costs" :key="index">
                    {{ cost.quantity }} x {{ cost.description }} -
                    {{ cost.cost }}
                </div>
                <div>
                    <PrimaryButton @click.prevent="addCost"
                        >Add cost</PrimaryButton
                    >
                    <TextInput
                        type="number"
                        placeholder="quantity"
                        v-model="formData.quantity"
                    />
                    <select
                        name="costs"
                        id="costs"
                        v-model="formData.selectedCost"
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

                <div class="flex">
                    <InputLabel>Tax</InputLabel>
                    <TextInput v-model="formData.taxes" type="number" />
                    <InputLabel>Discount</InputLabel>
                    <TextInput v-model="formData.discount" type="number" />
                    <p>Total: {{ computedTotal }}</p>
                </div>
                <div>
                    <PrimaryButton class="w-1/5" type="submit"
                        >Crear</PrimaryButton
                    >
                </div>
            </form>
        </main>
    </AppLayout>
</template>
