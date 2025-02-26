<script setup>
import { ref } from "vue";
import Papa from "papaparse";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "./Buttons/PrimaryButton.vue";
import TextInput from "./TextInput.vue";

const tableData = ref([]);
const headers = ref(["DESCRIPTION", "COST", "UNIT", "PERIODICITY"]);

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    Papa.parse(file, {
        complete: (result) => {
            if (result.data.length <= 1) return;

            // Detectar índice de las columnas que nos interesan
            let columns = result.data[0].map((h) => h.trim().toUpperCase());
            let idxDesc = columns.indexOf("DESCRIPTION");
            let idxCost = columns.indexOf("COST");
            let idxUnit = columns.indexOf("UNIT");
            let idxPeriod = columns.indexOf("PERIODICITY");

            // Filtrar y transformar los datos
            tableData.value = result.data.slice(1).map((row) => {
                let description = row[idxDesc] || "";
                let cost = row[idxCost] || "";
                let unit =
                    idxUnit !== -1
                        ? row[idxUnit].toLowerCase() || "unit"
                        : "unit";
                let periodicity =
                    idxPeriod !== -1
                        ? row[idxPeriod].toLowerCase() || "unit"
                        : "unit";

                return { description, cost, unit, periodicity };
            });

            // Filtrar filas vacías
            tableData.value = tableData.value.filter(
                (row) => row.description && row.cost
            );
        },
        skipEmptyLines: true,
    });
}

function submitForm() {
    try {
        router.post("/costs/store_multiple", { costs: tableData.value });
    } catch (error) {
        console.error(error);
        alert("Error al guardar los datos");
    }
}
</script>

<template>
    <div class="p-6 max-w-4xl mx-auto rounded-lg text-text">
        <input
            type="file"
            @change="handleFileUpload"
            accept=".csv"
            class="mb-4 p-2 w-full"
        />

        <form @submit.prevent="submitForm" v-if="tableData.length">
            <table class="w-full border-collapse">
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
                    <tr
                        v-for="(row, rowIndex) in tableData"
                        :key="rowIndex"
                        class="dark:bg-gray-500 bg-white"
                    >
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
                Save
            </PrimaryButton>
        </form>
    </div>
</template>
