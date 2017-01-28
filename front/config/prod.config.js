const path = require('path');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const CleanWebpackPlugin = require('clean-webpack-plugin');
const webpack = require('webpack');

const endpoint = 'http://localhost:8080';
const webDir = path.join(__dirname, '../../web');

const config = {
    entry: [
        path.join(__dirname, '../js/index.js')
    ],
    output: {
        path: path.join(webDir, 'js'),
        publicPath: `${endpoint}/js/`,
        filename: "bundle.js"
    },
    module: {
        loaders: [{
            test: /\.scss$/,
            loader: ExtractTextPlugin.extract("style", "css?minimize!sass")
        }, {
            test: /\.js$/,
            exclude: /node_modules/,
            loader: 'babel-loader',
            query: {
                presets: ['es2015']
            }
        }, {
            test: /\.(png|woff|woff2|eot|ttf|svg).*$/,
            loader: 'url-loader?limit=100000'
        }, {
            test: /\.json$/,
            loader: 'json-loader'
        }]
    },
    plugins: [
        new ExtractTextPlugin("../css/styles.css"),
        new CleanWebpackPlugin(['js', 'css'], { root: webDir }),
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            }
        })
    ],
};

module.exports = config;
