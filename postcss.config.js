module.exports = {
    plugins: {
        "postcss-import": {},
        "postcss-jit-props": require("open-props"),
        "postcss-logical": {dir: "ltr"},
        tailwindcss: {},
        autoprefixer: {}
    }
};
