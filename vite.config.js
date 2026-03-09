import { defineConfig } from 'vite';
import browserslistToEsbuild from 'browserslist-to-esbuild';

export default defineConfig( {
	base: './',
	build: {
		outDir: 'assets',
		emptyOutDir: true,
		sourcemap: false,
		target: browserslistToEsbuild(),
		rollupOptions: {
			input: 'resources/optioner.js',
			external: [ 'jquery' ],
			output: {
				entryFileNames: '[name].js',
				assetFileNames: '[name][extname]',
				globals: {
					jquery: 'jQuery',
				},
			},
		},
	},
} );
