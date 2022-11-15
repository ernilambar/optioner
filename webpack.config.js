const path = require( 'path' );

const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
	...defaultConfig,
	entry: {
		optioner: path.resolve( __dirname, 'resources', 'optioner.js' ),
	},
	output: {
		path: path.resolve( __dirname, 'assets' ),
	},
};
