<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

import BudgetCounter from "@/Components/BudgetCounter.vue";

const props = defineProps({
    budgets: Array,
});
</script>

<template>
    <AppLayout title="Budgets - Ordered">
        <template #header>
            <div class="flex align-center justify-center gap-5 items-end">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Budgets Ordered
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="py-12">
                <div
                    class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6"
                >
                    <table
                        class="w-full border-collapse border border-gray-300"
                    >
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2">
                                    ID
                                </th>
                                <th class="border border-gray-300 px-4 py-2">
                                    Updated At
                                </th>
                                <th class="border border-gray-300 px-4 py-2">
                                    Content
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="budget in budgets" :key="budget.id">
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ budget.id }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{
                                        new Date(
                                            budget.updated_at
                                        ).toLocaleDateString("es-ES", {
                                            year: "numeric",
                                            month: "numeric",
                                            day: "numeric",
                                            hour: "numeric",
                                            minute: "numeric",
                                        })
                                    }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ budget.content }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
