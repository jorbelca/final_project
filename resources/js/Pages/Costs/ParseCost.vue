<script setup>
import PageHeader from "@/Components/_Default/PageHeader.vue";
import { adaptarTexto } from "@/Components/Budgets/helpers";
import Parser from "@/Components/Costs/Parser/Parser.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout title="Costs">
        <template #header>
            <PageHeader
                title="Archivos"
                padding="24"
                :links="[
                    {
                        text: adaptarTexto('◀ Lista de Costes'),
                        route: 'costs.index',
                    },
                ]"
            />
        </template>
        <Parser />
    </AppLayout>
</template>
