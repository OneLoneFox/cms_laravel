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
                ui: ['Poppins'],
            },
            boxShadow: {
                softxl: `
                0 0px 1.6px -8px rgba(0, 0, 0, 0.022),
                0 0px 4px -8px rgba(0, 0, 0, 0.031),
                0 0px 8.2px -8px rgba(0, 0, 0, 0.039),
                0 0px 16.8px -8px rgba(0, 0, 0, 0.048),
                0 0px 46px -8px rgba(0, 0, 0, 0.07)
              `,
            }
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
