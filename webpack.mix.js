let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Statamic Control Panel Assets
 |--------------------------------------------------------------------------
 |
 | Feel free to add your own JS or CSS to the Statamic Control Panel.
 | https://statamic.dev/extending/control-panel#adding-css-and-js-assets
 |
 */

mix.js('resources/js/cp.js', 'resources/dist/js').vue();
//    .sass('resources/sass/cp.scss', 'resources/dist/css');
