const path = require('path');
const fs = require('fs');
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
    mode: 'production',
    entry: ['./js/index.js'],
    output: {
        path: path.resolve(__dirname, '../public/js/'),
        filename: 'bundle.js',
    },
    resolve: {
        modules: ['node_modules']
    },
    module: {
        rules: [
            {
                test: /\.js/,
                loader: 'babel-loader',
                options: {
                    babelrc: true,
                    presets: [
                        ["react"],
                        ["es2015"],
                        ["stage-0"]
                    ],
                    plugins: ["transform-decorators-legacy"]
                },
            },
        ]
    },
    plugins: [
        new CopyWebpackPlugin([
            {from: './js/*', to: '../'},
            {from: './css/*', to: '../'},
            {from: './img/*', to: '../'}
        ])
    ]
};
