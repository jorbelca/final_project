<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "../../Buttons/PrimaryButton.vue";
import TextInput from "../../_Default/TextInput.vue";
import ProcessingMessage from "../../UI/ProcessingMessage.vue";
import { TrashIcon } from "@heroicons/vue/24/solid";
import parseCsv from "./parseCsv";
import parsePdfText from "./parsePdf";

let loading = ref(false);
let tableData = ref([]);
const headers = ref(["DESCRIPCION", "COSTE", "UNIDAD", "PERIODICIDAD"]);

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (
        file.type === "text/csv" ||
        file.type === "application/vnd.ms-excel" ||
        file.name.split(".").pop() === "csv"
    ) {
        tableData = parseCsv(file, tableData);
    } else if (
        file.type === "text/pdf" ||
        file.type === "application/vnd.ms-excel" ||
        file.name.split(".").pop() === "pdf"
    ) {
        const reader = new FileReader();
        reader.readAsArrayBuffer(file);
        reader.onload = async () => {
            const pdfData = new Uint8Array(reader.result);
            tableData = await parsePdfText(pdfData, tableData);
        };
    } else {
        alert("No se ha podido leer el archivo seleccionado");
        return;
    }
}

function submitForm() {
    if (!tableData.value || tableData.value.length === 0) {
        alert("No hay datos para enviar. ");
        return;
    }
    loading.value = true;

    router.visit("/costs/store_multiple", {
        method: "post",
        data: {
            costs: tableData.value,
        },
        preserveState: true,
        preserveScroll: true,

        onSuccess: () => {
            loading.value = false;
        },
        onError: (errors) => {
            loading.value = false;
            console.error("Errores de validación:", errors);
            alert(
                `Error al guardar los datos: ${Object.values(errors).join(
                    ", "
                )}`
            );
        },
        onFinish: () => {
            loading.value = false;
        },
    });
}

function removeCost(indexCost) {
    tableData.value = tableData.value.filter(
        (cost, index) => index !== indexCost
    );
}
</script>

<template>
    <ProcessingMessage :loading="loading" />
    <div class="px-6 max-w-4xl mx-auto rounded-lg text-text">
        <p class="mb-4">
            Sube un archivo CSV o PDF con al menos las siguientes columnas
        </p>
        <div class="mb-4">
            <p class="font-medium mb-2">Ejemplo de la estructura:</p>
            <table class="w-full mb-4 border-collapse">
                <thead>
                    <tr class="bg-hover">
                        <th
                            v-for="header in headers"
                            :key="header"
                            class="border p-2 text-center"
                        >
                            {{ header }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="dark:bg-gray-500 bg-white">
                        <td class="border p-2 text-center font-bold">
                            Requerido
                        </td>
                        <td class="border p-2 text-center font-semibold">
                            Requerido
                        </td>
                        <td class="border p-2 text-center">Opcional</td>
                        <td class="border p-2 text-center">Opcional</td>
                    </tr>
                    <tr class="dark:bg-gray-500 bg-white">
                        <td colspan="4" class="border p-2 text-center italic">
                            ...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="p-6 max-w-4xl mx-auto rounded-lg text-text">
        <input
            type="file"
            @change="handleFileUpload"
            accept=".csv,.pdf,application/vnd.ms-excel"
            class="mb-4 p-2 w-full"
        />

        <form @submit.prevent="submitForm" v-if="tableData.length">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-hover">
                        <th></th>
                        <th
                            v-for="header in headers"
                            :key="header"
                            class="border p-2 text-center"
                        >
                            {{ header }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, rowIndex) in tableData"
                        :key="rowIndex"
                        class="dark:bg-gray-500 bg-white"
                    >
                        <td class="border p-2">
                            <button
                                type="button"
                                @click.prevent="removeCost(rowIndex)"
                            >
                                <TrashIcon class="icon-delete" />
                            </button>
                        </td>
                        <td class="border p-2">
                            <TextInput
                                type="text"
                                v-model="row.description"
                                class="border rounded p-1 w-full"
                            />
                        </td>
                        <td class="border p-2">
                            <TextInput
                                type="text"
                                v-model="row.cost"
                                class="border rounded p-1 w-full"
                            />
                        </td>
                        <td class="border p-2">
                            <TextInput
                                type="text"
                                v-model="row.unit"
                                class="border rounded p-1 w-full"
                            />
                        </td>
                        <td class="border p-2">
                            <TextInput
                                type="text"
                                v-model="row.periodicity"
                                class="border rounded p-1 w-full"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
            <PrimaryButton
                type="submit"
                class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
            >
                Guardar
            </PrimaryButton>
        </form>
    </div>
</template>
