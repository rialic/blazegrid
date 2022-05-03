const mix = require('laravel-mix')
require('laravel-mix-purgecss')

//CSS
mix.sass('resources/scss/pages/home.scss', 'assets/css/home/app.css')
    .sass('resources/scss/pages/login.scss', 'assets/css/login/app.css')
    .sass('resources/scss/pages/crash.scss', 'assets/css/crash/app.css')
    .options({ processCssUrls: false, autoprefixer: { options: { browsers: ['cover 99.5%'] } }, postCss: [require('cssnano')({ discardComments: { removeAll: true, } })] })
    .purgeCss()


mix.js('resources/js/pages/home.js', 'assets/js/home/app.js')
    .js('resources/js/pages/crash.js', 'assets/js/crash/app.js')

mix.copyDirectory("resources/webfonts", "public/assets/webfonts")
    .copyDirectory("resources/images", "public/assets/images");


if (mix.inProduction()) {
    mix.version()
}