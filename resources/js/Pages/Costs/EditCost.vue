<script setup>
import PageHeader from "@/Components/_Default/PageHeader.vue";
import { adaptarTexto } from "@/Components/Budgets/helpers";
import FormCosts from "@/Components/Costs/FormCosts.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    cost: Object,
});
</script>

<template>
    <AppLayout title="Editar Coste">
        <template #header>
            <PageHeader
                title="Editar Coste"
                padding="24"
                :links="[
                    {
                        text: adaptarTexto('◀ Lista de Costes'),
                        route: 'costs.index',
                    },
                ]"
            />
        </template>
        <div>
            <FormCosts :cost="props.cost" />
        </div>
    </AppLayout>
</template>
