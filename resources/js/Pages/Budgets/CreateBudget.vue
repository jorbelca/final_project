<script setup>
import BudgetForm from "@/Components/Budgets/BudgetForm.vue";
import PageHeader from "@/Components/_Default/PageHeader.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineProps } from "vue";
const props = defineProps({
    costs: Array,
    clients: Array,
    budget: Object,
    taxes: Number,
});
</script>

<template>
    <AppLayout title="Crea un Presupuesto">
        <template #header>
            <PageHeader
                title="Crear un Presupuesto"
                :links="[
                    {
                        text: '◀ Lista de Presupuestos ',
                        route: 'budgets.index',
                    },
                ]"
                :form="true"
            >
            </PageHeader>
        </template>
        <div>
            <BudgetForm
                :clients="props.clients"
                :costs="props.costs"
                :budget="props.budget"
                :taxes="props.taxes"
            />
        </div>
    </AppLayout>
</template>
