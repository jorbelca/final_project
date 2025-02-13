<script setup>
import Parser from "@/Components/Parser.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout title="Costs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Costs - File
            </h2>
        </template>
        <Parser></Parser>
    </AppLayout>
</template>
