<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import BudgetTable from "@/Components/Budgets/BudgetTable.vue";
import BudgetCounter from "@/Components/Budgets/BudgetCounter.vue";
import PageHeader from "@/Components/_Default/PageHeader.vue";
import ProcessingMessage from "@/Components/UI/ProcessingMessage.vue";
import { ref } from "vue";
import { adaptarTexto } from "@/Components/Budgets/helpers";

const props = defineProps({
    budgets: Array,
    budgetCount: Number,
});

let loading = ref(false);


const updateQueryParamsAndVisit = (newParams, url = route("budgets.index")) => {
    loading.value = true;
    // Get current query parameters from window.location.search if url is not provided or doesn't contain query string
    const currentUrl = new URL(url, window.location.origin);
    const currentParams = new URLSearchParams(currentUrl.search);
    const params = Object.fromEntries(currentParams.entries());

    // Merge new parameters with existing ones
    const finalParams = { ...params, ...newParams };

    router.visit(currentUrl.pathname, {
        method: "get",
        data: finalParams, // Pass all merged parameters
        preserveScroll: true,
        only: ["budgets"],
        onFinish: () => {
            loading.value = false;
        },
    });
};

const changePage = (url) => {
    if (url) {
        // Extract query parameters from the provided URL
        const urlObject = new URL(url);
        const params = Object.fromEntries(urlObject.searchParams.entries());
        updateQueryParamsAndVisit(params, url);
    }
};

const changeSize = (size) => {
    // When changing size, reset page to 1 and keep other existing params
    updateQueryParamsAndVisit({ size: size, page: 1 });
};


</script>

<template>
    <AppLayout title="Presupuestos">
        <template #header>
            <PageHeader
                title="Presupuestos"
                :links="[
                    {
                        text: adaptarTexto('Crea un Presupuesto >'),
                        route: 'budgets.create',
                    },
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
                @page-size-change="changeSize"
            />
        </div>
    </AppLayout>
</template>
