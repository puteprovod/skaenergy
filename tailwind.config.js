const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/views/**/*.vue',
    ],
    theme: {
        extend: {
            backgroundImage: {
                'top-image': "url('/img/top.png')"
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                'benderbold': ['benderbold', ...defaultTheme.fontFamily.sans],
                'benderregular': ['benderregular', ...defaultTheme.fontFamily.sans]
            },
            colors: {
                'darkbl' : '#4d6b96',
                'mild-white' : '#ffffff',
                'bg-gray': '#f0f0f0',
                'ska-blue': '#002b69'
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
