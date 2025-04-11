<script setup>
const props = defineProps({
    plans: Array,
    planSelected: Number,
});
</script>

<template>
    <!-- Tiles for plans -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div
            v-for="plan in [...props.plans].sort((a, b) => a.id - b.id)"
            :key="plan.id"
            class="bg-white border border-gray-200 dark:bg-hover rounded-lg shadow-sm p-4 flex flex-col justify-between text-center"
        >
            <div>
                <h5 class="font-semibold text-text">
                    {{ plan.name }}
                </h5>
                <p class="text-text">
                    Creditos:
                    {{ JSON.parse(plan.features).Credits }}
                </p>
                <small
                    class="text-text"
                    v-if="JSON.parse(plan.features)['Soporte prioritario']"
                >
                    Prioridad en el soporte
                </small>
                <p class="font-thin text-text">{{ plan.price }} €</p>
            </div>
            <div class="mt-4">
                <input
                    :checked="plan.id === planSelected"
                    type="radio"
                    :value="plan.id"
                    @change="$emit('update:planSelected', plan.id)"
                    name="plan"
                    id="plan_{{ plan.id }}"
                />
                <label
                    :for="'plan_' + plan.id"
                    class="ml-2 text-text cursor-pointer"
                    >Seleccionar</label
                >
            </div>
        </div>
    </div>
</template>
