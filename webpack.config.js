const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/js/app.js')
    .addStyleEntry('simple', './assets/css/simple.scss')

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    .enableSassLoader()
    .enablePostCssLoader()
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();
