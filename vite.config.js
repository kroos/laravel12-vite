import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { path  } from 'path';

export default defineConfig({
	plugins: [
		laravel({
			input: [
				'resources/scss/app.scss',
				'resources/css/app.css',
				'resources/js/app.js'
			],
			refresh: true,
		}),
	],
	// define: {
	// 	jQuery: 'window.jQuery',
	// 	$: 'window.jQuery'
	// },
	build: {
		chunkSizeWarningLimit: 4000,
	},
});
