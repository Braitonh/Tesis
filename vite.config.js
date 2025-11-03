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
    server: {
        host: '0.0.0.0', // Permitir acceso desde cualquier IP de la red local
        port: 5173,
        hmr: {
            host: '192.168.1.4', // Tu IP local para que el celular pueda cargar los assets
        },
    },
});
