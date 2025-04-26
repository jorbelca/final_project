<script setup>
import { EnvelopeIcon } from "@heroicons/vue/24/solid";
</script>

<template #footer>
    <footer>
        <div
            class="relative bottom-0 bg-gray-500 text-white min-h-[45px] min-w-full row flex justify-center align-middle"
        >
            <div class="col-md-6 flex flex-row items-center gap-3">
                <img src="/favicon.ico" alt="Favicon" class="w-5 h-5" />
                <p>Budget App</p>
                -

                <a
                    href="mailto:jorbelcal@alumno.edu.gva.es?subject=SoporteBudgetApp"
                >
                    Contacto
                </a>
            </div>
        </div>
    </footer>
</template>
