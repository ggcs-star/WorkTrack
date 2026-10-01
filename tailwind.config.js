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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    50: '#eef2f8',
                    100: '#d9e1ee',
                    200: '#b3c3dd',
                    300: '#8da5cc',
                    400: '#5f7fb0',
                    500: '#3d5a8a',
                    600: '#2a3f66',
                    700: '#1f2f4d',
                    800: '#182540',
                    900: '#101a30',
                },
                sky: {
                    50: '#eaf3ff',
                    100: '#d0e6ff',
                    200: '#a3cdff',
                    300: '#70b0ff',
                    400: '#3f8ff5',
                    500: '#2f7fe0',
                    600: '#2566b8',
                    700: '#1d4f91',
                    800: '#163c6e',
                    900: '#102a4e',
                },
                success: {
                    50: '#eafbf1',
                    100: '#d1f5de',
                    500: '#1fa35a',
                    600: '#17824a',
                    700: '#116238',
                },
                warning: {
                    50: '#fff7e8',
                    100: '#ffecc4',
                    500: '#f2a71b',
                    600: '#cc8710',
                    700: '#a76b0c',
                },
                danger: {
                    50: '#fdecef',
                    100: '#fbd2da',
                    500: '#e94f6b',
                    600: '#d13257',
                    700: '#a82443',
                },
            },
        },
    },

    plugins: [forms],
};
