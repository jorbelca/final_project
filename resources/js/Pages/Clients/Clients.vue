<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DataTable from "@/Components/Table.vue";
const props = defineProps({
    clients: Object,
});
</script>

<template>
    <AppLayout title="Clients">
        <template #header>
            <h2 class="font-semibold text-sm text-green-500">
                <a :href="route('clients.create')">Create a Client > </a>
            </h2>
            <div class="flex align-center justify-center gap-5 items-end">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Clients
                </h2>
            </div>
        </template>

        <div class="py-12">
            <DataTable :data="clients"></DataTable>
        </div>
    </AppLayout>
</template>
