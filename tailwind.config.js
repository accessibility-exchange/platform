/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        colors: {
            black: "#000",
            white: "#fff",
            "graphite-8": "#2b2e38",
            "graphite-7": "#3f424c",
            "graphite-6": "#4b5560",
            "graphite-5": "#637180",
            "grey-3": "#d9dce3",
            "grey-2": "#eaedf3",
            "grey-1": "#f8fafd",
            "blue-8": "#0f1138",
            "blue-7": "#26296a",
            "blue-6": "#3842aa",
            "blue-5": "#7077CB",
            "green-5": "#00aea9",
            "green-2": "#92e5e3",
            "green-1": "#bfe2e1",
            "turquoise-5": "#00b9d6",
            "turquoise-2": "#7de2f4",
            "turquoise-1": "#c6f4fe",
            "lavender-3": "#cab6d9",
            "lavender-2": "#eecef0",
            "magenta-3": "#f16e9d",
            "magenta-2": "#ffa0ce",
            "yellow-3": "#f5dc62",
            "yellow-2": "#fce88b",
            "yellow-1": "#ebedbb",
            "red-9": "#691616",
            "red-8": "#922020",
            "red-2": "#f6a7a7",
            "red-1": "#f5cdcd"
        },
        backgroundColor: ({theme}) => theme("colors"),
        textColor: ({theme}) => theme("colors"),
        fontFamily: {
            base: ["\"Open Sans\"", "sans-serif"],
            display: ["montserrat", "sans-serif"]
        },
        fontSize: {
            "fluid-lg": "clamp(1.125rem, 0.8929vw + 0.6964rem, 1.5rem)",
            "fluid-xl": "clamp(1.25rem, 1.7857vw + 0.3929rem, 2rem)",
            "fluid-2xl": "clamp(1.75rem, 2.9762vw + 0.3214rem, 3rem)",
            "fluid-3xl": "clamp(2.5rem, 4.7619vw + 0.2143rem, 4.5rem)",
            xs: ["0.75rem", { lineHeight: "1rem" }],
            sm: ["0.875rem", { lineHeight: "1.25rem" }],
            base: ["1rem", { lineHeight: "1.5rem" }],
            lg: ["1.125rem", { lineHeight: "1.75rem" }],
            xl: ["1.25rem", { lineHeight: "1.75rem" }],
            "2xl": ["1.5rem", { lineHeight: "2rem" }],
            "3xl": ["1.875rem", { lineHeight: "2.25rem" }],
            "4xl": ["2.25rem", { lineHeight: "2.5rem" }],
            "5xl": ["3rem", { lineHeight: "1" }],
            "6xl": ["3.75rem", { lineHeight: "1" }],
            "7xl": ["4.5rem", { lineHeight: "1" }]
        },
        maxWidth: {
            prose: "70ch"
        }
    },
    corePlugins: {
        preflight: false
    },
    plugins: []
};
