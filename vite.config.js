import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'public/assets/css/animate.min.css',
                'public/assets/css/horizontal-menu.min.css',
                'public/assets/css/app-style.css',
                'public/assets/css/main.css',
                'public/assets/theme/app.css',
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
