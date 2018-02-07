const path = require('path');
const webpack = require('webpack');

module.exports = {
    entry: {
        './assets/block/block.build': './assets/block/block.js',
    },
    output: {
        path: path.resolve(__dirname),
        filename: '[name].js',
    },
    watch: true,
    devtool: 'cheap-eval-source-map',
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                },
            },
        ],
    },
};