const externals = require( './externals' )
const rules = require( './rules' )
const plugins = require( './plugins' )
const path = require( 'path' )

module.exports = [{

	mode: 'production',

	devtool: 'hidden-source-map',

	entry: {
		'redux-templates': path.join( __dirname, '../redux-templates/src/index.js' )
	},

	output: {
		path: path.join( __dirname, '../redux-templates/assets/js' ),
		filename: '[name].min.js',
	},

	// Permit importing @wordpress/* packages.
	externals,

	resolve: {
		alias: {
			'~redux-templates': path.resolve( __dirname, '../redux-templates/src/' )
		}
	},

	// Optimize output bundle.
	optimization: {
		minimize: true,
		noEmitOnErrors: true,
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

	module: {
		strictExportPresence: true,
		rules,
	},

	plugins,
}]
