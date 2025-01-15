<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DataTable from "@/Components/Table.vue";
defineProps({
    costs: Object,
});
</script>

<template>
    <AppLayout title="Costs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Costs
            </h2>
        </template>

        <div class="py-12">
            <DataTable :data="costs"></DataTable>
        </div>
    </AppLayout>
</template>
