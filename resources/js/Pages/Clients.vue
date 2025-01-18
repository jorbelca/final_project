<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DataTable from "@/Components/Table.vue";
const props = defineProps({
    clients: Object,
});
console.log(props.clients);
</script>

<template>
    <AppLayout title="Clients">
        <template #header>
            <div class="flex align-center gap-5">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Clients
                </h2>
                <h2 class="font-semibold text-l text-green-500">
                    <a :href="route('clients.create')">Create</a>
                </h2>
            </div>
        </template>

        <div class="py-12">
            <DataTable :data="clients"></DataTable>
        </div>
    </AppLayout>
</template>
