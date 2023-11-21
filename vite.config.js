import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import reload from "vite-plugin-full-reload";
import sri from "vite-plugin-manifest-sri";
import {viteStaticCopy} from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/filament/admin/theme.css"
            ],
            valetTls: true
        }),
        reload(["resources/views/**/*.blade.php"]),
        sri(),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/infusion/dist/infusion-framework.js',
                    dest: 'assets/vendor/infusion/'
                },
                {
                    src: 'node_modules/infusion/src/components/textToSpeech/js/TextToSpeech.js',
                    dest: 'assets/vendor/infusion/'
                },
                {
                    src: 'node_modules/infusion/src/components/orator/js/',
                    dest: 'assets/vendor/infusion/orator/'
                },
                {
                    src: 'node_modules/infusion/src/components/orator/css/',
                    dest: 'assets/vendor/infusion/orator/'
                },
                {
                    src: 'node_modules/infusion/src/components/orator/fonts/Orator-Icons.woff',
                    dest: 'assets/vendor/infusion/orator/fonts/'
                }
            ]
        })
    ]
});
