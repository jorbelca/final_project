<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CostsTable from "@/Components/Costs/CostsTable.vue";
import PageHeader from "@/Components/_Default/PageHeader.vue";
import { adaptarTexto } from "@/Components/Budgets/helpers";

defineProps({
    costs: Object,
});
</script>

<template>
    <AppLayout title="Costes">
        <template #header>
            <PageHeader
                title="Costes"
                padding="24"
                :links="[
                    {
                        text: adaptarTexto('Crear un Coste >'),
                        route: 'costs.create',
                    },
                    {
                        text: adaptarTexto('Subir archivo >'),
                        route: 'costs.parse',
                    },
                ]"
            />
        </template>

        <div>
            <CostsTable :data="costs" />
        </div>
    </AppLayout>
</template>
