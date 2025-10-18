import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'
import os from 'os';
import dotenv from 'dotenv';

dotenv.config();
const networkInterfaces = os.networkInterfaces();
const localIP = Object.values(networkInterfaces)
    .flat()
    .find(details => details.family === 'IPv4' && !details.internal)?.address || '0.0.0.0';
const vitePort = parseInt(process.env.VITE_PORT || 5174);

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
        host: '0.0.0.0',
        port: vitePort,
        cors: true,
        hmr: {
            host: localIP,
            port: vitePort,
            protocol: 'ws',
        },
        origin: `http://${localIP}:${vitePort}`,
    },
});
