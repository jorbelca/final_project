<script setup>
import { router } from "@inertiajs/vue3";
import { computed, ref, onMounted, onBeforeUnmount } from "vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/es";
// Initialize the relativeTime plugin
dayjs.extend(relativeTime);
dayjs.locale("es");
import NoDataMsg from "../UI/NoDataMsg.vue";
import ProcessingMessage from "../UI/ProcessingMessage.vue";
import StateTile from "./StateTile.vue";
import ContentCell from "./ContentCell.vue";
import ClientCell from "./ClientCell.vue";
import Pagination from "../Pagination/Pagination.vue";
import Tooltip from "./Tooltip.vue";
import Buttons from "./Buttons.vue";

let loading = ref(false);
// Replace the local ref with a prop
const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["page-change", "page-size-change"]);

const filteredData = computed(() => {
    return props.data.map((item) => {
        const filteredItem = { ...item };
        delete filteredItem.user_id;
        return filteredItem;
    });
});

// Métodos para el componente
// function serialNumber(key) {
//     return key + 1;
// }

// Métodos de edición y eliminación
const deleteRow = (id) => {
    if (!confirm("¿Estás seguro de que deseas eliminar este presupuesto?"))
        return;
    loading.value = true;
    router.delete(`budgets/${id}`, {
        onError: (errors) =>
            console.error("Error eliminando el presupuesto:", errors),
        onFinish: () => {
            loading.value = false;
        },
    });
};

const editRow = (id) => {
    loading.value = true;
    router.get(`budgets/${id}/edit`, {
        onError: (errors) =>
            alert("Error cargando el formulario del presupuesto."),
        onFinish: () => {
            loading.value = false;
        },
    });
};

const cloneBudget = (id) => {
    loading.value = true;
    router.get(`budget/${id}/clone`, {
        onError: (errors) =>
            alert("Error cargando el formulario del presupuesto."),
        onFinish: () => {
            loading.value = false;
        },
    });
};

const generate = (id) => {
    try {
        window.open(`/budget/${id}/generate`, "_blank");
    } catch (error) {
        alert("Error generando el presupuesto.");
        console.error("Error generando el presupuesto:", error);
    }
};

const downloadPdf = async (id) => {
    try {
        loading.value = true;
        const response = await fetch(`/budget/${id}/generate?download=true`);
        if (!response.ok) {
            throw new Error("No se recibió el PDF.");
        }
        const data = await response.blob(); // Convertir la respuesta en un archivo
        const url = window.URL.createObjectURL(data); // Crear una url temporal
        const a = document.createElement("a"); //Crear enlace
        a.href = url;
        a.download = `budget-${id}.pdf`; //Nombre del archivo
        document.body.appendChild(a);
        a.click(); //Simular click
        document.body.removeChild(a); //Elimina enlace
        window.URL.revokeObjectURL(url); //Liberar memoria

        loading.value = false;
        alert("Pdf descargado");

        loading.value = false;
    } catch (error) {
        loading.value = false;
        alert("Error generando el pdf");
        console.error("Error generando el pdf:", error);
    }
};

const isMobileState = ref(window.innerWidth < 500);

const checkIfMobile = () => {
    isMobileState.value = window.innerWidth < 500;
};

const isMobile = () => {
    return isMobileState.value;
};

onMounted(() => {
    window.addEventListener("resize", checkIfMobile);
    // Initial check
    checkIfMobile();
});

onBeforeUnmount(() => {
    window.removeEventListener("resize", checkIfMobile);
});
</script>

<template>
    <ProcessingMessage :loading="loading" />
    <div class="p-2 sm:p-6 text-text">
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
                                <small class="font-normal">Creado &nbsp;</small
                                >{{ dayjs(budget.created_at).fromNow() }}</span
                            ><StateTile :status="budget.state" :admin="true" />
                        </div>
                    </div>
                    <hr />
                    <div class="card-body">
                        <div class="text-nowrap">
                            <p class="text-sm">
                                <Tooltip v-if="isMobile()" text="Impuestos" />
                                <b v-if="!isMobile()"> Impuestos:</b>
                                {{ budget.taxes }} %
                            </p>

                            <p class="text-sm">
                                <Tooltip v-if="isMobile()" text="Descuento" />
                                <b v-if="!isMobile()"> Descuento:</b>
                                {{ budget.discount }} %
                            </p>
                        </div>
                        <div>
                            <ContentCell
                                :content="JSON.parse(budget.content)"
                                :isMobile="true"
                            />
                        </div>
                        <div class="flex flex-col items-center">
                            <small>Acciones</small>
                            <Buttons
                                :budget-id="budget.id"
                                @edit="editRow"
                                @delete="deleteRow"
                                @clone="cloneBudget"
                                @generate="generate"
                                @download="downloadPdf"
                            />
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
                        <th class="table-header">Contenido</th>
                        <th class="table-header">Estado</th>
                        <th class="table-header">Impuestos</th>
                        <th class="table-header">Descuento</th>
                        <th class="table-header">Cliente</th>
                        <th class="table-header">Creado</th>
                        <th class="table-header">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="budget in filteredData"
                        :key="budget.id"
                        class="hover:bg-hover"
                    >
                        <td class="table-cell">
                            <ContentCell
                                :content="JSON.parse(budget.content)"
                            />
                        </td>
                        <td class="table-cell">
                            <StateTile :status="budget.state" :admin="true" />
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
                            <Buttons
                                :budget-id="budget.id"
                                @edit="editRow"
                                @delete="deleteRow"
                                @clone="cloneBudget"
                                @generate="generate"
                                @download="downloadPdf"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination
                v-if="props.data.length > 0"
                :meta="pagination"
                @page-change="emit('page-change', $event)"
                @page-size-change="emit('page-size-change', $event)"
            />
        </div>
    </div>
</template>
