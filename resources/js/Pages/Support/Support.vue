<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";
import PageHeader from "@/Components/_Default/PageHeader.vue";
import {
    PlusIcon,
    TrashIcon,
    UserIcon,
    ClockIcon,
} from "@heroicons/vue/24/outline";
import StatusDot from "@/Components/UI/StatusDot.vue";

const props = defineProps({
    tickets: Array,
});

const form = useForm({
    question: "",
});

const formAnswers = ref({});

watchEffect(() => {
    if (props.tickets.length > 0) {
        props.tickets.forEach((ticket) => {
            if (!formAnswers.value[ticket.id]) {
                formAnswers.value[ticket.id] = useForm({
                    answer: "",
                });
            }
        });
    }
});

function submitAnswer(ticketId) {
    formAnswers.value[ticketId].put(`/support/${ticketId}`, {
        onSuccess: () => {
            formAnswers.value[ticketId].reset();
        },
    });
}

function deleteTicket(ticketId) {
    if (confirm("¿Estás seguro de que quieres eliminar este ticket?")) {
        router.delete(`/support/${ticketId}`);
    }
}

const getStatusBadge = (ticket) => {
    return ticket.answer ? "answered" : "pending";
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("es-ES", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>

<template>
    <AppLayout title="Soporte" :header="false">
        <template #header>
            <PageHeader
                title="Centro de Soporte"
                subtitle="Gestiona tus consultas y obtén ayuda"
            />
        </template>

        <div
            class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6"
        >
            <!-- Formulario para crear nuevo ticket -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
            >
                <div class="p-4 sm:p-6">
                    <form
                        @submit.prevent="
                            form.post('/support', {
                                onSuccess: () => {
                                    form.reset();
                                },
                            })
                        "
                        class="space-y-4"
                    >
                        <div class="flex-1 flex-col lg:flex-row gap-4">
                            <label
                                for="question"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2"
                            >
                                Describe tu consulta o problema
                            </label>
                            <div class="space-y-4">
                                <textarea
                                    id="question"
                                    v-model="form.question"
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 resize-none transition-colors"
                                    placeholder="Describe tu consulta o problema en detalle..."
                                    :class="{
                                        'border-red-500 focus:ring-red-500':
                                            form.errors.question,
                                    }"
                                ></textarea>

                                <div
                                    v-if="form.errors.question"
                                    class="text-sm text-red-600 dark:text-red-400"
                                >
                                    {{ form.errors.question }}
                                </div>

                                <div class="flex justify-end">
                                    <button
                                        type="submit"
                                        :disabled="
                                            form.processing ||
                                            !form.question.trim()
                                        "
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 dark:disabled:bg-gray-600 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                    >
                                        <PlusIcon class="h-4 w-4" />
                                        <span v-if="form.processing"
                                            >Enviando...</span
                                        >
                                        <span v-else>Enviar Ticket</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de tickets -->
            <div v-if="tickets.length > 0" class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2
                        class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                    >
                        Mis Tickets
                        <span
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                            >({{ tickets.length }})</span
                        >
                    </h2>
                </div>

                <div
                    v-for="ticket in tickets"
                    :key="ticket.id"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-200"
                >
                    <!-- Header del ticket -->
                    <div
                        class="px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600"
                    >
                        <div
                            class="flex flex-row sm:items-center justify-between sm:gap-4"
                        >
                            <div
                                class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 min-w-0"
                            >
                                <div class="flex items-center gap-2">
                                    <StatusDot
                                        :status="getStatusBadge(ticket)"
                                        class="w-3 h-3 rounded-full transition-colors"
                                    />
                                    <span
                                        class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100"
                                    >
                                        Ticket #{{ ticket.id }}
                                    </span>
                                    <!-- Punto de estado pulsante -->
                                    <div class="flex items-center gap-2"></div>
                                </div>

                                <div
                                    class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400"
                                >
                                    <ClockIcon class="h-4 w-4 flex-shrink-0" />
                                    <span class="truncate">{{
                                        formatDate(ticket.created_at)
                                    }}</span>
                                </div>
                            </div>

                            <button
                                @click="deleteTicket(ticket.id)"
                                class="self-end sm:self-auto p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors flex-shrink-0"
                                title="Eliminar ticket"
                            >
                                <TrashIcon class="h-5 w-5" />
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del ticket -->
                    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                        <!-- Pregunta -->
                        <div class="flex gap-3 sm:gap-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center"
                                >
                                    <img
                                        v-if="
                                            ticket?.questioner
                                                ?.profile_photo_path
                                        "
                                        :src="
                                            ticket?.questioner
                                                ?.profile_photo_path
                                        "
                                        :alt="ticket?.questioner?.name"
                                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover"
                                    />
                                    <UserIcon
                                        v-else
                                        class="h-4 w-4 sm:h-6 sm:w-6 text-blue-600 dark:text-blue-400"
                                    />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div
                                    class="flex flex-wrap items-center gap-2 mb-2"
                                >
                                    <span
                                        class="font-medium text-gray-900 dark:text-gray-100 text-sm sm:text-base"
                                    >
                                        {{ ticket?.questioner.name }}
                                    </span>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        ID: {{ ticket?.questioner_id }}
                                    </span>
                                    <span
                                        v-if="
                                            ticket?.questioner?.subscription
                                                ?.plan?.name
                                        "
                                        class="px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-full"
                                    >
                                        {{
                                            ticket?.questioner.subscription.plan
                                                .name
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 sm:p-4"
                                >
                                    <p
                                        class="text-gray-800 dark:text-gray-200 text-sm sm:text-base leading-relaxed break-words"
                                    >
                                        {{ ticket?.question }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Respuesta (si existe) -->
                        <div v-if="ticket?.answer" class="flex gap-3 sm:gap-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center"
                                >
                                    <span
                                        class="text-xs font-bold text-green-600 dark:text-green-400"
                                        >ADM</span
                                    >
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div
                                    class="flex flex-wrap items-center gap-2 mb-2"
                                >
                                    <span
                                        class="font-medium text-gray-900 dark:text-gray-100 text-sm sm:text-base"
                                    >
                                        {{ ticket.answerer.name }}
                                    </span>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        ID: {{ ticket.answerer_id }}
                                    </span>
                                    <span
                                        class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded-full"
                                    >
                                        Administrador
                                    </span>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        • {{ formatDate(ticket.updated_at) }}
                                    </span>
                                </div>
                                <div
                                    class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 sm:p-4"
                                >
                                    <p
                                        class="text-gray-800 dark:text-gray-200 text-sm sm:text-base leading-relaxed break-words"
                                    >
                                        {{ ticket.answer }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de respuesta (solo para admins) -->
                        <div
                            v-else-if="+$page?.props?.auth?.user?.admin === 1"
                            class="border-t border-gray-200 dark:border-gray-600 pt-4 sm:pt-6"
                        >
                            <div
                                class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4"
                            >
                                <h4
                                    class="font-medium text-gray-900 dark:text-gray-100 mb-4 text-sm sm:text-base"
                                >
                                    Responder como Administrador
                                </h4>
                                <form
                                    @submit.prevent="submitAnswer(ticket?.id)"
                                    class="space-y-4"
                                >
                                    <textarea
                                        v-model="formAnswers[ticket?.id].answer"
                                        rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 text-sm sm:text-base transition-colors"
                                        placeholder="Escribe tu respuesta..."
                                    ></textarea>

                                    <div class="flex justify-end">
                                        <button
                                            type="submit"
                                            :disabled="
                                                formAnswers[ticket?.id]
                                                    ?.processing ||
                                                !formAnswers[
                                                    ticket.id
                                                ]?.answer?.trim()
                                            "
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 dark:disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors duration-200 text-sm sm:text-base"
                                        >
                                            <span
                                                v-if="
                                                    formAnswers[ticket?.id]
                                                        ?.processing
                                                "
                                                >Respondiendo...</span
                                            >
                                            <span v-else>Responder</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Mejoras para dark mode */
@media (prefers-color-scheme: dark) {
    .bg-gray-750 {
        background-color: rgb(55, 65, 81);
    }
}

/* Mejoras responsive para texto */
@media (max-width: 640px) {
    .break-words {
        word-break: break-word;
        overflow-wrap: break-word;
    }
}
</style>
