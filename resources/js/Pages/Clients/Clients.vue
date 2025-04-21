<script setup>
import { router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import ClientsTable from "@/Components/Clients/ClientsTable.vue";
import PageHeader from "@/Components/_Default/PageHeader.vue";
import { adaptarTexto } from "@/Components/Budgets/helpers";
import { ref } from "vue";
import ProcessingMessage from "@/Components/UI/ProcessingMessage.vue";

const props = defineProps({
    clients: Object,
});

const loading = ref(false);

const updateQueryParamsAndVisit = (newParams, url = route("clients.index")) => {
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
        only: ["clients"],
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
    <AppLayout title="Clientes">
        <template #header>
            <PageHeader
                padding="24"
                title="Clientes"
                :links="[
                    {
                        text: adaptarTexto('Crear un Cliente >'),
                        route: 'clients.create',
                    },
                ]"
            />
        </template>

        <div>
            <ProcessingMessage :loading="loading" />
            <ClientsTable
                :data="props.clients.data"
                :pagination="props.clients"
                @page-change="changePage"
                @page-size-change="changeSize"
            />
        </div>
    </AppLayout>
</template>
