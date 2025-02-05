<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DataTable from "@/Components/Table.vue";
import BudgetCounter from "@/Components/BudgetCounter.vue";

const props = defineProps({
    budgets: Array,
});
</script>

<template>
    <AppLayout title="Budgets">
        <template #header
            ><h2 class="font-semibold text-sm text-green-500">
                <a :href="route('budgets.create')">Create a Budget ></a>
            </h2>
            <div class="flex align-center justify-center gap-5 items-end">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Budgets
                </h2>
            </div>
            <div class="flex self-start">
                <BudgetCounter :budgets="budgets"></BudgetCounter>
            </div>
        </template>

        <div class="py-12">
            <DataTable :data="budgets"></DataTable>
        </div>
    </AppLayout>
</template>
