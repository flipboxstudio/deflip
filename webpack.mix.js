// First, we require the module
const mix = require('laravel-mix');
const path = require('path');

// =========================================================================
// Mix any task here and let the "laravel-mix" do the rest
// =========================================================================
mix
    .setPublicPath(path.normalize('./public'))

    // For SASS/SCSS, just use 'sass' mixin
    .sass('resources/assets/sass/app.scss', 'css/')

    // For Javascript Transpiler (ES6/2015 Supported)
    .js('resources/assets/js/app.js', 'js/')
    .copy('node_modules/font-awesome/fonts/*', 'public/fonts/')
    .version();