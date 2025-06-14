import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    uild: {
        chunkSizeWarningLimit: 600,
        rollupOptions: {
            output: {
                manualChunks: {
                    "speech-recognition": [
                        "./resources/js/Pages/IA/speechRecognition.js",
                    ],
                    "ai-components": [
                        "./resources/js/Pages/IA/recordBtn.vue",
                        "./resources/js/Pages/IA/creditsTile.vue",
                    ],
                    "vue-vendor": ["vue", "@inertiajs/vue3"],
                    "ui-components": [
                        "./resources/js/Components/UI/ProcessingMessage.vue",
                    ],
                },
            },
        },
    },
    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
});
