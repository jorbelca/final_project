<script>
import {
    ArchiveBoxArrowDownIcon,
    DocumentDuplicateIcon,
    PencilSquareIcon,
    PrinterIcon,
    TrashIcon,
} from "@heroicons/vue/24/solid";
import { ref } from "vue";

export default {
    components: {
        ArchiveBoxArrowDownIcon,
        DocumentDuplicateIcon,
        PencilSquareIcon,
        PrinterIcon,
        TrashIcon,
    },
    props: {
        budgetId: {
            type: String,
            required: true,
        },
    },
    emits: ["edit", "delete", "generate", "clone", "download"],
    setup() {
        const isOpen = ref(false);
        const toggleDropdown = () => {
            isOpen.value = !isOpen.value;
        };
        return { isOpen, toggleDropdown };
    },
};
</script>

<style>
.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown button {
    pointer-events: cursor;
}

.dropdown-content {
    position: absolute;

    background-color: #f9f9f9;
    min-width: 100px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    opacity: 0;
    transform: scaleY(0);
    transform-origin: top;
    transition: opacity 0.3s ease, transform 0.3s ease;

    border-radius: 0.5rem;
    padding: 0.5rem 0;
}

.dropdown-content.open {
    opacity: 1;
    transform: translateX(-40px);
    pointer-events: auto; /* Enable interaction when open */
}
</style>

<template>
    <div class="dropdown">
        <button class="btn primary" @click="toggleDropdown">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 transition-transform duration-300 dark:text-white"
                :class="isOpen ? 'rotate-180' : ''"
                viewBox="0 0 20 20"
                fill="currentColor"
            >
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                />
            </svg>
        </button>
        <div
            class="dropdown-content dark:bg-gray-800"
            :class="{ open: isOpen }"
        >
            <button
                class="w-full flex items-center justify-center gap-1"
                @click.prevent="$emit('edit', budgetId)"
                title="Edit Budget"
            >
                <PencilSquareIcon class="icon-edit m-1" />
                Editar
            </button>
            <button
                class="btn danger w-full flex items-center justify-center gap-1"
                @click.prevent="$emit('delete', budgetId)"
                title="Delete Budget"
            >
                <TrashIcon class="icon-delete m-1" /> Borrar
            </button>
            <button
                class="btn secondary w-full flex items-center justify-center gap-1"
                @click.prevent="$emit('generate', budgetId)"
                title="Generate PDF"
            >
                <PrinterIcon class="size-5 text-blue-500 m-1" /> PDF
            </button>
            <button
                class="btn secondary w-full flex items-center justify-center gap-1"
                @click.prevent="$emit('clone', budgetId)"
                title="Duplicate Budget"
            >
                <DocumentDuplicateIcon
                    class="size-5 text-gray-500 dark:text-gray-400 m-1"
                />
                Duplicar
            </button>
            <button
                class="btn secondary w-full flex items-center justify-center gap-1"
                @click.prevent="$emit('download', budgetId)"
                title="Download"
            >
                <ArchiveBoxArrowDownIcon
                    class="size-5 text-green-500 dark:text-green-500 m-1"
                />
                Descargar
            </button>
        </div>
    </div>
</template>
