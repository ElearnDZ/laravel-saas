let mix = require("laravel-mix")
require("laravel-mix-purgecss")

let tailwindcss = require("tailwindcss")

mix
  .js("resources/assets/js/app.js", "public/js")
  .postCss("resources/assets/css/app.css", "public/css", [
    tailwindcss("./tailwind.js"),
  ])
  .purgeCss()
