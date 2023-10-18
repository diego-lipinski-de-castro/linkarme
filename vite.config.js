import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { viteStaticCopy } from "vite-plugin-static-copy";

export default defineConfig({
    build: {
        target: "esnext",
        rollupOptions: {
            // input: ["resources/js/app.js", "resources/css/app.css"],
            output: {
                entryFileNames: `assets/[name].js`,
                chunkFileNames: `assets/[name].js`,
                assetFileNames: `assets/[name].[ext]`,
            },
        },
    },
    optimizeDeps: {
        esbuildOptions: {
            target: ["esnext"],
        },
    },
    plugins: [
        laravel({
            input: "resources/js/app.js",
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
        // viteStaticCopy({
        //     targets: [
        //         {
        //             src: 'resources/css/app.css',
        //             dest: 'css',
        //         }
        //     ]
        // })
    ],
});
