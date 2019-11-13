const mix = require('laravel-mix');

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

mix.disableNotifications()
   .browserSync({
      proxy: 'localhost:8080',
      files: ["public/**/*.css", "public/**/*.js", "public/*.js"]
   })
   .react('resources/js/index.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')