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
                sans: ['"SolaimanLipi"', 'Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['"SolaimanLipi"', 'Cormorant Garamond', 'Georgia', ...defaultTheme.fontFamily.serif],
                display: ['"SolaimanLipi"', 'Cormorant Garamond', 'Georgia', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    50: '#fdf2f4',
                    100: '#fce7eb',
                    200: '#f9d0d9',
                    300: '#f4a9ba',
                    400: '#ed7a95',
                    500: '#e34d72',
                    600: '#c7254e',
                    700: '#a61d3d',
                    800: '#8b1a33',
                    900: '#761a2d',
                },
                neutral: {
                    50: '#fafaf9',
                    100: '#f5f5f4',
                    200: '#e7e5e4',
                    300: '#d6d3d1',
                    400: '#a8a29e',
                    500: '#78716c',
                    600: '#57534e',
                    700: '#44403c',
                    800: '#292524',
                    900: '#1c1917',
                },
            },
        },
    },

    plugins: [forms],
};
