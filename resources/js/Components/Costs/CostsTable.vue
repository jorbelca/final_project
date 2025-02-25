<script setup>
import { router } from "@inertiajs/vue3";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/solid";
import { computed } from "vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/en";

// Computed para las columnas
let props = defineProps({
    data: {
        type: Array,
        required: true,
    },
});

const filteredData = computed(() => {
    return props.data.map((item) => {
        const filteredItem = { ...item };
        delete filteredItem.user_id;
        return filteredItem;
    });
});

// Métodos para el componente
function serialNumber(key) {
    return key + 1;
}

// Métodos de edición y eliminación
const deleteRow = (id) => {
    if (!confirm("Are you sure you want to delete this cost?")) return;

    router.delete(`costs/${id}`, {
        onError: (errors) => console.error("Error deleting cost:", errors),
    });
};

const editRow = (id) => {
    router.get(`costs/${id}/edit`, {
        onError: (errors) => alert("Error loading edit form."),
    });
};

dayjs.extend(relativeTime);
dayjs.locale("en");
</script>

<template>
    <div class="data-table-container text-text">
        <div class="table-wrapper">
            <div class="mobile-view md:hidden">
                <div
                    v-for="cost in filteredData"
                    :key="cost.id"
                    class="mobile-card"
                >
                    <div class="card-header">
                        <p class="font-bold">
                            {{ cost.id }}. {{ cost.description }}
                        </p>
                        <span
                            :title="
                                dayjs(cost.created_at).format(
                                    'DD/MM/YYYY HH:mm:ss'
                                )
                            "
                        >
                            {{ dayjs(cost.created_at).fromNow() }}</span
                        >
                    </div>
                    <hr />
                    <div class="card-body">
                        <div>
                            <p><b>Cost:</b> {{ cost.cost }} $</p>
                            <p><b>Periodicity:</b> {{ cost.periodicity }}</p>
                        </div>
                        <div class="action-buttons">
                            <button @click.prevent="editRow(cost.id)">
                                <PencilSquareIcon class="icon-edit" />
                            </button>
                            <button @click.prevent="deleteRow(cost.id)">
                                <TrashIcon class="icon-delete" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <table class="hidden md:table w-full">
                <thead>
                    <tr>
                        <th class="table-header"></th>
                        <th class="table-header">Description</th>
                        <th class="table-header">Cost</th>
                        <th class="table-header">Periodicity</th>
                        <th class="table-header">Created</th>
                        <th class="table-header">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="filteredData.length === 0">
                        <td :colspan="6" class="empty-message">
                            No data found.
                        </td>
                    </tr>
                    <tr
                        v-else
                        v-for="(cost, key) in filteredData"
                        :key="cost.id"
                        class="hover:bg-hover"
                    >
                        <td class="table-cell">{{ serialNumber(key) }}</td>
                        <td class="table-cell">{{ cost.description }}</td>
                        <td class="table-cell">{{ cost.cost }}</td>
                        <td class="table-cell">{{ cost.periodicity }}</td>
                        <td class="table-cell">
                            <span
                                :title="
                                    dayjs(cost.created_at).format(
                                        'DD/MM/YYYY HH:mm:ss'
                                    )
                                "
                            >
                                {{ dayjs(cost.created_at).fromNow() }}</span
                            >
                        </td>
                        <td class="table-cell">
                            <button @click.prevent="editRow(cost.id)">
                                <PencilSquareIcon class="icon-edit mx-3" />
                            </button>
                            <button @click.prevent="deleteRow(cost.id)">
                                <TrashIcon class="icon-delete" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
