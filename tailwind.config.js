/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.{js,vue}",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    extend: {},
  },
  darkMode: 'media',
  plugins: [require('flowbite/plugin')],
}
