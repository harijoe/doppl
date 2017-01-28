const path = require('path');
const { HotModuleReplacementPlugin } = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');

const endpoint = 'http://localhost:8080';
const webDir = path.join(__dirname, '../../web');

const config = {
    entry: [
        path.join(__dirname, '../js/index.js'),
        `webpack-dev-server/client?${endpoint}`,
        'webpack/hot/dev-server'
    ],
    output: {
        path: path.join(webDir, 'js'),
        publicPath: `${endpoint}/js/`,
        filename: "bundle.js"
    },
    module: {
        loaders: [{
            test: /\.scss$/,
            loaders: ["style-loader", "css-loader?sourceMap", "sass-loader"]
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
        new HotModuleReplacementPlugin(),
        new CleanWebpackPlugin(['js', 'css'], { root: webDir })
    ],
    devServer: {
        hot: true,
        proxy: {
            '*': {
                target: 'http://localhost:8000',
            }
        }

    }
};

module.exports = config;
