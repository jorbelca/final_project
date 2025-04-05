<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import PageHeader from "@/Components/_Default/PageHeader.vue";
import ProcessingMessage from "@/Components/UI/ProcessingMessage.vue";
const props = defineProps({
    credits: Number,
    prompt: Text,
});
let loading = ref(false);
const form = useForm({
    additioNalPrompt: props.prompt.prompt,
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
</script>

<template>
    <ProcessingMessage :loading="loading" />
    <AppLayout title="Generar con IA" :header="false">
        <template #header>
            <PageHeader title="Generador de Presupuestos IA" padding="16" />
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
                            props.credits
                        }}</span>
                    </p>
                </div>
            </div>
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
                        <div class="text-right">
                            <button
                                type="submit"
                                :disabled="form.prompt === ''"
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
    </AppLayout>
</template>
