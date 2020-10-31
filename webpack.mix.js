const mix = require('laravel-mix');

const tailwindcss = require('tailwindcss');

mix.react('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   	.options({
	    postCss: [
	      tailwindcss(tailwindcss('./tailwind.config.js')),
	    ];
