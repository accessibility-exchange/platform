const mix = require("laravel-mix");
require("laravel-mix-sri");

mix.copyDirectory("resources/fonts", "public/fonts");

mix.js("resources/js/app.js", "public/js").extract();

mix.sass("resources/css/app.scss", "public/css");

mix.generateIntegrityHash();

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync({
    port: 8080,
    proxy: "localhost",
    open: false
});

mix.options({
    processCssUrls: false
});

