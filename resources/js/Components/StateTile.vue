<script>
import { ref } from "vue";

export default {
    props: {
        status: String,
    },
    setup(props) {
        const statusState = ref(props.status);

        return { statusState };
    },
};
</script>
<template>
    <div
        class="status-tile px-2 py-1 rounded-full"
        :class="{
            'bg-gray-100 text-gray-500': statusState === 'draft',
            'bg-green-500 text-white': statusState === 'approved',
            'bg-red-500 text-white': statusState === 'rejected',
        }"
    >
        <template v-if="statusState === 'draft'"
            ><span>🕗</span>
            <button className="text-xs/[5px] cursor-default">
                <div className=" flex flex-row ml-1 w-4 text-gray-500">
                    Draft
                </div>
            </button>
        </template>

        <template v-if="statusState === 'approved'"
            >✔

            <button className="text-xs/[5px] cursor-default">
                <div className="ml-1 w-4 text-white">Approved</div>
            </button>
        </template>

        <template v-if="statusState === 'rejected'"
            >X

            <button className="text-xs/[5px] cursor-default">
                <div className="ml-1 w-4 text-white">Rejected</div>
            </button>
        </template>
    </div>
</template>

<style>
.status-tile {
    display: block; /* El contenedor se ajustará al tamaño del contenido */

    min-width: 6rem; /* Mínimo espacio para etiquetas pequeñas */
    max-width: 12rem;
    font-size: 0.875rem; /* Tamaño del texto adaptable */
}
</style>
