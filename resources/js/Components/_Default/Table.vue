<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import StateTile from "./Budgets/StateTile.vue";
// Computed para las columnas
let props = defineProps({
    data: {
        type: Array,
        required: true,
    },
});

const currentPath = window.location.pathname;
const excludedFields = [
    "updated_at",
    "pivot",
    "email_verified_at",
    "user_id",
    "client_id",
];

// Propiedad computada para obtener las columnas (excluyendo las no deseadas)
const columns = computed(() =>
    props.data.length > 0
        ? Object.keys(props.data[0]).filter(
              (column) => !excludedFields.includes(column)
          )
        : []
);
const filteredData = computed(() => {
    return props.data.map((row) => {
        // Devuelve una copia del objeto sin los campos excluidos
        const filteredRow = { ...row };
        excludedFields.forEach((field) => {
            delete filteredRow[field];
        });
        return filteredRow;
    });
});
// Métodos para el componente
function serialNumber(key) {
    return key + 1;
}

function formatColumnHead(value) {
    return value.split("_").join(" ").toUpperCase();
}

const deleteRow = (id) => {
    // Determinar el recurso basándonos en el nombre de la ruta
    let resource;
    if (currentPath.includes("clients")) {
        resource = "clients";
    } else if (currentPath.includes("budgets")) {
        resource = "budgets";
    } else if (currentPath.includes("costs")) {
        resource = "costs";
    } else {
        alert("Cannot determine resource to delete.");
        return;
    }

    // Confirmar y enviar la solicitud
    const confirmation = confirm(
        `Are you sure you want to delete this ${resource}?`
    );
    if (confirmation) {
        router.delete(`${resource}/${id}`, {
            onError: (errors) => {
                console.error(`Error deleting ${resource}:`, errors);
            },
        });
    } else {
        alert(`Deletion of ${resource} canceled.`);
    }
};

const editRow = (id) => {
    // Determinar el recurso basándonos en el nombre de la ruta
    let resource;
    if (currentPath.includes("clients")) {
        resource = "clients";
    } else if (currentPath.includes("budgets")) {
        resource = "budgets";
    } else if (currentPath.includes("costs")) {
        resource = "costs";
    } else {
        console.error("Cannot determine resource to edit.");
        return;
    }

    router.get(`${resource}/${id}/edit`, {
        onError: (errors) => {
            alert("Error loading edit form.");
            console.error(`Error:`, errors);
        },
    });
};
const generate = (id) => {
    try {
        window.open(`/budget/${id}/generate`, "_blank");
    } catch (error) {
        alert("Error generating budget.");
        console.error("Error generating budget:", error);
    }
};
</script>

<template>
    <div class="data-table overflow-x-auto px-3 m-3">
        <table
            class="min-w-full bg-white border border-gray-200 rounded-lg p-5"
        >
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th
                        class="px-4 py-2 text-left text-sm font-semibold text-gray-600"
                    ></th>
                    <!-- Encabezados -->
                    <th
                        v-for="column in columns"
                        :key="column"
                        class="px-4 py-2 text-center text-sm font-semibold text-gray-600"
                    >
                        {{ column != "id" ? formatColumnHead(column) : "" }}
                    </th>
                    <th v-if="filteredData.length > 0">Actions</th>
                    <th
                        v-if="
                            currentPath.includes('budgets') &&
                            filteredData.length > 0
                        "
                    >
                        PDF
                    </th>
                </tr>
            </thead>
            <tbody>
                <!-- Sin datos -->
                <tr v-if="filteredData.length === 0">
                    <td
                        :colspan="columns.length + 1"
                        class="px-4 py-4 text-center text-sm text-gray-500"
                    >
                        No data found.
                    </td>
                </tr>
                <!-- Datos -->
                <tr
                    v-else
                    v-for="(row, index) in filteredData"
                    :key="row.id"
                    class="border-b hover:bg-gray-50"
                >
                    <td class="px-4 py-2 text-sm text-gray-700">
                        {{ serialNumber(index) }}
                    </td>
                    <td
                        v-for="(value, key) in row"
                        :key="key"
                        class="px-4 py-2 text-sm text-gray-700 text-center"
                    >
                        <span v-if="key === 'content'">
                            {{
                                JSON.parse(value)
                                    .map(
                                        (val) => `${val.quantity} x
                            ${val.description} = ${val.quantity * +val.cost} $`
                                    )
                                    .join(",")
                            }}
                        </span>
                        <span v-else-if="key === 'created_at'">
                            {{
                                new Date(value).toLocaleDateString("es-ES", {
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                })
                            }}
                        </span>

                        <span
                            v-else-if="key === 'image_url'"
                            class="flex justify-center"
                        >
                            <img
                                :src="value"
                                alt="Image"
                                class="h-10 w-10 object-cover rounded"
                            />
                        </span>

                        <span v-else-if="key === 'client'">
                            {{ value?.name }}
                        </span>

                        <span v-else-if="key === 'state'">
                            <StateTile :status="value" :admin="true" />
                        </span>
                        <span v-else class="flex flex-row justify-center">
                            {{ key == "id" || key === "client" ? "" : value }}
                        </span>
                    </td>
                    <td>
                        <div
                            class="flex flex-row gap-6 justify-between m-2 mr-5"
                        >
                            <button
                                v-on:click.prevent="deleteRow(`${row.id}`)"
                                class="text-red-500"
                            >
                                X
                            </button>
                            <button
                                v-on:click.prevent="editRow(`${row.id}`)"
                                class="text-yellow-500"
                            >
                                ✏
                            </button>
                        </div>
                    </td>
                    <td>
                        <div
                            v-if="currentPath.includes('budgets')"
                            class="flex flex-row gap-6 justify-between m-2 mr-5"
                        >
                            <button
                                v-on:click.prevent="generate(`${row.id}`)"
                                class="text-yellow-500"
                            >
                                🖨
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.content {
    white-space: pre-line;
}
</style>
