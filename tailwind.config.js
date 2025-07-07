import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import colors from 'tailwindcss/colors'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            textColor: {
                skin: {
                    success: 'var(--color-text-success)',
                    info: 'var(--color-text-info)',
                    warning: 'var(--color-text-warning)',
                    danger: 'var(--color-text-danger)',
                    base: 'var(--color-text-base)',
                    muted: 'var(--color-text-muted)',
                    inverted: 'var(--color-text-inverted)',
                },
            },
        }
    },
    darkMode: 'class',
    plugins: [forms, typography],
};
