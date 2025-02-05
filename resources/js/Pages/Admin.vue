<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import StateTile from "@/Components/StateTile.vue";

const props = defineProps({
    users: [],
});

const changeState = (user_id) => {
    try {
        router.post(`users/${user_id}/changestate`, {
            onSuccess: () => {
                alert(` Changed successfully`);
            },
            onError: (errors) => {
                console.error(`Error :`, errors);
            },
        });
    } catch (e) {
        alert(`Error` + e);
    }
};
const getBudgetCounts = (user) => {
    return user.budgets.reduce((acc, budget) => {
        acc[budget.state] = (acc[budget.state] || 0) + 1;
        return acc;
    }, {});
};
</script>

<template>
    <AppLayout title="Admin">
        <template #header>
            <div class="flex align-center justify-center gap-5 items-end">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Admin
                </h2>
            </div>
        </template>

        <div
            class="overflow-x-auto p-2 m-3 bg-white rounded-lg flex flex-row justify-between"
            v-for="user in props.users"
            :key="user.id"
        >
            <div class="w-1/4 pl-2">
                <h3 class="font-bold">User</h3>
                <div class="">{{ user.id }}</div>
                <img :src="user?.profile_photo_path" alt="" width="30" />
                <div class="">{{ user.name }}</div>

                <div v-if="user.active == 1" class="text-green-500">Active</div>
                <div v-if="user.active == 0" class="text-red-500">Inactive</div>

                <button
                    @click="changeState(user.id)"
                    class="rounded-sm bg-zinc-400 hover:bg-zinc-500 text-white p-1"
                >
                    Change State
                </button>
            </div>
            <div class="px-4 flex flex-col justify-center items-center w-1/4">
                <h3 class="font-bold">Budgets</h3>
                <div>Nº: {{ user.budgets.length }}</div>

                <div>
                    <span class="flex flex-row items-center gap-2 py-1">
                        <StateTile
                            v-if="
                                user.budgets.some(
                                    (budget) => budget.state === 'draft'
                                )
                            "
                            :status="'draft'"
                        />
                        {{ getBudgetCounts(user).draft }}
                    </span>
                    <span class="flex flex-row items-center gap-2 py-1">
                        <StateTile
                            v-if="
                                user.budgets.some(
                                    (budget) => budget.state === 'approved'
                                )
                            "
                            :status="'approved'"
                        />{{ getBudgetCounts(user).approved }}
                    </span>
                    <span class="flex flex-row items-center gap-2 py-1">
                        <StateTile
                            v-if="
                                user.budgets.some(
                                    (budget) => budget.state === 'rejected'
                                )
                            "
                            :status="'rejected'"
                        />
                        {{ getBudgetCounts(user).rejected }}
                    </span>
                </div>
            </div>
            <div class="px-4 flex flex-col justify-center items-center w-1/4">
                <h3 class="font-bold">Clients</h3>
                <div>Nº: {{ user.clients.length }}</div>
            </div>
            <div class="px-4 flex flex-col justify-center items-center w-1/4">
                <h3 class="font-bold">Costs</h3>
                <div>Nº: {{ user.costs.length }}</div>
            </div>
        </div>
        <div class="pb-10"></div>
    </AppLayout>
</template>
