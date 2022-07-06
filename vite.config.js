import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import reload from "vite-plugin-full-reload";
import { readFileSync } from "fs";
import { resolve } from "path";
import { homedir } from "os";
import sri from "vite-plugin-manifest-sri";

let host = "platform.test";
let homeDir = homedir();

export default defineConfig(({command}) => {
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
            https: {
                key: readFileSync(resolve(homeDir, `.config/valet/Certificates/${host}.key`)),
                cert: readFileSync(resolve(homeDir, `.config/valet/Certificates/${host}.crt`))
            },
            hmr: {
                host
            },
            host
        };
    }

    return config;
});
