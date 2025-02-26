<script setup>
import FormClients from "@/Components/Clients/FormClients.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineProps } from "vue";

const props = defineProps({
    client: Object,
});
</script>

<template>
    <AppLayout title="Edit Client">
        <template #header>
            <div class="flex justify-between items-center w-full">
                <!-- Enlaces alineados a la izquierda -->
                <div class="flex flex-col text-xs sm:text-sm">
                    <h2 class="font-semibold text-amber-500 text-nowrap">
                        <a :href="route('clients.index')">◀ List of Clients</a>
                    </h2>
                </div>

                <!-- Título seccion-->
                <div class="flex-grow text-center pr-14">
                    <h2 class="font-semibold text-xl text-text leading-tight">
                        Edit a Client
                    </h2>
                </div>
            </div>
        </template>

        <div>
            <FormClients :client="props.client" />
        </div>
    </AppLayout>
</template>
