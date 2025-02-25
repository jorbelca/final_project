<script setup>
import FormCosts from "@/Components/Costs/FormCosts.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout title="Create Cost">
        <template #header>
            <div class="flex justify-between items-center w-full">
                <!-- Enlaces alineados a la izquierda -->
                <div class="flex flex-col text-xs sm:text-sm">
                    <h2 class="font-semibold text-amber-500">
                        <a :href="route('costs.index')">◀ List of Costs</a>
                    </h2>
                </div>

                <!-- Título seccion-->
                <div class="flex-grow text-center pr-14">
                    <h2 class="font-semibold text-xl text-text leading-tight">
                        Create a Cost
                    </h2>
                </div>
            </div>
        </template>
        <div>
            <FormCosts /></div
    ></AppLayout>
</template>
