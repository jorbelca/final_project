<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";

const props = defineProps({
    credits: Number,
    prompt: Text,
});

const form = useForm({
    prompt: "",
});




function updatePrompt(promptId) {
    form.put(`/prompt/${promptId}`, {});
}
</script>

<template>
    <AppLayout title="Support">
        <template #header>
            <div class="flex align-center justify-center gap-5 items-end">
                <h2 class="font-semibold text-xl text-text leading-tight">
                    Soporte
                </h2>
            </div>
        </template>
        <div class="overflow-x-auto p-2 m-3 bg-white dark:bg-hover rounded-lg">
            <h3 class="font-black text-text">Crear un nuevo ticket</h3>
            <div
                class="overflow-x-auto p-2 m-3 bg-white dark:bg-hover rounded-lg flex flex-row justify-between"
            >
                <form
                    class="flex flex-row w-full gap-3 items-end"
                    @submit.prevent="form.post('/support')"
                >
                    <textarea
                        v-model="form.question"
                        class="w-full rounded-lg dark:bg-slate-400 dark:border-slate-200 dark:placeholder:text-white"
                        placeholder="Mensaje"
                    >
                    </textarea>
                    <div v-if="form.errors.question">
                        {{ form.errors.question }}
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-green-400 hover:bg-green-500 text-white p-2 w-10px max-h-10 text-center"
                    >
                        Enviar
                    </button>
                </form>
            </div>
        </div>

        <div
            class="overflow-x-auto p-2 m-3 rounded-lg flex flex-col border border-gray-300 dark:border-gray-400 bg-violet-100 dark:bg-blue-900 text-text"
            :if="tickets.length > 0"
            v-for="ticket in props.tickets"
            :key="ticket.id"
        >
            <div class="flex justify-between px-4">
                <span class="font-black">Ticket {{ ticket.id }}</span>
                <button
                    class="justify-self-end text-red-500 font-bold"
                    @click="deleteTicket(ticket.id)"
                >
                    X
                </button>
            </div>
            <div
                class="grid grid-cols-6 gap-4 border py-2 border-gray-300 dark:border-gray-400 rounded-lg"
            >
                <div class="col-span-2 min-w-[130px] py-2 pl-4 gap-2">
                    <img
                        v-if="ticket.questioner.profile_photo_path"
                        :src="ticket.questioner.profile_photo_path"
                        alt=""
                        width="30"
                    />
                    <h3 class="font-normal">
                        <span> {{ ticket.questioner.name }}</span
                        >, ID :
                        <span>{{ ticket.questioner_id }}</span>
                    </h3>
                    <small class="text-[9px]">
                        {{
                            new Date(ticket.created_at).toLocaleDateString(
                                "es-ES",
                                {
                                    year: "numeric",
                                    month: "numeric",
                                    day: "numeric",
                                    hour: "numeric",
                                    minute: "numeric",
                                }
                            )
                        }}</small
                    >
                </div>
                <div
                    class="col-span-4 flex flex-nowrap flex-col justify-center"
                >
                    <p class="font-bold text-blue-500 dark:text-blue-200">
                        Pregunta : &nbsp;<span
                            class="font-normal text-text text-justify"
                            >{{ ticket.question }}</span
                        >
                    </p>
                </div>
            </div>
            <div
                class="grid grid-cols-6 gap-4 py-2 border border-gray-300 dark:border-gray-400 rounded-lg"
                v-if="ticket.answer !== null"
            >
                <div class="col-span-2 min-w-[130px] pl-4">
                    <small class="font-bold text-red-600 text-xs"
                        >(Admin)</small
                    >
                    <h3 class="font-normal">
                        <span> {{ ticket.answerer.name }}</span
                        >, ID :
                        <span>{{ ticket.answerer_id }}</span>
                    </h3>
                    <small class="text-[9px]">
                        {{
                            new Date(ticket.updated_at).toLocaleDateString(
                                "es-ES",
                                {
                                    year: "numeric",
                                    month: "numeric",
                                    day: "numeric",
                                    hour: "numeric",
                                    minute: "numeric",
                                }
                            )
                        }}</small
                    >
                </div>
                <div class="col-span-4 flex flex-col justify-center">
                    <p class="font-bold text-green-500 dark:text-green-400">
                        Respuesta : &nbsp;
                        <span class="font-normal text-text">{{
                            ticket.answer
                        }}</span>
                    </p>
                </div>
            </div>

            <div
                class="flex justify-around py-2"
                v-else-if="
                    +$page.props.auth.user?.admin === 1 &&
                    ticket.answer === null
                "
            >
                <div
                    class="overflow-x-auto p-2 m-5 rounded-lg flex flex-row justify-between w-full bg-violet-200"
                >
                    <form
                        class="flex w-full gap-3"
                        @submit.prevent="submitAnswer(ticket.id)"
                    >
                        <textarea
                            v-model="formAnswers[ticket.id].answer"
                            class="w-full rounded-lg"
                            placeholder="Respuesta"
                        >
                        </textarea>
                        <div v-if="form.errors.question">
                            {{ form.errors.question }}
                        </div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-blue-400 hover:bg-blue-500 text-white p-1 w-10px"
                        >
                            Responder
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="pb-10"></div>
    </AppLayout>
</template>
