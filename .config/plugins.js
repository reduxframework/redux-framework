const ImageminPlugin = require( 'imagemin-webpack' )
const CreateFileWebpack = require('create-file-webpack')

module.exports = [
	new ImageminPlugin( {
		bail: false,
		cache: true,
		imageminOptions: {
			plugins: [
				[ 'pngquant', { quality: [ 0.5, 0.5 ] } ],
			]
		}
	} ),
	new CreateFileWebpack({
		// path to folder in which the file will be created
		path: './',
		// file name
		fileName: 'local_developer.txt',
		// content of the file
		content: ''
	})
]
