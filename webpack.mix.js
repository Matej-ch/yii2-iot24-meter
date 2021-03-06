let mix = require('laravel-mix');

mix.css('src/web/css/main.css', 'src/web/css/main.min.css', {processUrls: false});
mix.css('src/web/css/tui-date-picker.css', 'src/web/css/tui-date-picker.min.css', {processUrls: false});
mix.css('src/web/css/tui-time-picker.css', 'src/web/css/tui-time-picker.min.css', {processUrls: false});
mix.css('src/web/css/calendar.css', 'src/web/css/calendar.min.css', {processUrls: false});

mix.js('src/web/js/calendar.js', 'src/web/js/calendar.min.js');
mix.js('src/web/js/main.js', 'src/web/js/main.min.js');

mix.options({
    terser: {
        extractComments: false,
    },
    processCssUrls: false,
})

mix.webpackConfig({
    optimization: {
        providedExports: false,
        sideEffects: false,
        usedExports: false
    },
});