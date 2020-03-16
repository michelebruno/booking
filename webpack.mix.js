// eslint-disable-next-line no-undef
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
      files: ["public/**/*.css", "public/**/*.js", "public/*.js", "resource/views/**"],
      proxy: {
         target: "https://localhost",
      },
      https: true,
   })
   .react('resources/js/index.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/style.scss', 'public/css')
   .sourceMaps(true, 'source-map')
