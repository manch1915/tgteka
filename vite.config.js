import { defineConfig, splitVendorChunkPlugin } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import i18n from 'laravel-vue-i18n/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        i18n(),
        splitVendorChunkPlugin()
    ],
    optimizeDeps: {
        include: [
            'naive-ui',
            'vueuc',
            'keen-slider',
            // Include other dependencies that might be problematic
        ],
    },
    ssr: {
        noExternal: [
            'naive-ui',
            'vueuc',
            'keen-slider',
            // Ensure these are bundled and not externalized
        ],
    },
});
