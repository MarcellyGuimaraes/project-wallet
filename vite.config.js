import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

const vitePort = process.env.VITE_PORT ? Number(process.env.VITE_PORT) : 5173;

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Playfair Display', {
                    weights: [500, 600, 700],
                }),
                bunny('Cormorant Garamond', {
                    weights: [400, 500, 600, 700],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: vitePort,
        strictPort: true,
        hmr: {
            host: process.env.VITE_HMR_HOST || 'localhost',
            clientPort: vitePort,
        },
        watch: {
            usePolling: true,
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
