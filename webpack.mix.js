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
    .js('resources/assets/js/user_profile.js', 'public/js')
    .js('resources/assets/js/user_campaigns.js', 'public/js')
    .js('resources/assets/js/user_sets.js', 'public/js')
    .js('resources/assets/js/user_creatives.js', 'public/js')
    .js('resources/assets/js/user_ads.js', 'public/js')
    .js('resources/assets/js/admin.js', 'public/js')
    .sass('resources/assets/sass/application.scss', 'public/css')
