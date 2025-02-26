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
    if (!confirm("Are you sure you want to delete this client?")) return;

    router.delete(`clients/${id}`, {
        onError: (errors) => console.error("Error deleting cost:", errors),
    });
};

const editRow = (id) => {
    router.get(`clients/${id}/edit`, {
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
                    v-for="(client, key) in filteredData"
                    :key="client.id"
                    class="mobile-card"
                >
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <img
                                :src="client.image_url"
                                alt="Client Image"
                                class="rounded-image"
                            />
                            <p class="font-bold">
                                {{ serialNumber(key) }}. {{ client.name }}
                                <br />
                                <span class="font-normal">
                                    &nbsp; {{ client.company_name }}
                                </span>
                            </p>
                        </div>
                        <span
                            :title="
                                dayjs(client.created_at).format(
                                    'DD/MM/YYYY HH:mm:ss'
                                )
                            "
                        >
                            {{ dayjs(client.created_at).fromNow() }}</span
                        >
                    </div>
                    <hr />
                    <div class="card-body">
                        <div>
                            <p><b>Email:</b> {{ client.email }}</p>
                        </div>
                        <div class="action-buttons">
                            <button @click.prevent="editRow(client.id)">
                                <PencilSquareIcon class="icon-edit" />
                            </button>
                            <button @click.prevent="deleteRow(client.id)">
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
                        <th class="table-header">Logo</th>
                        <th class="table-header">Name</th>
                        <th class="table-header">Email</th>
                        <th class="table-header">Company</th>
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
                        v-for="(client, key) in filteredData"
                        :key="client.id"
                        class="hover:bg-hover"
                    >
                        <td class="table-cell">{{ serialNumber(key) }}</td>
                        <td class="table-cell">
                            <img
                                :src="client.image_url"
                                alt="Client Image"
                                class="rounded-image"
                            />
                        </td>
                        <td class="table-cell">{{ client.name }}</td>
                        <td class="table-cell">{{ client.email }}</td>
                        <td class="table-cell">{{ client.company_name }}</td>
                        <td class="table-cell">
                            <span
                                :title="
                                    dayjs(client.created_at).format(
                                        'DD/MM/YYYY HH:mm:ss'
                                    )
                                "
                            >
                                {{ dayjs(client.created_at).fromNow() }}</span
                            >
                        </td>
                        <td class="table-cell">
                            <button @click.prevent="editRow(client.id)">
                                <PencilSquareIcon class="icon-edit mx-3" />
                            </button>
                            <button @click.prevent="deleteRow(client.id)">
                                <TrashIcon class="icon-delete" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
