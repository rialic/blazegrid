const mix = require('laravel-mix')
require('laravel-mix-purgecss')



//CSS
mix.sass('resources/scss/pages/home.scss', 'assets/css/home/app.css')
    .options({ processCssUrls: false, autoprefixer: { options: { browsers: ['cover 99.5%'] } }, postCss: [require('cssnano')({ discardComments: { removeAll: true, } })] })
    .purgeCss()


if (mix.inProduction()) {
    mix.version()
}




