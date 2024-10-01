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
                sans: ['nunito', ...defaultTheme.fontFamily.sans],
            },

            animation: {
                'background-test': 'background-test 1s ease infinite',
            },

            keyframes: {
                'background-test': {
                    '0%, 100%': {
                        'background-color': 'red',
                    },
                    '50%': {
                        'background-color': 'blue',
                    },
                }
            }
        },
    },

    plugins: [
        forms,
    ],
};
