<script setup>
defineProps({
    client: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div v-if="client">
        <div
            class="flex flex-col items-center border border-gray-100
             dark:border-gray-600 rounded-lg p-1 md:p-0 md:py-1"
        >
            <img
                :src="client.image_url"
                alt="Client Image"
                class="rounded-image"
            />
            <small>{{ client.name }}</small>
        </div>
    </div>
</template>
