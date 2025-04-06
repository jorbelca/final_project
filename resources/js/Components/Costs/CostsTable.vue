<script setup>
import { router } from "@inertiajs/vue3";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/solid";
import { computed, ref } from "vue";
import dayjs from "dayjs";
import "dayjs/locale/es";
import relativeTime from "dayjs/plugin/relativeTime";
import NoDataMsg from "../UI/NoDataMsg.vue";
import ProcessingMessage from "../UI/ProcessingMessage.vue";
import { periodicity } from "./FormCosts.vue";
import { showDescription } from "../Budgets/helpers";

let loading = ref(false);

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
    loading.value = true;
    if (!confirm("Are you sure you want to delete this cost?")) return;

    router.delete(`costs/${id}`, {
        onError: (errors) => console.error("Error deleting cost:", errors),
        onFinish: () => {
            loading.value = false;
        },
    });
};

const editRow = (id) => {
    loading.value = true;
    router.get(`costs/${id}/edit`, {
        onError: (errors) => alert("Error loading edit form."),
        onFinish: () => {
            loading.value = false;
        },
    });
};

dayjs.extend(relativeTime);
dayjs.locale("es");
</script>

<template>
    <ProcessingMessage :loading="loading" />
    <div class="sm:data-table-container sm:p-4 text-text">
        <div class="table-wrapper">
            <NoDataMsg :noData="filteredData.length === 0" />
            <div class="mobile-view md:hidden">
                <div
                    v-for="cost in filteredData"
                    :key="cost.id"
                    class="mobile-card"
                >
                    <div class="card-header">
                        <p class="font-bold">
                            {{ cost.id }}.
                            <span v-html="showDescription(cost)"></span>
                        </p>
                        <small
                            :title="
                                dayjs(cost.created_at).format(
                                    'DD/MM/YYYY HH:mm:ss'
                                )
                            "
                        >
                            Creado {{ dayjs(cost.created_at).fromNow() }}</small
                        >
                    </div>
                    <hr />
                    <div class="card-body">
                        <div>
                            <p><b>Coste:</b> {{ cost.cost }} €</p>
                            <p>
                                <b>Periodicidad:</b>
                                {{ periodicity[cost.periodicity] }}
                            </p>
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

            <table
                class="hidden md:table w-full"
                v-if="filteredData.length > 0"
            >
                <thead>
                    <tr>
                        <th class="table-header"></th>
                        <th class="table-header">Descripcion</th>
                        <th class="table-header">Coste</th>
                        <th class="table-header">Periodicidad</th>
                        <th class="table-header">Creado</th>
                        <th class="table-header">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(cost, key) in filteredData"
                        :key="cost.id"
                        class="hover:bg-hover"
                    >
                        <td class="table-cell">{{ serialNumber(key) }}</td>
                        <td
                            v-html="showDescription(cost)"
                            class="table-cell"
                        ></td>
                        <td class="table-cell">{{ cost.cost }}</td>
                        <td class="table-cell">
                            {{ periodicity[cost.periodicity] }}
                        </td>
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
