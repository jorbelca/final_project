<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/_Default/ActionSection.vue";
import DangerButton from "@/Components/_Default/DangerButton.vue";
import DialogModal from "@/Components/_Default/DialogModal.vue";
import InputError from "@/Components/_Default/InputError.vue";
import SecondaryButton from "@/Components/_Default/SecondaryButton.vue";
import TextInput from "@/Components/_Default/TextInput.vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route("current-user.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title>
            <span class="text-text">Eliminar Cuenta</span>
        </template>

        <template #description>
            <span class="text-text"
                >Elimina tu cuenta de forma permanente.</span
            >
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-text">
                Una vez que tu cuenta sea eliminada, todos sus recursos y datos
                serán eliminados de forma permanente. Antes de eliminar tu
                cuenta, por favor descarga cualquier dato o información que
                desees conservar.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmUserDeletion">
                    Eliminar Cuenta
                </DangerButton>
            </div>

            <!-- Modal de Confirmación para Eliminar Cuenta -->
            <DialogModal :show="confirmingUserDeletion" @close="closeModal">
                <template #title>
                    <span class="text-text">Eliminar Cuenta</span>
                </template>

                <template #content>
                    ¿Estás seguro de que deseas eliminar tu cuenta? Una vez que
                    tu cuenta sea eliminada, todos sus recursos y datos serán
                    eliminados de forma permanente. Por favor, introduce tu
                    contraseña para confirmar que deseas eliminar tu cuenta de
                    forma permanente.

                    <div class="mt-4">
                        <TextInput
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-3/4 text-text"
                            placeholder="Contraseña"
                            autocomplete="current-password"
                            @keyup.enter="deleteUser"
                            id="password_delete_user"
                        />

                        <InputError
                            :message="form.errors.password"
                            class="mt-2 text-text"
                        />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal">
                        Cancelar
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                        id="btn_delete_user_final"
                    >
                        Eliminar Cuenta
                    </DangerButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>
