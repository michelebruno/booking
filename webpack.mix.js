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
      files: ["public/**/*.css", "public/**/*.js", "public/*.js"],
      proxy: 'localhost'
   })
   .react('resources/js/index.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sourceMaps( true, 'source-map')
