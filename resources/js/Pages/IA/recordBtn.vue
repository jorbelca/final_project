<script setup>
import { ref } from "vue";

defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["startRecording", "stop"]);

const isRecording = ref(false);

const toggleRecording = () => {
    if (isRecording.value) {
        // Stop recording
        isRecording.value = false;
        emit("stop");
    } else {
        // Start recording
        isRecording.value = true;
        emit("startRecording");
    }
};
</script>

<template>
    <button
        :disabled="disabled"
        type="button"
        @click="toggleRecording"
        class="align-self-bottom relative flex items-center justify-center h-12 w-12 border border-transparent rounded-full text-sm text-white transition-colors duration-200 ease-in-out disabled:opacity-50 group min-w-12"
        :class="{
            'bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-800 active:bg-blue-800 dark:active:bg-blue-900 focus:ring-blue-500 dark:focus:ring-blue-400':
                !isRecording,
            'bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-800 active:bg-red-800 dark:active:bg-red-900 focus:ring-red-500 dark:focus:ring-red-400 recording-active':
                isRecording,
            'focus:outline-none focus:ring-2': true,
        }"
    >
        <!-- Microphone Icon -->
        <svg
            v-if="!isRecording"
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-white"
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

        <!-- Square Icon (Stop) -->
        <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-white mic-recording"
            viewBox="0 0 24 24"
            fill="currentColor"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <rect x="6" y="6" width="12" height="12"></rect>
        </svg>

        <span
            v-if="!isRecording"
            class="absolute -top-2 -right-5 bg-yellow-400 text-xs font-bold text-text px-1.5 py-0.5 rounded-full"
            >BETA</span
        >
    </button>
</template>
<style scoped>
@keyframes recording-pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); /* Red pulse */
        transform: scale(1);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(220, 38, 38, 0);
        transform: scale(1.05);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
        transform: scale(1);
    }
}

.recording-active {
    animation: recording-pulse 1.5s infinite;
}
.recording-active {
    animation: recording-pulse 1.5s infinite;
    background-color: rgb(
        220,
        38,
        38
    ) !important; /* Red background while recording */
}

.mic-recording {
    animation: mic-bounce 0.8s ease infinite alternate;
}

@keyframes mic-bounce {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(1.2);
    }
}
</style>
