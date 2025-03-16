<script setup>
import Parser from "@/Components/Costs/Parser/Parser.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout title="Costs">
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
                        Files Upload
                    </h2>
                </div>
            </div>
        </template>
        <Parser />
    </AppLayout>
</template>
