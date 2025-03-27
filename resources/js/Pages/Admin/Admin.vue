<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import BudgetCounter from "@/Components/Budgets/BudgetCounter.vue";

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
            <div class="min-w-[130px] pl-2">
                <h3 class="font-bold">
                    User with ID :
                    <span>{{ user.id }}</span>
                </h3>

                <div class="flex align-content-center">
                    <img
                        v-if="user?.profile_photo_path"
                        :src="user?.profile_photo_path"
                        alt=""
                        width="30"
                    />
                    {{ user.name }}
                </div>

                <div v-if="user.active == 1" class="text-green-500">Active</div>
                <div v-if="user.active == 0" class="text-red-500">Inactive</div>

                <button
                    @click="changeState(user.id)"
                    class="rounded-sm bg-zinc-400 hover:bg-zinc-500 text-white p-1"
                >
                    Change State
                </button>
            </div>
            <div
                class="px-4 flex flex-nowrap flex-col justify-center items-center min-w-[170px]"
            >
                <p class="font-bold">
                    Nº of Budgets : &nbsp;<span class="ht">{{
                        user.budgets.length
                    }}</span>
                </p>

                <div class="px-4 flex flex-col justify-center items-center">
                    <BudgetCounter
                        :admin="true"
                        :budgetCount="user.budgetCounts"
                    />
                </div>
            </div>
            <div
                class="px-4 flex flex-nowrap flex-row justify-center items-center min-w-[150px]"
            >
                <h3 class="font-bold">Nº of Clients :&nbsp;</h3>
                <span>{{ user.clients_count }}</span>
            </div>
            <div
                class="px-4 flex flex-row justify-center items-center min-w-[150px]"
            >
                <h3 class="font-bold">Nº of Costs :&nbsp;</h3>
                <span>{{ user.costs_count }}</span>
            </div>
        </div>
        <div class="pb-10"></div>
    </AppLayout>
</template>
