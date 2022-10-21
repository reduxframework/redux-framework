const defaultConfig = require('@wordpress/scripts/config/webpack.config')
const { resolve } = require('path')

module.exports = {
    ...defaultConfig,
    plugins: [...defaultConfig.plugins],
    resolve: {
        ...defaultConfig.resolve,
        alias: {
            ...defaultConfig.resolve.alias,
            '@library': resolve(__dirname, 'src/Library'),
            '@onboarding': resolve(__dirname, 'src/Onboarding'),
            '@assist': resolve(__dirname, 'src/Assist'),
        },
    },
    entry: {
        extendify: './src/Library/app.js',
        'extendify-onboarding': './src/Onboarding/app.js',
        'extendify-assist': './src/Assist/app.js',
        'editorplus.min': './editorplus/editorplus.js',
    },
    output: {
        filename: '[name].js',
        path: resolve(process.cwd(), 'public/build'),
    },
}
