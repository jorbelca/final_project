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
            <div class="flex align-center gap-5">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Costs
                </h2>
                <h2 class="font-semibold text-l text-green-500">
                    <a :href="route('costs.create')">Create</a>
                </h2>
            </div>
        </template>

        <div class="py-12">
            <DataTable :data="costs"></DataTable>
        </div>
    </AppLayout>
</template>
