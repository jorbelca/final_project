<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import PageHeader from "@/Components/_Default/PageHeader.vue";
import ProcessingMessage from "@/Components/UI/ProcessingMessage.vue";
import { start, stop } from "./speechRecognition";
import RecordBtn from "./recordBtn.vue";

import { onMounted } from "vue";

const props = defineProps({
    credits: Number,
    prompt: Object,
});

let isChrome = ref(false);
let firstTime = ref(false);
let loading = ref(false);
let transcriptionError = ref(false);
const form = useForm({
    additioNalPrompt: props.prompt?.prompt ?? "",
    prompt: "",
});

function generate() {
    loading.value = true;
    form.post(`/prompt`, {
        onSuccess: () => {
            togglePrompt();
            form.reset("prompt");
        },
        onFinish: () => {
            loading.value = false;
        },
        onError: (errors) => {
            loading.value = false;
            console.log(errors);
        },
    });
}

const isPromptExpanded = ref(false);
const togglePrompt = () => {
    isPromptExpanded.value = !isPromptExpanded.value;
};

const startRecording = async () => {
    try {
        // Check if browser supports required media APIs
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            throw new Error("Browser doesn't support media recording");
        }
        // Check if microphone permission is granted
        const microPermission = await navigator.permissions.query({name: 'microphone'}).then(permissionStatus => {
            if (permissionStatus.state === 'denied') {
                return false;
            } else {
                return true;
            }
        });
        if (!microPermission) {
            throw new Error("Microphone permission denied");
        }

        const response = await start();
        if (response) {
            form.prompt = response;
            loading.value = false;
        } else {
            loading.value = false;
            form.prompt = "";
            transcriptionError.value = true;
            setTimeout(() => {
                transcriptionError.value = false;
            }, 3000);
        }
    } catch (error) {
        console.error("Recording error:", error);
        loading.value = false;
        transcriptionError.value = true;
        setTimeout(() => {
            transcriptionError.value = false;
        }, 3000);
    }
};

const stopRecording = () => {
    try {
        loading.value = true;
        form.prompt = "";
        stop();
    } catch (error) {
        console.error("Error stopping recording:", error);
        loading.value = false;
    }
};

onMounted(() => {
    const alreadyShown = localStorage.getItem("voicePromptNoticeShown");

    if (!alreadyShown) {
        firstTime.value = true;
        localStorage.setItem("voicePromptNoticeShown", "true");
    }
    if (navigator.userAgent.includes("Chrome")) {
        isChrome.value = true;
    } else {
        isChrome.value = false;
    }
    console.log("isChrome", isChrome.value);
});

const setMicrophonePermission = async (event) => {
    const permission = event === true;
    if (permission) {
        try {
            const status = await navigator.permissions.query({ name: "microphone" });
            if (status.state === "granted") {
                await navigator.mediaDevices.getUserMedia({ audio: true });
                console.log("Microphone permission granted");
            } else if (status.state === "prompt") {
                console.log("Microphone permission needs to be granted by the user");
            } else {
                console.log("Microphone permission denied");
            }
        } catch (error) {
            console.error("Error checking microphone permission:", error);
        }
    } else {
        console.log("Microphone permission denied");
    }
};

</script>

<template>
    <ProcessingMessage :loading="loading" />
    <AppLayout title="Asistente IA" :header="false">
        <template #header>
            <PageHeader title="Generar mediante IA " :padding="16" />
        </template>

        <div class="max-w-7xl mx-auto py-3 px-2 sm:px-3 lg:px-4">
            <div
                class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-2 mb-3 shadow-md inline-flex items-center"
            >
                <div class="p-1 bg-white bg-opacity-30 rounded-full mr-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <div>
                    <p
                        class="text-xs text-white font-medium border-b border-gray-300 dark:border-gray-600 pb-1"
                    >
                        Créditos:
                        <span class="text-sm font-bold">{{
                            props.credits ?? 0
                        }}</span>
                    </p>
                </div>
            </div>
            <!-- Mensaje de error si no hay créditos -->
            <small v-if="props.credits == 0" class="text-red-600">
                No tienes creditos dirigete a la pantalla de
                <a href="user/profile"><u>Perfil</u></a></small
            >

            <!-- Sección de prompt adicional (desplegable) -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-3 overflow-hidden border dark:border-gray-600"
            >
                <div
                    @click="togglePrompt"
                    class="p-2 border-b border-gray-200 dark:border-gray-700 cursor-pointer flex justify-between items-center"
                >
                    <h3
                        class="text-base font-bold text-gray-900 dark:text-white flex items-center"
                    >
                        Prompt Adicional
                    </h3>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 transition-transform duration-300 dark:text-white"
                        :class="isPromptExpanded ? 'rotate-180' : ''"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <div
                    v-show="isPromptExpanded"
                    class="p-2 transition-all duration-300"
                >
                    <div>
                        <textarea
                            v-model="form.additioNalPrompt"
                            rows="3"
                            class="w-full px-2 py-1 text-gray-700 border rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"
                            placeholder="Introduce detalles adicionales sobre tu actividad para dar mayor contexto a la IA y asi mejorar la calidad de la respuesta."
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Sección de generación de presupuesto -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border dark:border-gray-600"
            >
                <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                    <h3
                        class="text-base font-bold text-gray-900 dark:text-white flex items-center"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1 text-green-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Generar Presupuesto
                    </h3>
                </div>
                <div class="p-2">
                    <form
                        class="flex flex-col gap-2"
                        @submit.prevent="generate"
                    >
                        <div>
                            <textarea
                                v-model="form.prompt"
                                rows="5"
                                class="w-full px-2 py-1 text-gray-700 border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"
                                placeholder="Que te gustaría presupuestar?"
                            ></textarea>
                            <p
                                v-if="form.errors.question"
                                class="mt-1 text-xs text-red-600"
                            >
                                {{ form.errors.question }}
                            </p>
                        </div>

                        <div
                            class="text-right inline-flex items-end justify-between gap-2"
                        >
                             <div class="w-3/6 flex">
                                <RecordBtn
                                    @startRecording="startRecording"
                                    @stop="stopRecording"
                                    @touchstart="setMicrophonePermission(true)"
                               
                                />
                                <span
                                    v-if="transcriptionError"
                                    class="text-xs text-red-600 flex items-end"
                                >
                                    {{ "Error in transcription" }}
                                </span>
                            </div>

                            <button
                                type="submit"
                                :disabled="
                                    form.prompt === '' ||
                                    props.credits <= 0 ||
                                    form.additioNalPrompt === ''
                                "
                                class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md text-sm text-white hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200 ease-in-out disabled:opacity-50"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 mr-1"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Generar Presupuesto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>




        <div
            v-if="firstTime === true"
            class="mx-5 my-4 p-3 bg-yellow-50 dark:bg-gray-600 border-l-4 border-yellow-400 dark:border-yellow-600 rounded-md shadow-sm"
        >
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-yellow-500"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <strong>Nota:</strong> El reconocimiento de voz requiere
                        descargar aproximadamente 100MB de datos. Se recomienda
                        usar una conexión Wi-Fi o una buena conexión de datos
                        móviles.
                    </p>
                </div>
                <div class="ml-auto">
                    <button
                        @click="firstTime = false"
                        class="text-yellow-500 hover:text-yellow-600"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
