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
                sans: ['Manrope', 'Noto Sans Arabic', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                oasis: {
                    green: '#006241',       // Historic Starbucks Green - h1 & brand signals
                    accent: '#00754A',      // Green Accent - primary CTAs & Frap button
                    house: '#1E3932',       // House Green - deep feature bands & footer
                    uplift: '#2b5148',      // Green Uplift - mid-dark green
                    mint: '#d4e9e2',        // Green Light - form valid-state & soft mint
                    gold: '#cba258',        // Ceremony & honors Gold
                    'gold-light': '#dfc49d',
                    'gold-lightest': '#faf6ee',
                    cream: '#f2f0eb',       // Neutral Warm page canvas
                    ceramic: '#edebe9',     // Ceramic section wash & dividers
                    white: '#ffffff',       // Pure card & modal surface
                    black: '#000000',       // Pure deep ink for dark CTAs
                    ink: 'rgba(0, 0, 0, 0.87)',      // Text Black (87%)
                    muted: 'rgba(0, 0, 0, 0.58)',    // Text Black Soft (58%)
                    'white-soft': 'rgba(255, 255, 255, 0.70)', // Text White Soft on dark
                    danger: '#c82014',      // Red for error & destructive states
                    warning: '#fbbc05',     // Yellow for warnings
                },
            },
            boxShadow: {
                card: '0 0 0.5px rgba(0, 0, 0, 0.14), 0 1px 1px rgba(0, 0, 0, 0.24)',
                nav: '0 1px 3px rgba(0, 0, 0, 0.1), 0 2px 2px rgba(0, 0, 0, 0.06), 0 0 2px rgba(0, 0, 0, 0.07)',
                frap: '0 0 6px rgba(0, 0, 0, 0.24), 0 8px 12px rgba(0, 0, 0, 0.14)',
                'frap-active': '0 0 6px rgba(0, 0, 0, 0.24), 0 8px 12px rgba(0, 0, 0, 0)',
            },
            borderRadius: {
                card: '12px',
                pill: '50px',
            },
            letterSpacing: {
                tightest: '-0.01em',
                loose: '0.1em',
                looser: '0.15em',
            },
        },
    },

    plugins: [forms],
};
