<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CostsTable from "@/Components/Costs/CostsTable.vue";
defineProps({
    costs: Object,
});
</script>

<template>
    <AppLayout title="Costs">
        <template #header>
            <div class="flex justify-between items-center w-full">
                <!-- Enlaces alineados a la izquierda -->
                <div class="flex flex-col text-xs sm:text-sm">
                    <h2 class="font-semibold text-green-500 hover:underline">
                        <a :href="route('costs.create')">Create a Cost ></a>
                    </h2>
                    <h2 class="font-semibold text-primary hover:underline pt-1">
                        <a :href="route('costs.parse')">Upload a File ></a>
                    </h2>
                </div>

                <!-- Título seccion-->
                <div class="flex-grow text-center pr-14">
                    <h2 class="font-semibold text-xl text-text leading-tight">
                        Costs
                    </h2>
                </div>
            </div>
        </template>

        <div>
            <CostsTable :data="costs" />
        </div>
    </AppLayout>
</template>
