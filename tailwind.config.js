const defaultTheme = require('tailwindcss/defaultTheme');
const plugin = require('tailwindcss/plugin');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/**/*.html',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
            borderWidth: ['last'],
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        plugin(function ({ addBase, theme }) {
            addBase({
                'h1': { fontSize: theme('fontSize.4xl') },
                'h2': { fontSize: theme('fontSize.2xl') },
                'h3': { fontSize: theme('fontSize.1xl') },
                'h4': { fontSize: theme('fontSize.xl') },
            })
        }),
    ],
};
