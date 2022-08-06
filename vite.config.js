import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import reload from "vite-plugin-full-reload";
import { homedir } from "os";
import sri from "vite-plugin-manifest-sri";

let host = "platform.test";
let homeDir = homedir();

export default defineConfig(({ command }) => {
    let config = {
        plugins: [
            laravel([
                "resources/css/app.css",
                "resources/js/app.js"
            ]),
            reload(["resources/views/**/*.blade.php"]),
            sri()
        ]
    };

    if (homeDir && command === "serve") {
        config.server = {
            hmr: {
                host
            },
            host
        };
    }

    return config;
});
