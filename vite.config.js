import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/sortable-styles.css',
                'resources/js/sortable-helper.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
