<script>
export const periodicity = {
    unit: "por servicio / producto",
    minute: "por minuto",
    hourly: "por hora",
    daily: "al día",
    monthly: "al mes",
    yearly: "al año",
    daily: "diarios",
    weekly: "a la semana",
    biweekly: "cada dos semanas",
};
</script>

<script setup>
import InputLabel from "@/Components/_Default/InputLabel.vue";
import TextInput from "@/Components/_Default/TextInput.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import ProcessingMessage from "../UI/ProcessingMessage.vue";

let loading = ref(false);
const edit = window.location.pathname.includes("edit");

const props = defineProps({
    cost: Object,
});

const formDataCosts = useForm({
    description: props.cost ? props.cost.description : "",
    cost: props.cost ? props.cost.cost : "",
    unit: props.cost ? props.cost.unit : "",
    periodicity: props.cost ? props.cost.periodicity : "",
});

const submitForm = () => {
    loading.value = true;
    try {
        if (edit) {
            formDataCosts.put(`/costs/${props.cost.id}`, {
                onFinish: () => {
                    loading.value = false;
                },
            });
        } else {
            formDataCosts.post("/costs", {
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
</script>

<template>
    <main class="pb-10 container mx-auto">
        <ProcessingMessage :loading="loading" />
        <form
            class="flex flex-col gap-4 p-5 form-wrapper shadow-xl rounded-xl w-full"
            @submit.prevent="submitForm"
        >
            <div class="flex flex-wrap gap-4 justify-center">
                <div>
                    <InputLabel>Descripcion</InputLabel>
                    <TextInput
                        v-model="formDataCosts.description"
                        type="text"
                    />
                </div>
                <div>
                    <InputLabel>Coste</InputLabel>
                    <TextInput
                        v-model="formDataCosts.cost"
                        type="number"
                        placeholder="€"
                    />
                </div>
                <div>
                    <InputLabel>Unidad</InputLabel>
                    <TextInput
                        v-model="formDataCosts.unit"
                        type="text"
                        placeholder="pieza, m3, kg, etc"
                    />
                </div>
                <div>
                    <InputLabel>Periodicidad</InputLabel>
                    <select
                        v-model="formDataCosts.periodicity"
                        class="text-text dark:bg-hover border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    >
                        ">
                        <option
                            v-for="(item, index) in Object.keys(periodicity)"
                            :key="index"
                            :value="item"
                        >
                            {{ periodicity[item] }}
                        </option>
                    </select>
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
                    {{ edit ? "Editar" : "Crear" }}
                </PrimaryButton>
            </div>
        </form>
    </main>
</template>
