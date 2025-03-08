<script>
export default {
    props: {
        loading: {
            type: Boolean,
            required: true,
        },
    },
    mounted() {
        setTimeout(() => console.log("ProcessingMessage montado"), 1000);
    },
};
</script>

<template>
    <div
        v-if="loading"
        class="absolute inset-0 bg-gray-200 opacity-60 flex items-center justify-center"
    >
        <span class="text-lg font-bold">Processing...</span>
    </div>
</template>
