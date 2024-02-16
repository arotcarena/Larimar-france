const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/entrypoints/app.js')
    .addEntry('admin', './assets/entrypoints/admin.js')
    //FR
    .addEntry('header', './assets/entrypoints/fr/header.js')
    .addEntry('productIndex', './assets/entrypoints/fr/productIndex.js')
    .addEntry('productShow', './assets/entrypoints/fr/productShow.js')
    .addEntry('checkout', './assets/entrypoints/fr/checkout.js')
    .addEntry('loginForm', './assets/entrypoints/fr/loginForm.js')
    .addEntry('registerForm', './assets/entrypoints/fr/registerForm.js')
    .addEntry('account', './assets/entrypoints/fr/account.js')
    //EN
    .addEntry('enHeader', './assets/entrypoints/en/header.js')
    .addEntry('enProductIndex', './assets/entrypoints/en/productIndex.js')
    .addEntry('enProductShow', './assets/entrypoints/en/productShow.js')
    .addEntry('enCheckout', './assets/entrypoints/en/checkout.js')
    .addEntry('enLoginForm', './assets/entrypoints/en/loginForm.js')
    .addEntry('enRegisterForm', './assets/entrypoints/en/registerForm.js')
    .addEntry('enAccount', './assets/entrypoints/en/account.js')


    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    .enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
