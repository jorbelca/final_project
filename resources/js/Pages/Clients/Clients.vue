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
