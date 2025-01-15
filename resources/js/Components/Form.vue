<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { onMounted } from "vue";

const props = defineProps({
    title: String,
});

const formData = {};
onMounted(() => {
    console.log(props.title);
});
</script>

<template>
    <AppLayout title="{title}">
        <template #header>
            <div class="flex align-center gap-5">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create a {{ props.title }}
                </h2>
                <h2 class="font-semibold text-l text-green-500">
                    <a :href="route('budgets.index')"
                        >List of {{ props.title }}</a
                    >
                </h2>
            </div>
        </template>
        <main>
            <form class="flex flex-col gap-4 p-7">
                <InputLabel>Client Id</InputLabel>
                <TextInput></TextInput>

                <InputLabel></InputLabel>
                <TextInput></TextInput>

                <button type="submit">Crear</button>
            </form>
        </main>
    </AppLayout>
</template>
