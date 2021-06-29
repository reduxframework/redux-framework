const externals = require( './externals' )
const rules = require( './rules' )
const plugins = require( './plugins' )
const path = require( 'path' )

module.exports = [{

	mode: 'development',

	devtool: 'cheap-module-source-map',

	entry: {
		'redux-templates': path.join( __dirname, '../redux-templates/src/index.js' )
	},

	output: {
		path: path.join( __dirname, '../redux-templates/assets/js' ),
		filename: '[name].js',
	},

	// Permit importing @wordpress/* packages.
	externals,

	resolve: {
		alias: {
			'~redux-templates': path.resolve( __dirname, '../redux-templates/src/' )
		}
	},

	optimization: {
		splitChunks: {
			cacheGroups: {
				vendor: {
					test: /node_modules/,
					chunks: "initial",
					name: "vendor",
					priority: 10,
					enforce: true
				}
			}
		},
	},

	// Clean up build output
	stats: {
		all: false,
		assets: true,
		colors: true,
		errors: true,
		performance: true,
		timings: true,
		warnings: true,
	},

	module: {
		strictExportPresence: true,
		rules,
	},

	plugins,
}]
