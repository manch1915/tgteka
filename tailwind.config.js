import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import plugin from "tailwindcss";

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
                mediumblue: '#301864',
                lightblue: '#6522D9',
                neonblue: '#8729FF',
                skyblue: '#2E63D9',
                paleblue: '#EAE0FF',
                errorred: '#ff8888',
            },
            zIndex: {
                "-1": "-1",
            },
            flexGrow: {
                5: "5",
            },
            maxHeight: {
                "screen-menu": "calc(100vh - 3.5rem)",
                modal: "calc(100vh - 160px)",
            },
            transitionProperty: {
                position: "right, left, top, bottom, margin, padding",
                textColor: "color",
            },
            keyframes: {
                "fade-out": {
                    from: { opacity: 1 },
                    to: { opacity: 0 },
                },
                "fade-in": {
                    from: { opacity: 0 },
                    to: { opacity: 1 },
                },
            },
            animation: {
                "fade-out": "fade-out 250ms ease-in-out",
                "fade-in": "fade-in 250ms ease-in-out",
            },
        },
        asideScrollbars: {
            light: "light",
            gray: "gray",
        }
    },

    plugins: [forms, typography,plugin(function ({ matchUtilities, theme }) {
        matchUtilities(
            {
                "aside-scrollbars": (value) => {
                    const track = value === "light" ? "100" : "900";
                    const thumb = value === "light" ? "300" : "600";
                    const color = value === "light" ? "gray" : value;

                    return {
                        scrollbarWidth: "thin",
                        scrollbarColor: `${theme(`colors.${color}.${thumb}`)} ${theme(
                            `colors.${color}.${track}`
                        )}`,
                        "&::-webkit-scrollbar": {
                            width: "8px",
                            height: "8px",
                        },
                        "&::-webkit-scrollbar-track": {
                            backgroundColor: theme(`colors.${color}.${track}`),
                        },
                        "&::-webkit-scrollbar-thumb": {
                            borderRadius: "0.25rem",
                            backgroundColor: theme(`colors.${color}.${thumb}`),
                        },
                    };
                },
            },
            { values: theme("asideScrollbars") }
        );
    }),],
};
