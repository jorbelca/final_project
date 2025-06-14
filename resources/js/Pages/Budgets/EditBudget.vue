<script setup>
import BudgetForm from "@/Components/Budgets/BudgetForm.vue";
import { adaptarTexto } from "@/Components/Budgets/helpers";
import PageHeader from "@/Components/_Default/PageHeader.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    IA: Boolean,
    clone: Boolean,
    clients: Array,
    costs: Array,
    budget: Object,
    notas: String,
});
</script>

<template>
    <AppLayout :title="'Presupuesto ' + props.budget.id">
        <template #header>
            <PageHeader
                :padding="24"
                :title="
                    IA
                        ? 'Presupuesto generado por IA '
                        : clone
                        ? 'Clon del Presupuesto ' + props.budget.id
                        : 'Edicion del Presupuesto  ' + props.budget.id
                "
                :links="[
                    {
                        text: adaptarTexto('◀ Lista de Presupuestos '),
                        route: 'budgets.index',
                    },
                ]"
            >
            </PageHeader>
        </template>
        <BudgetForm
            :IA="props.IA"
            :notas="props.notas"
            :clone="props.clone"
            :clients="props.clients"
            :costs="props.costs"
            :budget="props.budget"
        />
    </AppLayout>
</template>
