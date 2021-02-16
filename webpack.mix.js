const mix = require("laravel-mix");
const chokidar = require("chokidar");

mix.copyDirectory("resources/fonts", "public/fonts");

mix.js("resources/js/app.js", "public/js");

mix.sass("resources/css/app.scss", "public/css");

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
