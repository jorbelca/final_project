<script setup>
import { router } from "@inertiajs/vue3";
import {
    DocumentDuplicateIcon,
    PencilSquareIcon,
    PrinterIcon,
    TrashIcon,
} from "@heroicons/vue/24/solid";
import { computed, ref } from "vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/en";
import NoDataMsg from "../UI/NoDataMsg.vue";
import ProcessingMessage from "../UI/ProcessingMessage.vue";
import StateTile from "./StateTile.vue";
import ContentCell from "./ContentCell.vue";
import ClientCell from "./ClientCell.vue";

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

    router.delete(`budgets/${id}`, {
        onError: (errors) => console.error("Error deleting cost:", errors),
        onFinish: () => {
            loading.value = false;
        },
    });
};

const editRow = (id) => {
    loading.value = true;
    router.get(`budgets/${id}/edit`, {
        onError: (errors) => alert("Error loading edit form."),
        onFinish: () => {
            loading.value = false;
        },
    });
};

dayjs.extend(relativeTime);
dayjs.locale("en");

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
    <ProcessingMessage :loading="loading" />
    <div class="data-table-container text-text">
        <div class="table-wrapper">
            <NoDataMsg :noData="filteredData.length === 0" />
            <div class="mobile-view md:hidden">
                <div
                    v-for="budget in filteredData"
                    :key="budget.id"
                    class="mobile-card"
                >
                    <div class="card-header">
                        <div class="flex flex-row gap-2 items-end">
                            <ClientCell :client="budget.client" />
                        </div>
                        <div class="flex flex-col gap-2 items-end">
                            <span
                                :title="
                                    dayjs(budget.created_at).format(
                                        'DD/MM/YYYY HH:mm:ss'
                                    )
                                "
                                class="text-sm"
                            >
                                <small class="font-bold">Created &nbsp;</small
                                >{{ dayjs(budget.created_at).fromNow() }}</span
                            ><StateTile :status="budget.state" :admin="true" />
                        </div>
                    </div>
                    <hr />
                    <div class="card-body">
                        <div class="text-nowrap">
                            <p class="text-sm">
                                <b>Taxes:</b> {{ budget.taxes }} %
                            </p>
                            <p class="text-sm">
                                <b>Discount:</b> {{ budget.discount }} %
                            </p>
                        </div>
                        <div class="flex flex-col">
                            <ContentCell
                                :content="JSON.parse(budget.content)"
                                :isMobile="true"
                            />
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="action-buttons">
                                <button @click.prevent="editRow(budget.id)">
                                    <PencilSquareIcon class="icon-edit" />
                                </button>
                                <button @click.prevent="deleteRow(budget.id)">
                                    <TrashIcon class="icon-delete" />
                                </button>
                            </div>
                            <div class="action-buttons">
                                <button
                                    v-on:click.prevent="
                                        generate(`${budget.id}`)
                                    "
                                >
                                    <PrinterIcon class="size-5 text-blue-500" />
                                </button>
                                <button v-on:click.prevent="">
                                    <DocumentDuplicateIcon
                                        class="size-5 text-gray-500 dark:text-gray-100"
                                    />
                                </button>
                            </div>
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
                        <th class="table-header">Content</th>
                        <th class="table-header">Taxes</th>

                        <th class="table-header">Discount</th>
                        <th class="table-header">Client</th>
                        <th class="table-header">Created</th>
                        <th class="table-header">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(budget, key) in filteredData"
                        :key="budget.id"
                        class="hover:bg-hover"
                    >
                        <td class="table-cell">
                            <p class="font-bold">
                                {{ serialNumber(key) }}
                            </p>
                        </td>
                        <td class="table-cell">
                            <ContentCell
                                :content="JSON.parse(budget.content)"
                            />
                        </td>
                        <td class="table-cell">{{ budget.taxes }} %</td>
                        <td class="table-cell">{{ budget.discount }} %</td>
                        <td class="table-cell">
                            <ClientCell :client="budget.client" />
                        </td>

                        <td class="table-cell">
                            <span
                                :title="
                                    dayjs(budget.created_at).format(
                                        'DD/MM/YYYY HH:mm:ss'
                                    )
                                "
                            >
                                {{ dayjs(budget.created_at).fromNow() }}</span
                            >
                        </td>
                        <td class="table-cell">
                            <div>
                                <button
                                    @click.prevent="editRow(budget.id)"
                                    title="Edit Budget"
                                >
                                    <PencilSquareIcon class="icon-edit m-1" />
                                </button>
                                <button
                                    @click.prevent="deleteRow(cost.id)"
                                    title="Delete Budget"
                                >
                                    <TrashIcon class="icon-delete m-1" />
                                </button>
                            </div>
                            <div>
                                <button
                                    v-on:click.prevent="
                                        generate(`${budget.id}`)
                                    "
                                    title="Generate PDF"
                                >
                                    <PrinterIcon
                                        class="size-5 text-blue-500 m-1"
                                    />
                                </button>

                                <button
                                    v-on:click.prevent=""
                                    title="Duplicate the same budget"
                                >
                                    <DocumentDuplicateIcon
                                        class="size-5 text-gray-500 dark:text-gray-100 m-1"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
