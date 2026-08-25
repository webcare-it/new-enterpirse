import path from "path";
import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react-swc";

export default defineConfig(({ mode }) => ({
    plugins: [react(), tailwindcss()],

    base: mode === "development" ? "/" : "/app/",

    resolve: {
        alias: {
            "@": path.resolve(__dirname, "./src"),
        },
    },

    build: {
        target: "es2015",
        cssTarget: "safari11",
        outDir: "../../../public/app",
        emptyOutDir: true,
        chunkSizeWarningLimit: 500,
        sourcemap: "hidden",
        minify: "terser",
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
        rollupOptions: {
            output: {
                manualChunks: {
                    "react-vendor": [
                        "react",
                        "react-dom",
                        "react-dom/client",
                        "react-router",
                        "react-router-dom",
                    ],
                    motion: ["framer-motion"],
                    swiper: ["swiper", "swiper/react", "swiper/modules"],
                    query: ["@tanstack/react-query"],
                    ui: [
                        "lucide-react",
                        "class-variance-authority",
                        "tailwind-merge",
                    ],
                    lightbox: ["photoswipe"],
                },
            },
        },
    },
}));
