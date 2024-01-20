import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import vuePlugin from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        vuePlugin(),
        symfonyPlugin(),
    ],
    build: {
        rollupOptions: {
            input: {
                app: "./assets/js/app.js"
            },
            output: {
                manualChunks: {
                    vue: ['vue']
                }
            }
        }
    },
});
