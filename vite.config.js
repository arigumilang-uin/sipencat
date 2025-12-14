import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        // --- TAMBAHAN PENTING DI SINI ---
        host: '0.0.0.0', // Membuka akses jaringan
        hmr: {
            host: '192.168.1.11', // IP Laptop Anda (sesuai log terminal tadi)
        },
        // --------------------------------
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});