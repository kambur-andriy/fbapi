let mix = require('laravel-mix');

mix.webpackConfig({
    watchOptions: {
        aggregateTimeout: 2000,
        poll: 2000,
        ignored: /node_modules/
    }
});

mix.js('resources/assets/js/main.js', 'public/js')
    .js('resources/assets/js/user.js', 'public/js')
    .js('resources/assets/js/admin.js', 'public/js')
    .sass('resources/assets/sass/application.scss', 'public/css')
