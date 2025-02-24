<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";

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
    router.delete(`/support/${ticketId}`);
}
</script>

<template>
    <AppLayout title="Support">
        <template #header>
            <div class="flex align-center justify-center gap-5 items-end">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Support
                </h2>
            </div>
        </template>
        <div class="overflow-x-auto p-2 m-3 bg-white rounded-lg">
            <h3 class="font-black">Create a new ticket</h3>
            <div
                class="overflow-x-auto p-2 m-3 bg-white rounded-lg flex flex-row justify-between"
            >
                <form
                    class="flex flex-row w-full gap-3 items-end"
                    @submit.prevent="form.post('/support')"
                >
                    <textarea
                        v-model="form.question"
                        class="w-full rounded-lg"
                        placeholder="Message"
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
                        Send
                    </button>
                </form>
            </div>
        </div>

        <div
            class="overflow-x-auto p-2 m-3 rounded-lg flex flex-col border border-grey-600 bg-violet-100"
            :if="tickets.length > 0"
            v-for="ticket in props.tickets"
            :key="ticket.id"
        >
            <div class="flex justify-between px-4">
                <span class="font-black">Ticket {{ ticket.id }}</span>
                <button
                    class="justify-self-end text-red-600 font-black"
                    @click="deleteTicket(ticket.id)"
                >
                    X
                </button>
            </div>
            <div
                class="flex justify-around py-2 border border-grey-400 rounded-lg"
            >
                <div
                    class="min-w-[130px] py-2 flex flew-col items-center gap-2"
                >
                    <img
                        v-if="ticket.questioner.profile_photo_path"
                        :src="ticket.questioner.profile_photo_path"
                        alt=""
                        width="30"
                    />
                    <h3 class="font-bold">
                        <span> {{ ticket.questioner.name }}</span
                        >, ID :
                        <span>{{ ticket.questioner_id }}</span>
                    </h3>
                </div>
                <div
                    class="px-4 flex flex-nowrap flex-col justify-center items-center min-w-[170px]"
                >
                    <p class="font-bold text-blue-500">
                        Question : &nbsp;<span class="font-light text-black">{{
                            ticket.question
                        }}</span>
                    </p>
                </div>
                <small class="w-1/8 align-self-end text-xs flex items-end">
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
                class="flex justify-around py-2 border border-grey-400"
                v-if="ticket.answer !== null"
            >
                <div class="min-w-[130px] pl-2">
                    <small class="font-bold text-red-800 text-xs"
                        >(Admin)</small
                    >
                    <h3 class="font-bold">
                        <span> {{ ticket.answerer.name }}</span
                        >, ID :
                        <span>{{ ticket.answerer_id }}</span>
                    </h3>
                </div>
                <div
                    class="flex flex-nowrap flex-col justify-center items-center min-w-[170px]"
                >
                    <p class="font-bold text-green-500">
                        Answer : &nbsp;<span class="font-light text-black">{{
                            ticket.answer
                        }}</span>
                    </p>
                </div>

                <small class="w-1/8 align-self-end text-xs flex items-end">
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
                            placeholder="Response"
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
                            Reply
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="pb-10"></div>
    </AppLayout>
</template>
