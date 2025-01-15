<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    costs: Object,
});
</script>

<template>
    <AppLayout title="Costs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Costs
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <ul>
                        <li v-for="cost in costs" :key="cost.id">
                            {{ cost.id }}. {{ cost.description }} // Cost:
                            {{ cost.cost }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
