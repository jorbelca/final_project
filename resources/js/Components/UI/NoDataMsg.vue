<script>
export default {
    props: {
        noData: {
            type: Boolean,
            required: true,
        },
    },
};
</script>
<template>
    <div v-if="noData" class="empty-message text-center">No data .</div>
</template>
