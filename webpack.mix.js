let mix = require('laravel-mix');
let build = require('./tasks/build.js');

mix.disableSuccessNotifications();
mix.setPublicPath('source/assets/build');
mix.webpackConfig({
    plugins: [
        build.jigsaw,
        build.browserSync(),
        build.watch(['source/**/*.md', 'source/**/*.php', 'source/**/*.scss', '!source/**/_tmp/*']),
    ],
    node: {
        fs: 'empty'
    },
});

mix
    .sass('source/_assets/sass/main.scss', 'css')
    .js('source/_assets/js/events.js', 'js')

// .sass('source/_assets/sass/green.scss', 'css')
// .sass('source/_assets/sass/orange.scss', 'css')
;

// mix.js('source/_assets/js/main.js', 'js')
//     .sass('source/_assets/sass/main.scss', 'css')
//     .sass('source/_assets/sass/aactmad.scss', 'css')
//     .options({
//         processCssUrls: false,
//     });
