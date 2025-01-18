<script setup>
import { computed } from "vue";
import { router, Link, usePage } from "@inertiajs/vue3";
// Computed para las columnas
let props = defineProps({
    data: {
        type: Array,
        required: true,
    },
});

const excludedFields = ["updated_at", "pivot", "email_verified_at"];

props.data = props.data.map((row) => {
    excludedFields.forEach((field) => {
        delete row[field];
    });
    return row;
});
let columns = computed(() =>
    props.data.length > 0 ? Object.keys(props.data[0]) : []
);

// Métodos para el componente
function serialNumber(key) {
    return key + 1;
}

function formatColumnHead(value) {
    return value.split("_").join(" ").toUpperCase();
}

const deleteRow = (id) => {
    // Obtener el nombre de la ruta actual
    const currentPath = window.location.pathname;

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
    if (confirm(`Are you sure you want to delete this ${resource}?`)) {
        router.delete(`${resource}/${id}`, {
            onSuccess: () => {
                alert(
                    `${
                        resource.charAt(0).toUpperCase() + resource.slice(1)
                    } deleted successfully`
                );
            },
            onError: (errors) => {
                console.error(`Error deleting ${resource}:`, errors);
            },
        });
    }
};
</script>

<template>
    <div class="data-table overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th
                        class="px-4 py-2 text-left text-sm font-semibold text-gray-600"
                    >
                        #
                    </th>
                    <!-- Encabezados -->
                    <th
                        v-for="column in columns"
                        :key="column"
                        class="px-4 py-2 text-left text-sm font-semibold text-gray-600"
                    >
                        {{ formatColumnHead(column) }}
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sin datos -->
                <tr v-if="data.length === 0">
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
                    v-for="(row, index) in data"
                    :key="row.id"
                    class="border-b hover:bg-gray-50"
                >
                    <td class="px-4 py-2 text-sm text-gray-700">
                        {{ serialNumber(index) }}
                    </td>
                    <td
                        v-for="(value, key) in row"
                        :key="key"
                        class="px-4 py-2 text-sm text-gray-700"
                    >
                        <span v-if="key === 'content'">
                            {{
                                JSON.parse(value)
                                    .map(
                                        (val) => `${val.quantity}
                            x ${val.description} = ${val.quantity * val.price}
                            $`
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

                        <span v-else-if="key === 'image_url'">
                            <img
                                :src="value"
                                alt="Image"
                                class="h-10 w-10 object-cover rounded"
                            />
                        </span>
                        <span v-else>
                            {{ value }}
                        </span>
                    </td>
                    <td>
                        <button
                            v-on:click.prevent="deleteRow(`${row.id}`)"
                            class="text-red-500"
                        >
                            X
                        </button>
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
