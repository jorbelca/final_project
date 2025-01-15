<script setup>
import { computed, onMounted } from "vue";

// Computed para las columnas
let props = defineProps({
    data: {
        type: Array,
        required: true,
    },
});

const excludedFields = ["updated_at", "id", "pivot", "email_verified_at"];

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

onMounted(() => console.log(props.data[0].image_url));
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
