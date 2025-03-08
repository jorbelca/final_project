<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import BudgetTable from "@/Components/Budgets/BudgetTable.vue";
import BudgetCounter from "@/Components/Budgets/BudgetCounter.vue";
import PageHeader from "@/Components/PageHeader.vue";

const props = defineProps({
    budgets: Array,
});
</script>

<template>
    <AppLayout title="Budgets">
        <template #header>
            <PageHeader
                title="Budgets"
                :links="[
                    { text: 'Create a Budget >', route: 'budgets.create' },
                ]"
                :padding="8"
            >
                <div class="flex self-start">
                    <BudgetCounter :budgets="budgets"></BudgetCounter>
                </div>
            </PageHeader>
        </template>
        <div>
            <BudgetTable :data="budgets" />
        </div>
    </AppLayout>
</template>
