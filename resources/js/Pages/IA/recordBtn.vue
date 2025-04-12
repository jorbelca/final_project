<script setup>
import { ref } from "vue";

const emit = defineEmits(["startRecording", "stop"]);

const isRecording = ref(false);

const handleStart = () => {
    isRecording.value = true;
    emit("startRecording");
};

const handleStop = () => {
    if (isRecording.value) {
        isRecording.value = false;
        emit("stop");
    }
};
</script>

<template>
    <button
        type="button"
        @mousedown="handleStart"
        @mouseup="handleStop"
        @mouseleave="handleStop"
        @touchstart.prevent="handleStart"
        @touchend.prevent="handleStop"
        @touchcancel.prevent="handleStop"
        class="align-self-bottom relative flex items-center justify-center h-12 w-12 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-full text-sm text-white hover:bg-blue-700 dark:hover:bg-blue-800 active:bg-blue-800 dark:active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors duration-200 ease-in-out disabled:opacity-50 group"
        :class="{ 'animate-pulse': isRecording }"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-white transition-transform duration-300"
            :class="{ 'scale-110': isRecording }"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path
                d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"
            ></path>
            <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
            <line x1="12" y1="19" x2="12" y2="23"></line>
            <line x1="8" y1="23" x2="16" y2="23"></line>
        </svg>

        <span
            class="absolute -top-2 -right-5 bg-yellow-400 text-xs font-bold text-text px-1.5 py-0.5 rounded-full"
            >BETA</span
        >
    </button>

 </template>
<style scoped>
@keyframes recording-pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
        transform: scale(1);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
        transform: scale(1.05);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
        transform: scale(1);
    }
}

.recording-active {
    animation: recording-pulse 1.5s infinite;
    background-color: rgb(220, 38, 38) !important; /* Red background while recording */
}

.mic-recording {
    animation: mic-bounce 0.8s ease infinite alternate;
}

@keyframes mic-bounce {
    0% { transform: scale(1); }
    100% { transform: scale(1.2); }
}
</style>
