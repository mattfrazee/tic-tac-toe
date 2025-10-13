import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
        VitePWA({
            registerType: 'autoUpdate',
            manifest: {
                name: 'Tic Tac Toe',
                short_name: 'TicTacToe',
                theme_color: '#0a0a0a',
                background_color: '#0a0a0a',
                display: 'standalone',
                icons: [
                    { src: '/icons/icon-192.png', sizes: '192x192', type: 'image/png' },
                    { src: '/icons/icon-512.png', sizes: '512x512', type: 'image/png' },
                    { src: '/icons/maskable-512.png', sizes: '512x512', type: 'image/png', purpose:'maskable' },
                ]
            },
            workbox: { globPatterns: ['**/*.{js,css,html,png,svg,ico}'] }
        })
    ],
    publicDir: 'public',
    base: '/',
    server: {
        host: '0.0.0.0',          // allow connections from LAN
        port: 5173,               // make sure this matches your Vite port
        cors: true,               // allow cross-origin requests
        hmr: {
            host: '10.0.0.194',     // your local network IP (same as Laravel server)
        },
        origin: 'http://10.0.0.194:5173', // used for injected @vite/client scripts
    },
});
