const mix = require("laravel-mix");
const openProps = require("open-props");
const postcssEasyImport = require("postcss-easy-import");
const postcssJitProps = require("postcss-jit-props");
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
        postcssEasyImport(),
        postcssJitProps(openProps)
    ]
});

