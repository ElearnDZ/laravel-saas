let mix = require("laravel-mix")
require("laravel-mix-purgecss")

let postcssImport = require("postcss-import")
let tailwindcss = require("tailwindcss")

mix
  .js("resources/assets/js/app.js", "public/js")
  .postCss("resources/assets/css/app.css", "public/css", [
    postcssImport({ from: "./resources/assets/css/app.css" }),
    tailwindcss("./tailwind.js"),
  ])
  .purgeCss()
