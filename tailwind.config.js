/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.{js,vue}",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
  },
  darkMode: 'media',
  plugins: [require("daisyui")],
}
