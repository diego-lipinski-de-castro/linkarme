const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./resources/js/**/*.js",
        "./node_modules/vue-tailwind-datepicker/**/*.js",
    ],

    theme: {
        extend: {
            colors: {
                indigo: {
                    50: "#f0f9ff",
                    100: "#dff1ff",
                    200: "#b9e4fe",
                    300: "#7bd0fe",
                    400: "#34b9fc",
                    500: "#0aa1ed",
                    600: "#0080cb",
                    700: "#0070b5",
                    800: "#055787",
                    900: "#0a4770",
                },
                "vtd-primary": colors.sky,
                "vtd-secondary": colors.gray,
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
