import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                darkblue: '#070C29',
                deepblue: '#0D143A',
                blue: '#1B1C61',
                mediumblue: '#301864',
                lightblue: '#6522D9',
                neonblue: '#8729FF',
                skyblue: '#2E63D9',
                paleblue: '#EAE0FF',
            },
        },
    },

    plugins: [forms, typography],
};
