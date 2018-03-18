const {mix} = require('laravel-mix');

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

mix.js('src/resources/assets/js/app.js', 'src/resources/public/js')
    .sass('src/resources/assets/scss/app.scss', 'src/resources/public/css')

    .copy('node_modules/font-awesome/fonts/', 'src/resources/public/fonts/font-awesome');