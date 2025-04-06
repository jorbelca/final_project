<script>
export default {
    props: {
        loading: {
            type: Boolean,
            required: true,
        },
    },
};
</script>

<template>
    <div
        v-if="loading"
        class="absolute inset-0 bg-gray-200 opacity-60 flex items-center justify-center h-full w-full z-50 overlay"
    >
        <span class="text-lg font-bold">Procesando...</span>
    </div>
</template>
<style scoped>
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}
</style>
