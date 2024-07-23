/** @type {import('postcss-load-config').Config} */
const config = {
	plugins: [
		require( 'postcss-preset-env' ),
		require( 'postcss-import' ),
		require( 'postcss-custom-media' ),
		require( 'postcss-nested' ),
	],
};

module.exports = config;
