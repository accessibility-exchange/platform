const mix = require("laravel-mix");
require("laravel-mix-sri");

mix.js("resources/js/app.js", "public/js").extract();

mix.css("resources/css/app.css", "public/css");

mix.generateIntegrityHash();

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync({
    // If using Laravel Sail, change to "localhost"
    proxy: "platform.test",
    open: false
});

mix.options({
    processCssUrls: false,
    postCss: [
        require("postcss-easy-import"),
        require("postcss-jit-props")(require("open-props")),
        require("postcss-logical")({dir: "ltr"}),
        require("tailwindcss")
    ]
});

