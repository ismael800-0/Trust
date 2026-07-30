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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Newsreader', ...defaultTheme.fontFamily.serif],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                trust: {
                    50: '#EAF1EE',
                    100: '#CFE0D8',
                    300: '#7FA895',
                    500: '#2F6F5A',
                    600: '#1B4D3E',
                    700: '#153D32',
                    900: '#0D2620',
                },
                gold: {
                    100: '#F3E4BB',
                    300: '#DDB65C',
                    500: '#C99A2E',
                    600: '#A87F22',
                },
                paper: '#F7F4EC',
                ink: '#22201B',
                clay: {
                    500: '#A6402F',
                    600: '#8A3325',
                },
            },
        },
    },
    plugins: [forms],
};