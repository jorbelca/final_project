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
        class="absolute inset-0 bg-gray-200 opacity-50 flex items-center justify-center"
    >
        <span class="text-lg font-bold">Processing...</span>
    </div>
</template>
