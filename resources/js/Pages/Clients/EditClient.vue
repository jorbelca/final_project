<script setup>
import PageHeader from "@/Components/_Default/PageHeader.vue";
import { adaptarTexto } from "@/Components/Budgets/helpers";
import FormClients from "@/Components/Clients/FormClients.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineProps } from "vue";

const props = defineProps({
    client: Object,
});
</script>

<template>
    <AppLayout title="Editar Cliente">
        <template #header>
            <PageHeader
                title="Editar Cliente"
                padding="24"
                :links="[
                    {
                        text: adaptarTexto('◀ Listado de Clientes'),
                        route: 'clients.index',
                    },
                ]"
            />
        </template>

        <div>
            <FormClients :client="props.client" />
        </div>
    </AppLayout>
</template>
