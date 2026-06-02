/**
 * Webpack config for QR Link Generator for WP blocks.
 *
 * @package Qr_Link_Generator_For_Wp
 */

const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
	...defaultConfig,
	entry: {
		index: path.resolve(__dirname, 'blocks/qr-code/index.js'),
	},
	output: {
		path: path.resolve(__dirname, 'blocks/qr-code/build'),
		filename: '[name].js',
		clean: true,
	},
	plugins: defaultConfig.plugins.map((plugin) => {
		if (plugin instanceof MiniCssExtractPlugin) {
			return new MiniCssExtractPlugin({
				filename: '[name].css',
			});
		}
		return plugin;
	}),
};
