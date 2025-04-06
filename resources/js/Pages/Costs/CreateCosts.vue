<script setup>
import PageHeader from "@/Components/_Default/PageHeader.vue";
import { adaptarTexto } from "@/Components/Budgets/helpers";
import FormCosts from "@/Components/Costs/FormCosts.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout title="Crear Coste">
        <template #header>
            <PageHeader
                title="Crear un Coste"
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
            <FormCosts /></div
    ></AppLayout>
</template>
