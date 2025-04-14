<script setup>
defineProps({
    message: {
        type: String,
        default: "",
    },
    style: {
        type: String,
        default: "success",
    },
});
</script>

<template>
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
        @click.self="$emit('close')"
    >
        <div
            class="bg-white rounded-lg shadow-lg p-4 max-w-sm mx-auto"
            @click.stop
        >
            <button class="text-red">X</button>
            <div class="flex items-center">
                <div class="ms-3 flex-grow">
                    <p
                        class="font-medium text-sm text-gray-800 break-words whitespace-normal"
                    >
                        {{ message }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 10px;
    background: none;
    border: none;
    cursor: pointer;
    font-weight: bold;
    color: #666;
}

.close-button:hover {
    color: #000;
}
</style>

<script>
export default {
    emits: ['close']
}
</script>