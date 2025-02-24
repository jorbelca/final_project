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
            <div class="flex flex-col justify-content-around w-1/4">
                <h2 class="font-semibold text-sm text-green-500">
                    <a :href="route('costs.create')">Create a Cost > </a>
                </h2>
                <h2 class="font-semibold text-sm text-blue-500">
                    <a :href="route('costs.parse')">Upload a File (beta) > </a>
                </h2>
            </div>
            <div class="flex align-center justify-center gap-5 items-end">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Costs
                </h2>
            </div>
        </template>

        <div class="py-12">
            <DataTable :data="costs"></DataTable>
        </div>
    </AppLayout>
</template>
