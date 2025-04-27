<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { defineAsyncComponent, ref, onMounted } from "vue";
import PageHeader from "@/Components/_Default/PageHeader.vue";
import ProcessingMessage from "@/Components/UI/ProcessingMessage.vue";

import { start, stop } from "./speechRecognition.js";
const RecordBtn = defineAsyncComponent(() =>
    import("./recordBtn.vue").then((module) => module.default)
);
const CreditsTile = defineAsyncComponent(() =>
    import("./creditsTile.vue").then((module) => module.default)
);

const props = defineProps({
    credits: Number,
    prompt: Object,
});

let firstTime = ref(false);
let loading = ref(false);
let transcriptionError = ref(false);
let noMedia = ref(false);
const transcriptionErrorMessage = ref(
    "Error al transcribir el audio. Por favor, intenta nuevamente."
);
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

const resetTranscriptionError = () => {
    loading.value = false;
    transcriptionError.value = true;
    setTimeout(() => {
        transcriptionError.value = false;
    }, 3000);
};

const startRecording = async () => {
    try {
        // Check if browser supports required media APIs
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            transcriptionErrorMessage.value =
                "Error: No se puede acceder al micrófono. Asegúrate de que el navegador tenga permisos para usarlo.";
            throw new Error("Browser doesn't support media recording");
        }

        const { transcription } = await start();

        if (transcription) {
            form.prompt = transcription;
            loading.value = false;
        } else {
            form.prompt = "";
            resetTranscriptionError();
        }
    } catch (error) {
        console.error("Recording error:", error);
        transcriptionErrorMessage.value = error.message;
        return resetTranscriptionError();
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

    //Comprobar si el navegador tiene permisos para usar el micrófono
    navigator.permissions
        .query({ name: "microphone" })
        .then((permissionStatus) => {
            if (permissionStatus.state === "denied") {
                transcriptionError.value = true;
                transcriptionErrorMessage.value =
                    "Error: Permiso del micro denegado.";
                noMedia.value = true;
            }
        })
        .catch(() => {
            transcriptionError.value = true;
            transcriptionErrorMessage.value =
                "Error: No se pudo comprobar el permiso.";
            noMedia.value = true;
        });
});


</script>

<template>
    <ProcessingMessage :loading="loading" />
    <AppLayout title="Asistente IA" :header="false">
        <template #header>
            <PageHeader title="Generar mediante IA " :padding="16" />
        </template>

        <div class="max-w-7xl mx-auto py-3 px-2 sm:px-3 lg:px-4">
            <CreditsTile :credits="props.credits" />

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
                            rows="7"
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
                                    :disabled="noMedia"
                                    @startRecording="startRecording"
                                    @stop="stopRecording"
                                />
                                <span
                                    v-if="transcriptionError"
                                    class="text-xs text-red-600 flex items-end text-wrap"
                                >
                                    {{ transcriptionErrorMessage }}
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
            v-if="firstTime"
            class="fixed bottom-10 left-1/2 transform -translate-x-1/2 p-3 max-w-3xl w-11/12 bg-yellow-50/90 dark:bg-gray-600/90 border-l-4 border-yellow-400 dark:border-yellow-600 rounded-md shadow-sm z-10"
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
