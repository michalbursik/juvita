require('dotenv').config({path: '../../../.env'})

export default {
    // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
    ssr: false,

    env: {
        APP_URL: process.env.APP_URL,
        API_URL: process.env.API_URL,
        ASSET_URL: process.env.ASSET_URL,
    },

    // Global page headers: https://go.nuxtjs.dev/config-head
    head: {
        title: 'admin',
        meta: [
            {charset: 'utf-8'},
            {name: 'viewport', content: 'width=device-width, initial-scale=1'},
            {hid: 'description', name: 'description', content: ''},
            {name: 'format-detection', content: 'telephone=no'}
        ],
        link: [
            {rel: 'icon', type: 'image/x-icon', href: '/favicon.ico'}
        ]
    },

    // Global CSS: https://go.nuxtjs.dev/config-css
    css: [{src: "@assets/scss/app.scss", lang: "sass"}],


    // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
    plugins: [
        {src: '~/plugins/axios.js', mode: 'client'},
        {src: '~/plugins/global.js', mode: 'client'},
        {src: '~/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', mode: 'client'},
    ],

    // Auto import components: https://go.nuxtjs.dev/config-components
    components: true,

    // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
    buildModules: [],

    auth: {
        redirect: {
            logout: '/login',
            login: '/login',
            home: '/warehouses'
        },
        strategies: {
            laravelSanctum: {
                provider: 'laravel/sanctum',
                url: process.env.API_URL
            },
        }
    },

    router: {
        middleware: ['auth', 'clear']
    },

    // Modules: https://go.nuxtjs.dev/config-modules
    modules: [
        // https://go.nuxtjs.dev/axios
        '@nuxtjs/axios',
        '@nuxtjs/auth-next',
        // https://go.nuxtjs.dev/pwa
        '@nuxtjs/pwa',
    ],

    publicRuntimeConfig: {
        APP_NAME: process.env.APP_NAME,
        APP_ENV: process.env.APP_ENV,
        ASSET_URL: process.env.ASSET_URL,
    },

    // Axios module configuration: https://go.nuxtjs.dev/config-axios
    axios: {
        // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
        baseURL: process.env.API_URL + '/api',
        credentials: true,
    },

    // TODO cache files you find in console
    workbox: {
        // dev: true,
        // debug: true,

        // offlineStrategy: 'StaleWhileRevalidate',
    },

    // PWA module configuration: https://go.nuxtjs.dev/pwa
    pwa: {
        manifest: {
            name: process.env.APP_NAME,
            short_name: process.env.APP_NAME,
            description: 'Aplikace pro správu skladu',
            start_url: process.env.APP_URL,
            lang: 'cs',
            background_color: '#ffffff',
            theme_color: '#ffffff',
            display: 'standalone',
            orientation: 'any',
        },
        icon: {
            sizes: [72, 96, 128, 144, 152, 192, 384, 512],
            fileName: 'icon.png',
            targetDir: 'pwa-icons',
            purpose: 'any',
        },
    },

    // Build Configuration: https://go.nuxtjs.dev/config-build
    build: {
        publicPath: process.env.ASSET_URL + "/dist/",
        extend(config, {isClient, isDev}) {
            console.log('APP_ENV: ' + process.env.APP_ENV);
            console.log('APP_URL: ' + process.env.APP_URL);
            console.log('ASSET_URL: ' + process.env.ASSET_URL);

            if (process.env.ASSET_URL === undefined) {
                // console.log('local')
            } else {
                config.output.publicPath = process.env.ASSET_URL + '/dist/'
            }

            console.log('config: ' + config.output.publicPath)
        },
    }
}
