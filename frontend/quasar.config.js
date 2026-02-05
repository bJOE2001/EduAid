/* eslint-env node */

/*
 * This file runs in a Node context (it's NOT transpiled by Babel), so use only
 * the ES6 features that are supported by your Node version. https://node.green/
 */

// Configuration for your app
// https://v2.quasar.dev/quasar-cli-vite/quasar-config-js

const { configure } = require('quasar/wrappers');

module.exports = configure(function (ctx) {
  return {
    // https://v2.quasar.dev/quasar-cli-vite/prefetch-feature
    // preFetch: true,

    // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#css
    css: [
      'app.scss'
    ],

    // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#property-boot
    boot: [
      'pinia',
      'axios',
      'router'
    ],


    // https://github.com/quasarframework/quasar/blob/dev/app-extension/app-extension-vite/src/plugin/index.js#L1
    plugins: {
      'Notify': {},
      'Dialog': {},
      'Loading': {},
    },

    // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#property-framework
    framework: {
      config: {
        dark: false,
        brand: {
          primary: '#1976D2',
          secondary: '#26A69A',
          accent: '#9C27B0',
          dark: '#1d1d1d',
          positive: '#21BA45',
          negative: '#C10015',
          info: '#31CCEC',
          warning: '#F2C037'
        }
      },

      // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#property-iconSet
      iconSet: 'material-icons', // Quasar icon set

      // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#property-lang
      lang: 'en-us', // Quasar language pack

      // For special cases outside of where the auto-import strategy can have an impact
      // (like functional components as one of the examples),
      // you can manually specify Quasar components/directives to be available everywhere:
      //
      // components: [],
      // directives: [],

      // Quasar plugins
      plugins: []
    },

    // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#property-build
    build: {
      target: {
        browser: [ 'es2019', 'edge88', 'firefox78', 'chrome87', 'safari13.1' ],
        node: 'node20'
      },

      vueRouterMode: 'history', // available values: 'hash', 'history'
      // vueRouterBase,
      // vueDevtools,
      // gzip: true,
      // analyze: true,
      // extractCSS: false,
      // sourceMap: false,
      // minify: false,
      // polyfillModulePreload: true,
      // distDir

      // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#property-prodEnv
      env: {
        API_URL: ctx.dev
          ? 'http://localhost:8000/api'
          : 'https://api.eduaid.gov/api'
      },

      // rawDefine: {}
      // vueOptionsRouter: {}
    },

    // Full list of options: https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#property-devServer
    devServer: {
      // https: true
      open: true, // opens browser window automatically
      port: 9000
    },

    // https://v2.quasar.dev/quasar-cli-vite/quasar-config-js#property-htmlVariables
    htmlVariables: {
      // htmlVariables: {
      //   'editor': 'some value', // you can use
      //   'product-name': 'Quasar' // |product-name| in index.html
      // }
    }
  };
});
