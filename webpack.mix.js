const mix = require("laravel-mix");
const chokidar = require("chokidar");
require("laravel-mix-sri");

mix.copyDirectory("resources/fonts", "public/fonts");

mix.js("resources/js/app.js", "public/js").extract();

mix.sass("resources/css/app.scss", "public/css");

mix.generateIntegrityHash();

if (mix.inProduction()) {
    mix.version();
}

mix.options({
    hmrOptions: {
        host: "localhost",
        port: 8080
    },
    processCssUrls: false
});

mix.webpackConfig({
    devServer: {
        host: "0.0.0.0",
        port: 8080,
        onBeforeSetupMiddleware(server) {
            chokidar.watch([
                "./resources/views/**/*.blade.php"
            ]).on("all", function () {
                server.sockWrite(server.sockets, "content-changed");
            });
        }
    }
});
