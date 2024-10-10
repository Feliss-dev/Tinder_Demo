// mix.js("resources/js/app.js", "public/js")
//     .js("./node_modules/flowbite/src/flowbite.js", "public/js")
//     .postCss("resources/css/app.css", "public/css", [require("tailwindcss")])
//     .postCss("./node_modules/flowbite/src/flowbite.css", "public/css");

let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

mix.js('node_modules/flowbite/dist/flowbite.js', 'public/js')
    .postCss('node_modules/flowbite/dist/tailwind.css', 'public/css', [
        tailwindcss('./tailwind.js'),
    ]);
