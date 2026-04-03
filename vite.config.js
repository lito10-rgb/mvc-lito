import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
export default defineConfig({
    base: '/', // <- agrega esto
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                 'resources/scss/perfil.scss',
                'resources/js/app.js',
                'resources/js/perfil.js',
                'resources/js/carrito-actions.js',
                'resources/js/fallback-counter.js',
                'resources/js/fallback-thumbs.js',
                'resources/js/admin/productos.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
