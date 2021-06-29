// fileTransformer.js
const path = require( 'path' )

module.exports = {
	async process( src, filename, config, options ) { // eslint-disable-line
		return 'module.exports = ' + JSON.stringify( path.basename( filename ) ) + ';'
	},
}
