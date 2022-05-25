const mix = require('laravel-mix')
require('laravel-mix-purgecss')

// CSS
mix.sass('resources/scss/pages/home.scss', 'assets/css/home/app.min.css')
    .sass('resources/scss/pages/login.scss', 'assets/css/login/app.min.css')
    .sass('resources/scss/pages/crash.scss', 'assets/css/crash/app.min.css')
    .options({ processCssUrls: false, autoprefixer: { options: { browsers: ['cover 99.5%'] } }, postCss: [require('cssnano')({ discardComments: { removeAll: true, } })] })
    .purgeCss()

// JS
mix.js(['resources/js/pages/home.js', 'resources/js/components/waves.js'], 'assets/js/home/app.min.js')
    .js(['resources/js/components/waves.js'], 'assets/js/login/app.min.js')
    .js(['resources/js/pages/crash.js', 'resources/js/components/waves.js'], 'assets/js/crash/app.min.js')

// OTHERS
mix.copyDirectory("resources/webfonts", "public/assets/webfonts")
    .copyDirectory("resources/images", "public/assets/images");


if (mix.inProduction()) {
    mix.version()
}