const mix = require('laravel-mix');
require('laravel-mix-modernizr');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css/_app.scss.css')
   .less('resources/less/app.less', 'public/css/_app.less.css')
   .styles([
      "public/css/_app.less.css",
      "public/css/_app.scss.css",
      "resources/css/*.css",
   ], "public/css/app.css")
   .extract([
      "vue",
      "jquery",
      "bootstrap",
      "Modernizr",
      "webshim",
      "datatables.net-responsive-bs",
      "datatables.net-bs"
   ])
   .modernizr()
   .sourceMaps();