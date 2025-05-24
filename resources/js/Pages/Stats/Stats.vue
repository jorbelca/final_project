<script setup>
import { formatMoney } from "@/Components/Budgets/helpers";
import AppLayout from "@/Layouts/AppLayout.vue";
import DetailStateTile from "@/Pages/Stats/DetailStateTile.vue";

const props = defineProps({
    budgetsStats: {
        type: Object,
        required: true,
    },
    clientsCount: { type: Number, required: true },
    clientByBudgetState: {
        type: Array,
        required: true,
    },
});

const totalBudgets = (budgets) =>
    Object.values(budgets).reduce(
        (acc, item) =>
            acc +
            (typeof item === "object" && item !== null && "count" in item
                ? item.count
                : item),
        0
    );
const totalQuantityBudgets = totalBudgets(props.budgetsStats.by_state);
const totalAmountBudgets = props.budgetsStats.total_amount;
const totalClientBudgets = props.clientByBudgetState
    .map((client) => totalBudgets(client.budgets_by_state))
    .reduce((acc, item) => acc + item, 0);
</script>

<template>
    <AppLayout title="Stats">
        <template #header v-if="$page?.props?.auth?.user?.profesional">
            <h2 class="font-semibold text-xl text-text leading-tight">
                Estadísticas
            </h2>

            <!-- Stats tiles in column layout -->
            <div class="grid grid-cols-1 gap-4 mb-6 no-wrap mt-2">
                <div class="bg-white dark:bg-hover p-4 rounded-lg shadow-md">
                    <div class="w-full">
                        <DetailStateTile :budgets="budgetsStats.by_state" />
                    </div>
                    <hr />
                    <div class="flex justify-between gap-4 m-2">
                        <div>
                            <div class="text-text text-sm">
                                Total de Presupuestos
                            </div>
                            <div class="text-2xl font-bold mt-1">
                                {{ totalQuantityBudgets }}
                            </div>
                        </div>
                        <div>
                            <div class="text-text text-sm">
                                Total Presupuestado
                            </div>
                            <div class="text-2xl font-bold mt-1">
                                {{ formatMoney(totalAmountBudgets) }} €
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-hover p-4 rounded-lg shadow-md flex justify-around text-center"
                >
                    <div>
                        <div class="text-text text-sm">Clientes Totales</div>
                        <div class="text-2xl font-bold mt-1">
                            {{ clientsCount }}
                        </div>
                    </div>
                    <div>
                        <div class="text-text text-sm">
                            Presupuestos con Cliente
                        </div>
                        <div class="text-2xl font-bold mt-1">
                            {{ totalClientBudgets }}
                        </div>
                    </div>
                    <div>
                        <div class="text-text text-sm">
                            Presupuestos anónimos
                        </div>
                        <div class="text-2xl font-bold mt-1">
                            {{
                                totalClientBudgets
                                    ? totalQuantityBudgets - totalClientBudgets
                                    : 0
                            }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client details section with grid -->
            <div>
                <h3 class="text-lg font-bold mb-3">Clientes</h3>
                <div class="bg-white dark:bg-hover rounded-lg shadow-md p-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div
                            v-for="(client, index) in clientByBudgetState"
                            :key="index"
                            class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200 bg-gray-50 dark:bg-gray-800"
                        >
                            <div
                                class="font-bold text-lg border-b border-gray-200 pb-2 mb-2 flex justify-between items-end"
                            >
                                <div>
                                    {{ client.client_name }}:
                                    <span class="text-sm font-normal">
                                        {{
                                            totalBudgets(
                                                client.budgets_by_state
                                            )
                                        }}
                                        presupuestos
                                        <span
                                            class="text-sm font-normal text-blue-400"
                                        >
                                            (
                                            {{
                                                (
                                                    (totalBudgets(
                                                        client.budgets_by_state
                                                    ) /
                                                        totalQuantityBudgets) *
                                                    100
                                                ).toFixed(1)
                                            }}%)
                                        </span>
                                    </span>
                                </div>
                                <span class="text-sm font-bold">
                                    <span
                                        class="text-sm font-normal text-green-600"
                                    >
                                        (
                                        {{
                                            (
                                                (Object.values(
                                                    client.budgets_by_state
                                                ).reduce((acc, state) => {
                                                    return (acc += state.total);
                                                }, 0) /
                                                    totalAmountBudgets) *
                                                100
                                            ).toFixed(1)
                                        }}%)
                                    </span>
                                    {{
                                        formatMoney(
                                            Object.values(
                                                client.budgets_by_state
                                            ).reduce((acc, state) => {
                                                return (acc += state.total);
                                            }, 0)
                                        )
                                    }}€
                                </span>
                            </div>

                            <DetailStateTile
                                :budgets="client.budgets_by_state"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #header v-else>
            <h2 class="font-semibold text-xl text-text leading-tight">
                Estadísticas
            </h2>
            <h1>Esta sección es solo para usuarios con Plan Bussiness</h1>
        </template>
    </AppLayout>
</template>
