<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import BudgetTable from "@/Components/Budgets/BudgetTable.vue";
import BudgetCounter from "@/Components/Budgets/BudgetCounter.vue";
import PageHeader from "@/Components/PageHeader.vue";

const props = defineProps({
    budgets: Array,
});

const changePage = (url) => {
    if (url) {
        router.visit(url, { preserveScroll: true, only: ["budgets"] });
    }
};
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
                    <BudgetCounter :budgets="budgets.data"></BudgetCounter>
                </div>
            </PageHeader>
        </template>
        <div>
            <BudgetTable
                :data="budgets.data"
                :pagination="budgets"
                @page-change="changePage"
            />
        </div>
    </AppLayout>
</template>
