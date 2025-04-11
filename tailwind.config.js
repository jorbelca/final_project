import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            colors: {
                primary: "var(--color-primary)",
                secondary: "var(--color-secondary)",
                background: "var(--color-background)",
                background_contrast: "var(--color-background-contrast)",
                background_header: "var(--color-background-header)",
                background_nav: "var(--color-background-nav)",
                border: "var(--color-border)",
                hover: "var(--color-hover)",
                text: "var(--color-text)",
            },

            fontFamily: {
                sans: ["Lato", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
