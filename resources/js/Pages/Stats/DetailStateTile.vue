<script setup>
import { defineProps } from "vue";
import StateTile from "@/Components/Budgets/StateTile.vue";
import { formatMoney } from "@/Components/Budgets/helpers";
import PercentBar from "@/Pages/Stats/PercentBar.vue";

const props = defineProps({
    budgets: {
        type: Object,
        required: true,
    },
});

const budgetsLength = Object.values(props.budgets).reduce(
    (acc, count) => acc + (count.count ? count.count : count),
    0
);
const mobile = window.innerWidth < 640;
</script>

<template>
    <div class="w-full">
        <div
            v-for="(count, state, stateIndex) in props.budgets"
            :key="stateIndex"
            class="grid grid-cols-[50%_1fr_1fr] gap-2 mb-2 justify-items-end sm:gap-4"
        >
            <div class="flex items-center gap-1 justify-self-start">
                <StateTile :admin="false" :status="state" class="min-w-fit" />
                <span class="ml-2 font-medium text-sm">{{ count.count ?? count }}</span>
                <span class="text-xs" v-if="!mobile">Presupuestos</span>
            </div>

            <PercentBar
                :percent="+(((count.count ?? count) / budgetsLength) * 100).toFixed(1)"
            />
            <span class="text-text text-sm" v-if="count.total">
                {{ formatMoney(count.total) }}€
            </span>
        </div>
    </div>
</template>
