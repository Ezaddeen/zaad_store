import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.jsx",
            refresh: true,
            // [تمت الإضافة] تحديد المجلد العام الجديد
            publicDirectory: 'public_html',
        }),
        react(),
    ],
});
