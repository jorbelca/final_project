<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ClientsTable from "@/Components/Clients/ClientsTable.vue";
import PageHeader from "@/Components/PageHeader.vue";

const props = defineProps({
    clients: Object,
});
</script>

<template>
    <AppLayout title="Clients">
        <template #header>
            <PageHeader
                title="Clients"
                :links="[
                    { text: 'Create a Client >', route: 'clients.create' },
                ]"
            />
        </template>

        <div>
            <ClientsTable :data="clients" />
        </div>
    </AppLayout>
</template>
