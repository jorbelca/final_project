<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CostsTable from "@/Components/Costs/CostsTable.vue";
import PageHeader from "@/Components/PageHeader.vue";

defineProps({
    costs: Object,
});
</script>

<template>
    <AppLayout title="Costs">
        <template #header>
            <PageHeader
                title="Costs"
                :links="[
                    { text: 'Create a Cost >', route: 'costs.create' },
                    { text: 'Upload a File >', route: 'costs.parse' },
                ]"
            />
        </template>

        <div>
            <CostsTable :data="costs" />
        </div>
    </AppLayout>
</template>
