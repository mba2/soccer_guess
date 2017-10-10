const path = require('path');
const webpack = require('webpack');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');

module.exports = [
    new webpack.HotModuleReplacementPlugin(),

    new CleanWebpackPlugin([
        path.resolve(__dirname, 'public/'), path.resolve(__dirname, 'dist/')
    ], {
        "verbose": true
    }),

    new CopyWebpackPlugin([
        {
            from: './src/images/',
            to: path.resolve(__dirname, 'public/assets/images')
        },
        {
            from: './src/index.html',
            to: process.env.NODE_ENV === 'dist' ? path.resolve(__dirname, 'dist/') : path.resolve(__dirname, 'public/')
        }
    ]),

    new ImageminPlugin({ test: /\.(jpe?g|png|gif|svg)$/i })
];