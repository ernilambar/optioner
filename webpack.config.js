const path = require( 'path' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const TerserPlugin = require( 'terser-webpack-plugin' );

module.exports = {
	target: 'browserslist',
	context: __dirname,
	entry: {
		optioner: path.resolve( __dirname, 'resources', 'optioner.js' ),
	},
	output: {
		path: path.resolve( __dirname, 'assets' ),
		filename: '[name].js',
		publicPath: './',
	},
	externals: {
		jquery: 'jQuery',
	},
	mode: 'development',
	devtool: 'inline-source-map',
	performance: {
		hints: false,
	},
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				loader: 'babel-loader',
				options: {
					presets: [ [ '@babel/preset-env' ] ],
				},
			},
			{
				test: /\.s[ac]ss$/i,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					{
						loader: 'postcss-loader',
						options: {
							postcssOptions: {
								plugins: [ [ 'postcss-preset-env' ] ],
							},
						},
					},
					'sass-loader',
				],
			},
		],
	},
	plugins: [ new MiniCssExtractPlugin( { filename: '[name].css' } ) ],
	optimization: {
		minimizer: [ new TerserPlugin( { extractComments: false } ), new CssMinimizerPlugin() ],
	},
};
