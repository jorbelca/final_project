<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CostsTable from "@/Components/Costs/CostsTable.vue";
import PageHeader from "@/Components/PageHeader.vue";

defineProps({
    costs: Object,
});
</script>

<template>
    <AppLayout title="Costes">
        <template #header>
            <PageHeader
                title="Costes"
                :links="[
                    { text: 'Crear un Coste >', route: 'costs.create' },
                    { text: 'Subir archivo >', route: 'costs.parse' },
                ]"
            />
        </template>

        <div>
            <CostsTable :data="costs" />
        </div>
    </AppLayout>
</template>
