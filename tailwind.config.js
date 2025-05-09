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
            screens: {
                'tiny': '10px', // Thêm breakpoint tùy chỉnh 'tiny' cho màn hình nhỏ hơn
            },
        },
    },

    plugins: [
        forms,
    ],
};
