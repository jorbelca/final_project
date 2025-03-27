<script setup>
import FormCosts from "@/Components/Costs/FormCosts.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    cost: Object,
});
</script>

<template>
    <AppLayout title="Edit Cost">
        <template #header>
            <div class="flex justify-between items-center w-full">
                <!-- Enlaces alineados a la izquierda -->
                <div class="flex flex-col text-xs sm:text-sm">
                    <h2 class="font-semibold text-amber-500">
                        <a :href="route('costs.index')">◀ Lista de Costes</a>
                    </h2>
                </div>

                <!-- Título seccion-->
                <div class="flex-grow text-center pr-14">
                    <h2 class="font-semibold text-xl text-text leading-tight">
                        Editar un Coste
                    </h2>
                </div>
            </div>
        </template>
        <div>
            <FormCosts :cost="props.cost" />
        </div>
    </AppLayout>
</template>
