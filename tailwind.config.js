import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                montserrat: ['"Montserrat"', ...defaultTheme.fontFamily.sans],
                quicksand: ['"Quicksand"', ...defaultTheme.fontFamily.sans],
                sans: ['"Quicksand"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    orange: {
                        50:  '#FFF7ED',
                        100: '#FFEDD5',
                        200: '#FED7AA',
                        300: '#FDBA74',
                        400: '#FB923C',
                        500: '#F97316',
                        600: '#EA580C',
                        700: '#C2410C',
                        primary: '#FF6B00',
                    },
                    red: {
                        50:  '#FEF2F2',
                        100: '#FEE2E2',
                        200: '#FECACA',
                        400: '#F87171',
                        500: '#EF4444',
                        600: '#DC2626',
                        700: '#B91C1C',
                        primary: '#E11D48',
                    },
                    canvas: '#FAF8F5',
                    dark: '#1E1B18',
                },
            },
        },
    },

    plugins: [forms],
};
