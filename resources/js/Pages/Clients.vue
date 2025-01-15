<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    clients: Object,
});
console.log(props.clients);
</script>

<template>
    <AppLayout title="Clients">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Clients
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <ul>
                        <li v-for="client in clients" :key="client.id">
                            {{ client.id }}. {{ client.name }} //
                            {{ client.email }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
