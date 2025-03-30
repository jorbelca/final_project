<script setup>
import BudgetForm from "@/Components/Budgets/BudgetForm.vue";
import PageHeader from "@/Components/PageHeader.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    clone: Boolean,
    clients: Array,
    costs: Array,
    budget: Object,
});
</script>

<template>
    <AppLayout :title="'Presupuesto ' + props.budget.id">
        <template #header>
            <PageHeader
                :padding="24"
                :title="
                    clone
                        ? 'Clon del Presupuesto ' + props.budget.id
                        : 'Edicion del Presupuesto  ' + props.budget.id
                "
                :links="[
                    {
                        text: '◀ Lista de Presupuestos ',
                        route: 'budgets.index',
                    },
                ]"
            >
            </PageHeader>
        </template>
        <BudgetForm
            :clone="true"
            :clients="props.clients"
            :costs="props.costs"
            :budget="props.budget"
        />
    </AppLayout>
</template>
