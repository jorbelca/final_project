<script setup>
import { Link, router, useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    user: Object,
    subscription: Object,
    plans: Array,
});

const form = useForm({
    _method: "PUT",
    default_taxes: props.user.default_taxes,
    company_name: props.user.company_name,
});
</script>

<template>
    <FormSection>
        <template #title>
            <span class="text-text">Informacion adicional</span>
        </template>

        <template #description>
            <span class="text-text"
                >Esta informacion la utilizamos para establecer ciertos valores
                por defecto</span
            >
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6 flex flex-col gap-4">
                <!-- default_taxes and company_name -->
                <div class="w-full flex flex-col sm:flex-row gap-4">
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel
                            for="default_taxes"
                            value="IVA por defecto"
                        />
                        <TextInput
                            id="default_taxes"
                            v-model="form.default_taxes"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autocomplete="IVA por defecto"
                        />
                        <InputError
                            :message="form.errors.default_taxes"
                            class="mt-2"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel
                            for="company_name"
                            value="Nombre de la Empresa (PDF)"
                        />
                        <TextInput
                            id="company_name"
                            v-model="form.company_name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autocomplete="empresa"
                        />
                        <InputError
                            :message="form.errors.company_name"
                            class="mt-2"
                        />
                    </div>
                </div>

                <!-- Subscriptions -->
                <div class="col-span-6">
                    <h5>Subscripcion</h5>
                    <InputLabel for="plan" value="Planes" />
                    <!-- Tiles for plans -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div
                            v-for="plan in props.plans"
                            :key="plan.id"
                            class="bg-white border border-gray-200 dark:bg-hover rounded-lg shadow-sm p-4 flex flex-col justify-between text-center"
                        >
                            <div>
                                <h5 class="font-semibold text-text">
                                    {{ plan.name }}
                                </h5>
                                <p class="text-text">
                                    {{ plan.description }}
                                </p>
                                <p class="font-thin text-text">
                                    {{ plan.price }} €
                                </p>
                            </div>
                            <div class="mt-4">
                                <input
                                    type="radio"
                                    :value="plan.id"
                                    v-model="form.plan"
                                    name="plan"
                                    id="plan_{{ plan.id }}"
                                />
                                <label
                                    :for="'plan_' + plan.id"
                                    class="ml-2 text-text cursor-pointer"
                                    >Seleccionar</label
                                >
                            </div>
                        </div>
                    </div>
                    <InputError :message="form.errors.plan" class="mt-2" />
                </div>
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Guardado.
            </ActionMessage>

            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Guardar
            </PrimaryButton>
        </template>
    </FormSection>
</template>
