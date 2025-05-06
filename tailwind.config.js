import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                custom: ["Plus Jakarta Sans", "sans-serif"],
            },
            colors: {
                primary: "#F1F1F1",
                secondary: "#31487A",
                typography: {
                    main: "#31487A",
                    dark: "#2C2C2C",
                    light: "#F1F1F1",
                    hover1: "#223255",
                    hover2: "#909090",
                },
            },
            borderRadius: {
                "xl-figma": "30px", // untuk 30px rounded
                "lg-figma": "15px", // untuk 15px rounded
            },
            fontSize: {
                h1: ["56px", { lineHeight: "1.1", fontWeight: "700" }],
                h2: ["48px", { lineHeight: "1.1", fontWeight: "700" }],
                h3: ["46px", { lineHeight: "1.1", fontWeight: "700" }],
                h4: ["24px", { lineHeight: "1.1", fontWeight: "700" }],
                h5: ["18px", { lineHeight: "1.1", fontWeight: "700" }],
                h6: ["15px", { lineHeight: "1.1", fontWeight: "700" }],
                "body-bold": ["16px", { lineHeight: "1.5", fontWeight: "700" }],
            },
        },
    },

    plugins: [forms, typography],
};
