<script setup>
import PageHeader from "@/Components/_Default/PageHeader.vue";
import { adaptarTexto } from "@/Components/Budgets/helpers";
import FormClients from "@/Components/Clients/FormClients.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    exists: Boolean,
    email: String,
});
</script>

<template>
    <AppLayout title="Crear Cliente">
        <template #header>
            <PageHeader
                padding="24"
                title="Crear un Cliente"
                :links="[
                    {
                        text: adaptarTexto('◀ Listado de Clientes'),
                        route: 'clients.index',
                    },
                ]"
            />
        </template>

        <div>
            <FormClients :exists="exists" :email="email" />
        </div>
    </AppLayout>
</template>
