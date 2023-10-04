const defaultConfig = require('@wordpress/scripts/config/webpack.config')
const { resolve } = require('path')
const CopyPlugin = require('copy-webpack-plugin')
const path = require('path')
const WebpackAssetsManifest = require('webpack-assets-manifest')
const MiniCSSExtractPlugin = require('mini-css-extract-plugin')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')

module.exports = {
    ...defaultConfig,
    devServer: {
        ...defaultConfig.devServer,
        host: process.env.WP_DEVHOST || 'wordpress.test',
    },
    plugins: [
        ...defaultConfig.plugins,
        new CleanWebpackPlugin(),
        new CopyPlugin({
            patterns: [
                {
                    from: 'src/Library/utility-minimum.css',
                    to: 'utility-minimum.css',
                },
            ],
        }),
        new WebpackAssetsManifest({
            output: path.resolve(process.cwd(), 'public/build/manifest.json'),
            publicPath: true,
            writeToDisk: true,
        }),
        new MiniCSSExtractPlugin({ filename: '[name]-[hash].css' }),
    ],
    resolve: {
        ...defaultConfig.resolve,
        alias: {
            ...defaultConfig.resolve.alias,
            '@library': resolve(__dirname, 'src/Library'),
            '@onboarding': resolve(__dirname, 'src/Onboarding'),
            '@assist': resolve(__dirname, 'src/Assist'),
            '@chat': resolve(__dirname, 'src/Chat'),
            '@draft': resolve(__dirname, 'src/Draft'),
        },
    },
    entry: {
        extendify: './src/Library/app.js',
        'extendify-onboarding': './src/Onboarding/app.js',
        'extendify-assist-page': './src/Assist/page.js',
        'extendify-assist-global': './src/Assist/global.js',
        'extendify-chat': './src/Chat/app.js',
        'extendify-draft': './src/Draft/app.js',
        'editorplus.min': './editorplus/editorplus.js',
        'extendify-deactivate': './src/Library/DeactivationPrompt.js',
    },
    output: {
        filename: '[name]-[hash].js',
        path: resolve(process.cwd(), 'public/build'),
    },
}
