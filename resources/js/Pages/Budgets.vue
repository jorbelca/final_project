<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    budgets: Object,
});
</script>

<template>
    <AppLayout title="Budgets">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Budgets
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <ul>
                        <li v-for="budget in budgets" :key="budget.id">
                            {{ budget.id }}. {{ budget.user_id }} // Discounts:
                            {{ budget.discount }} %//Taxes :{{ budget.taxes }}%
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
