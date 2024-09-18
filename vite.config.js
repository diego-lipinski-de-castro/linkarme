import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    build: {
        target: "esnext",
    },
    optimizeDeps: {
        esbuildOptions: {
            target: ["esnext"],
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/js/app.js",
            ],
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
    ],
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name === "app.css") {
                        return "assets/app.css";
                    }
                    return "assets/[name][extname]";
                },
            },
        },
    },
});
