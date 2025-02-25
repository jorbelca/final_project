<script setup>
import { EnvelopeIcon } from "@heroicons/vue/24/solid";
</script>

<template #footer>
    <footer>
        <div class="relative bottom-0 bg-gray-500 text-white py-4 min-w-full">
            <div class="row flex justify-center align-middle">
                <div class="col-md-6 flex flex-row items-center gap-3">
                    Budget App
                    <p>&copy; 2025</p>

                    <a
                        href="mailto:jorbelcal@alumno.edu.gva.es?subject=SoporteBudgetApp"
                    >
                        <EnvelopeIcon class="size-5" />
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>
