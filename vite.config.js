import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig(({ mode }) => {
    return {
        // build: {
        //     target: "esnext",
        //     rollupOptions: {
        //         output: {
        //             assetFileNames: (assetInfo) => {
        //                 if (assetInfo.name === "app.css") {
        //                     return "assets/app.css";
        //                 }
        //                 return "assets/[name][extname]";
        //             },
        //         },
        //     },
        // },
        optimizeDeps: {
            esbuildOptions: {
                target: ["esnext"],
            },
        },
        build: {
			sourcemap: true,
			target: 'esnext',
		},
        plugins: [
            laravel(['resources/css/app.css', 'resources/js/app.js']),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
    }
});
