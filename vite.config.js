import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import basicSsl from '@vitejs/plugin-basic-ssl'; // Import the plugin

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        basicSsl() // Use the plugin
    ],
    server: {
        https: true, // Enable HTTPS server
        host: 'localhost',
    }
});