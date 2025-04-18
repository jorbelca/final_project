<script setup>
import { defineProps } from "vue";

const props = defineProps({
    landing: Boolean,
});
</script>

<template>
    <div
        :class="[
            'relative mt-10 sm:mt-0 flex justify-center mr-10',
            landing
                ? 'max-w-[1000px] p-6 ' // Landing
                : 'p-10 max-w-[500px]', // Default
        ]"
    >
        <img
            src="/capturas/new_app.png"
            alt="Example App"
            :class="[
                'rounded-lg shadow-2xl border border-gray-300 dark:border-gray-700 hover:shadow-4xl',
                landing
                    ? 'max-w-[450px] max-h-[550px]' // Landing
                    : 'max-w-[250px] max-h-[250px]', // Default
            ]"
        />
        <!-- Imagen superpuesta (Budget) -->
        <img
            src="/capturas/new_budget.png"
            alt="Example Budget"
            :class="[
                'absolute rounded-lg shadow-xl border border-gray-300 dark:border-gray-700 hover:shadow-4xl',
                landing
                    ? 'max-w-[500px] max-h-[550px] top-20 -right-6' // Landing
                    : 'top-20 -right-0 max-w-[300px] max-h-[300px]', // Default
            ]"
        />
    </div>
</template>
<style scoped>
img {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

img:hover {
    transform: scale(1.05);
}

/* Responsive adjustments - Example using sm breakpoint */
@media (max-width: 640px) {
    .relative {
        margin-right: 0; /* Remove margin on small screens */
        padding: 1rem; /* Adjust padding */
        margin-top: 1rem; /* Adjust top margin */
    }

    /* Adjust image sizes and positions for small screens */
    img:first-of-type { /* Main image */
        max-width: 80%;
        max-height: auto;
    }

    img:last-of-type { /* Overlapping image */
        max-width: 70%;
        max-height: auto;
        top: 10%; /* Adjust vertical position */
        right: 5%; /* Adjust horizontal position */
    }

    /* Specific adjustments for landing vs default on small screens */
    .relative.p-10 { /* Default style on small screens */
         max-width: 90%;
    }
     .relative.p-6 { /* Landing style on small screens */
         max-width: 95%;
    }

    img:first-of-type[class*="max-w-[250px]"] { /* Default main image */
        max-width: 60%;
    }
     img:last-of-type[class*="max-w-[300px]"] { /* Default overlapping image */
        max-width: 50%;
        top: 15%;
        right: 10%;
    }

}
</style>
