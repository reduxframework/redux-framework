module.exports = [
	{
		test: /\.js$/,
		exclude: /(node_modules|bower_components)/,
		use: {
			loader: 'babel-loader',
			options: {
				// presets: ['es2015'],
				// Cache compilation results in ./node_modules/.cache/babel-loader/
				cacheDirectory: true,
				plugins: [
					'@babel/plugin-proposal-class-properties',
					'@babel/plugin-transform-destructuring',
					'@babel/plugin-proposal-object-rest-spread',
					[
						'@babel/plugin-transform-react-jsx',
						{
							pragma: 'wp.element.createElement',
						},
					]
				]
			}
		}
	},
	{
		test: /\.svg$/,
		use: ['@svgr/webpack'], // Settings are in .svgrrc
	},
	{
		test: /\.(png|jpg|gif)$/,
		use: [
			{
				loader: 'file-loader',
				options: {
					outputPath: 'images', // Dump images in dist/images.
					publicPath: 'dist/images', // URLs point to dist/images.
					regExp: /\/([^\/]+)\/([^\/]+)\/images\/(.+)\.(.*)?$/, // Gather strings for the output filename.
					name: '[1]-[2]-[3].[hash:hex:7].[ext]', // Filename e.g. block-accordion-basic.1b659fc.png
				},
			},
		],
	},
	// Help video snippets
	{
		test: /help\/videos\/[\w\d-_]+\.(mp4)$/,
		use: [
			{
				loader: 'file-loader',
				options: {
					outputPath: 'videos/help', // Dump images in dist/videos/help.
					publicPath: 'dist/videos/help', // URLs point to dist/videos/help.
					regExp: /\/videos\/(.+)\.(.*)?$/, // Gather strings for the output filename.
					name: '[1].[ext]', // Filename e.g. borders.mp4
				},
			},
		],
	},
	{
		test: /\.scss$/,
		use: ['style-loader', 'css-loader', 'sass-loader'],
	},
	{
		test: /\.(mp4)$/,
		exclude: /(help\/videos)/,
		use: [
			{
				loader: 'file-loader',
				options: {
					outputPath: 'videos', // Dump images in dist/images.
					publicPath: 'dist/videos', // URLs point to dist/images.
					regExp: /\/([^\/]+)\/([^\/]+)\/videos\/(.+)\.(.*)?$/, // Gather strings for the output filename.
					name: '[2]-[3].[hash:hex:7].[ext]', // Filename e.g. help-borders.1b659fc.mp4
				},
			},
		],
	},
]
