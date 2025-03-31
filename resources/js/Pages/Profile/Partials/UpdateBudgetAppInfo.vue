<script setup>
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import PlansTiles from "./PlansTiles.vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/es";

// Initialize the relativeTime plugin
dayjs.extend(relativeTime);
dayjs.locale("es");

const props = defineProps({
    user: Object,
    subscription: Object,
    plans: Array,
});

const form = useForm({
    default_taxes: props.user.default_taxes,
    company_name: props.user.company_name,
    subscription: props.subscription,
});

const updateSubscription = () => {
    form.put(route("subscription.update", props.subscription), {
        preserveScroll: true,
        onError: (errors) => {
            console.error(errors);
        },
    });
};
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
                            type="number"
                            class="mt-1 block w-full"
                            required
                            min="0"
                            max="99"
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
                    <InputLabel for="subscription" value="Subscripción" />

                    <div
                        class="bg-white dark:bg-hover border border-gray-200 rounded-lg p-4 my-3"
                    >
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="flex flex-col items-center">
                                <h6
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Créditos restantes
                                </h6>
                                <p class="text-xl font-bold text-text">
                                    {{ props.subscription.credits }}
                                </p>
                            </div>
                            <div class="flex flex-col items-center">
                                <h6
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Finaliza en
                                </h6>
                                <p class="text-text">
                                    {{
                                        props.subscription &&
                                        props.subscription.ends_at
                                            ? dayjs(
                                                  props.subscription.ends_at
                                              ).diff(dayjs(), "day") + " días"
                                            : "-"
                                    }}
                                </p>
                            </div>
                            <div class="flex flex-col items-center">
                                <h6
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Número de tarjeta
                                </h6>
                                <input
                                    type="text"
                                    class="text-text border border-gray-300 dark:border-gray-300 rounded-md text-center w-full max-w-[200px] text-xs font-bold bg-transparent focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                                    v-model="form.subscription.payment_number"
                                    maxlength="19"
                                    @input="
                                        (e) => {
                                            const start =
                                                e.target.selectionStart;
                                            const raw = e.target.value.replace(
                                                /[^\d]/g,
                                                ''
                                            );
                                            let formatted = '';
                                            for (
                                                let i = 0;
                                                i < raw.length && i < 16;
                                                i++
                                            ) {
                                                if (i > 0 && i % 4 === 0)
                                                    formatted += '-';
                                                formatted += raw[i];
                                            }
                                            form.subscription.payment_number =
                                                formatted || '0';
                                            e.target.value = formatted;
                                            e.target.setSelectionRange(
                                                start,
                                                start
                                            );
                                        }
                                    "
                                />
                            </div>
                        </div>
                    </div>

                    <InputLabel for="plan" value="Planes" />
                    <PlansTiles
                        :plans="props.plans"
                        :planSelected="props.subscription.plan_id"
                        @update:planSelected="
                            (planId) => (form.subscription.plan_id = planId)
                        "
                    />
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
                @click="updateSubscription"
            >
                Guardar
            </PrimaryButton>
        </template>
    </FormSection>
</template>
