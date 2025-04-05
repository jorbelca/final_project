<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ClientsTable from "@/Components/Clients/ClientsTable.vue";
import PageHeader from "@/Components/_Default/PageHeader.vue";

const props = defineProps({
    clients: Object,
});
</script>

<template>
    <AppLayout title="Clientes">
        <template #header>
            <PageHeader
                padding="24"
                title="Clientes"
                :links="[
                    { text: 'Crear un Cliente >', route: 'clients.create' },
                ]"
            />
        </template>

        <div>
            <ClientsTable :data="clients" />
        </div>
    </AppLayout>
</template>
