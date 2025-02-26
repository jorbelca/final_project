<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ClientsTable from "@/Components/Clients/ClientsTable.vue";

const props = defineProps({
    clients: Object,
});
</script>

<template>
    <AppLayout title="Clients">
        <template #header>
            <div class="flex justify-between items-center w-full">
                <!-- Enlaces alineados a la izquierda -->
                <div class="flex flex-col text-xs sm:text-sm">
                    <h2
                        class="font-semibold text-green-500 hover:underline text-nowrap"
                    >
                        <a :href="route('clients.create')">Create a Client ></a>
                    </h2>
                </div>

                <!-- Título seccion-->
                <div class="flex-grow text-center pr-14">
                    <h2 class="font-semibold text-xl text-text leading-tight">
                        Clients
                    </h2>
                </div>
            </div>
        </template>

        <div>
            <ClientsTable :data="clients" />
        </div>
    </AppLayout>
</template>
