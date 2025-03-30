<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import BudgetTable from "@/Components/Budgets/BudgetTable.vue";
import BudgetCounter from "@/Components/Budgets/BudgetCounter.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ProcessingMessage from "@/Components/UI/ProcessingMessage.vue";
import { ref } from "vue";

const props = defineProps({
    budgets: Array,
    budgetCount: Number,
});

let loading = ref(false);

const changePage = (url) => {
    loading.value = true;
    if (url) {
        router.visit(url, {
            preserveScroll: true,
            only: ["budgets"],
            onFinish: () => {
                loading.value = false;
            },
        });
    }
};
</script>

<template>
    <AppLayout title="Presupuestos">
        <template #header>
            <PageHeader
                title="Presupuestos"
                :links="[
                    { text: 'Crea un Presupuesto >', route: 'budgets.create' },
                ]"
                :padding="24"
            >
                <div class="flex self-start">
                    <BudgetCounter :budgetCount="budgetCount" />
                </div>
            </PageHeader>
        </template>
        <div>
            <ProcessingMessage :loading="loading" />
            <BudgetTable
                :data="budgets.data"
                :pagination="budgets"
                @page-change="changePage"
            />
        </div>
    </AppLayout>
</template>
