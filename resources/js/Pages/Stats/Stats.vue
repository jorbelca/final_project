<script setup>
import StateTile from "@/Components/Budgets/StateTile.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
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
</script>

<template>
    <AppLayout title="Stats">
        <template #header>
            <h2 class="font-semibold text-xl text-text leading-tight">
                Estadisticas
            </h2>

            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4"
            >
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="text-gray-500 text-sm">Clientes Totales</div>
                    <div class="text-2xl font-bold mt-1">
                        {{ clientsCount }}
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="text-gray-500 text-sm">
                        Total de Presupuestos
                    </div>
                    <div class="text-2xl font-bold mt-1">
                        {{ budgetsStats.total }}
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <div
                            v-for="(count, state) in budgetsStats.by_state"
                            :key="state"
                            class="flex items-center mr-3 mb-1"
                        >
                            <StateTile :admin="true" :status="state" />
                            <span class="ml-4 font-bold">{{ count }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="text-gray-500 text-sm">
                        Clients con Presupuestos
                    </div>
                    <div class="text-2xl font-bold mt-1">
                        {{ clientByBudgetState.length }}
                    </div>
                </div>
            </div>

            <!-- Enlarged client section -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-3">Detalles de Clientes</h3>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <div
                            v-for="(client, index) in clientByBudgetState"
                            :key="index"
                            class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200 bg-gray-50"
                        >
                            <div
                                class="font-bold text-lg border-b border-gray-200 pb-2 mb-2"
                            >
                                {{ client.client_name }}
                            </div>

                            <div class="text-sm font-semibold mb-2">
                                Presupuestos: {{ client.total_budgets }}
                            </div>

                            <div class="space-y-2">
                                <div
                                    v-for="(
                                        count, state, stateIndex
                                    ) in client.budgets_by_state"
                                    :key="stateIndex"
                                    class="flex items-center"
                                >
                                    <StateTile :admin="true" :status="state" />
                                    <span class="ml-2 font-medium">{{
                                        count
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>
