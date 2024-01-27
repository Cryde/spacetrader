/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.{js,vue}",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
  },
  darkMode: 'class',
  plugins: [require("daisyui")],
  daisyui: {
    themes: ["dark"],
  },
}
