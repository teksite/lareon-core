import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import {bunny} from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import {globSync} from "glob";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'lareon/steward/resources/css/app.css', 'lareon/steward/resources/js/app.js',

                ...globSync('lareon/modules/*/resources/js/app.js'), ...globSync('lareon/modules/*/resources/css/app.css'),

                'resources/css/app.css', 'resources/js/app.js',

            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
