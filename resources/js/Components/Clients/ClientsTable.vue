<script setup>
import { router } from "@inertiajs/vue3";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/solid";
import { computed, onMounted, onUnmounted, ref } from "vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/es";
import NoDataMsg from "../UI/NoDataMsg.vue";
import ProcessingMessage from "../UI/ProcessingMessage.vue";
import Pagination from "../Pagination/Pagination.vue";

let loading = ref(false);
const emit = defineEmits(["page-change", "page-size-change"]);
let props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const filteredData = computed(() => {
    return props?.data?.map((item) => {
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
    if (!confirm("Estas seguro que quieres elimnar este cliente?")) return;

    loading.value = true;
    router.delete(
        `clients/${id}`,
        {
            onFinish: () => {
                loading.value = false;
            },
        },
        {
            onError: (errors) => console.error("Error deleting cost:", errors),
        }
    );
};

const editRow = (id) => {
    router.get(`clients/${id}/edit`, {
        onError: (errors) => alert("Error loading edit form."),
    });
};

dayjs.extend(relativeTime);
dayjs.locale("es");
</script>

<template>
    <ProcessingMessage :loading="loading" />
    <div class="p-4 text-text">
        <div class="table-wrapper">
            <NoDataMsg :noData="filteredData?.length === 0" />
            <div class="mobile-view md:hidden">
                <div
                    v-for="(client, key) in filteredData"
                    :key="client.id"
                    class="mobile-card"
                >
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <img
                                v-if="
                                    client.image_url && client.image_url !== ''
                                "
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
                            <p class="font-bold text-text">Creado</p>
                            {{ dayjs(client.created_at).fromNow() }}</span
                        >
                    </div>
                    <hr />
                    <div class="card-body">
                        <div>
                            <p><b>Email:</b> {{ client.email }}</p>
                        </div>
                        <div class="action-buttons">
                            <button
                                @click.prevent="editRow(client.id)"
                                v-if="
                                    $page.props.auth.user.id ===
                                    client.created_by
                                "
                            >
                                <PencilSquareIcon class="icon-edit" />
                            </button>
                            <button @click.prevent="deleteRow(client.id)">
                                <TrashIcon class="icon-delete" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <table
                class="hidden md:table w-full"
                v-if="filteredData?.length > 0"
            >
                <thead>
                    <tr>
                        <th class="table-header"></th>
                        <th class="table-header">Logo</th>
                        <th class="table-header">Nombre</th>
                        <th class="table-header">Email</th>
                        <th class="table-header">Empresa</th>
                        <th class="table-header">Creado</th>
                        <th class="table-header">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(client, key) in filteredData"
                        :key="client.id"
                        class="hover:bg-hover"
                    >
                        <td class="table-cell">{{ serialNumber(key) }}</td>
                        <td class="table-cell">
                            <div class="flex justify-center">
                                <img
                                    v-if="
                                        client.image_url &&
                                        client.image_url !== ''
                                    "
                                    :src="client.image_url"
                                    alt="Client Image"
                                    class="rounded-image"
                                />
                            </div>
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
                            <button
                                v-if="
                                    $page.props.auth.user.id ===
                                    client.created_by
                                "
                                @click.prevent="editRow(client.id)"
                            >
                                <PencilSquareIcon class="icon-edit mx-3" />
                            </button>
                            <button @click.prevent="deleteRow(client.id)">
                                <TrashIcon class="icon-delete" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Pagination
                v-if="props.data?.length > 0"
                :meta="pagination"
                @page-change="emit('page-change', $event)"
                @page-size-change="emit('page-size-change', $event)"
                :pageSizeOptions="[5, 10, 20, 30, 50, 100]"
            />
        </div>
    </div>
</template>
