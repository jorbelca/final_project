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

    // Start with current window query parameters to preserve all filters
    const windowParams = new URLSearchParams(window.location.search);
    const currentParams = Object.fromEntries(windowParams.entries());

    // Parse the destination URL
    const destinationUrl = new URL(url, window.location.origin);

    // Merge parameters: current window params + new params (which take precedence)
    const finalParams = { ...currentParams, ...newParams };

    router.visit(destinationUrl.pathname, {
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
