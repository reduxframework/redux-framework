/**
 * Gulpfile.
 *
 * Gulp with WordPress.
 *
 * Implements:
 *      1. Live reloads browser with BrowserSync.
 *      2. CSS: Sass to CSS conversion, error catching, Autoprefixing, Sourcemaps,
 *         CSS minification, and Merge Media Queries.
 *      3. JS: Concatenates & uglifies Vendor and Custom JS files.
 *      4. Images: Minifies PNG, JPEG, GIF and SVG images.
 *      5. Watches files for changes in CSS or JS.
 *      6. Watches files for changes in PHP.
 *      7. Corrects the line endings.
 *      8. InjectCSS instead of browser page reload.
 *      9. Generates .pot file for i18n and l10n.
 *
 * @author Kevin Provance (@kprovance) & Ahmad Awais (@ahmadawais)
 * @version 2.0.0 - Rewrite for Gulp 4.0
 * @package ReduxFramework
 */

/**
 * Configuration.
 *
 * Project Configuration for gulp tasks.
 *
 * In paths you can add <<glob or array of globs>>. Edit the variables as per your project requirements.
 */

	// START Editing Project Variables.
	// Project related.
	// var project    = 'Redux Framework'; // Project Name.
	// var productURL = './'; .
var projectURL = 'http://127.0.0.1/redux-demo'; // Project URL. Could be something like localhost:8888.

// Translation related.
var text_domain = 'redux-framework';                         // Your textdomain here.
var destFile = 'redux-framework.pot';                     // Name of the transalation file.
var packageName = 'redux-framework';                         // Package name.
var bugReport = 'https://redux.io/contact';                // Where can users report bugs.
var lastTranslator = 'Kev Provance <kevin.provance@gmail.com>';           // Last translator Email ID.
var team = 'Redux.io <support@redux.io>';    // Team's Email ID.
var translatePath = './redux-core/languages/';                  // Where to save the translation files.

var styles = [
	{
		'path': './redux-core/assets/scss/vendor/elusive-icons/elusive-icons.scss',
		'dest': './redux-core/assets/css/vendor/'
	},
	{'path': './redux-core/assets/scss/vendor/select2/select2.scss', 'dest': './redux-core/assets/css/vendor/'},
	{'path': './redux-core/assets/scss/vendor/jquery-ui-1.10.0.custom.scss', 'dest': './redux-core/assets/css/vendor/'},
	{'path': './redux-core/assets/scss/vendor/nouislider.scss', 'dest': './redux-core/assets/css/vendor/'},
	{'path': './redux-core/assets/scss/vendor/qtip.scss', 'dest': './redux-core/assets/css/vendor/'},
	{'path': './redux-core/assets/scss/vendor/spectrum.scss', 'dest': './redux-core/assets/css/vendor/'},
	{'path': './redux-core/assets/scss/vendor/vendor.scss', 'dest': './redux-core/assets/css/'},
	{'path': './redux-core/assets/scss/color-picker.scss', 'dest': './redux-core/assets/css/'},
	{'path': './redux-core/assets/scss/media.scss', 'dest': './redux-core/assets/css/'},
	{'path': './redux-core/assets/scss/redux-admin.scss', 'dest': './redux-core/assets/css/'},
	{'path': './redux-core/assets/scss/rtl.scss', 'dest': './redux-core/assets/css/'},
	{'path': './redux-core/inc/welcome/css/redux-welcome.scss', 'dest': './redux-core/inc/welcome/css/'},
	{'path': './redux-core/inc/welcome/css/redux-banner.scss', 'dest': './redux-core/inc/welcome/css/'}
];

// JS Vendor related.
var jsVendorSRC = './redux-core/assets/js/vendor/*.js'; // Path to JS vendor folder.
var jsVendorDestination = './redux-core/assets/js/'; // Path to place the compiled JS vendors file.
var jsVendorFile = 'redux-vendors'; // Compiled JS vendors file name.

// JS Custom related.
var jsReduxSRC = './redux-core/assets/js/redux.js'; // Path to redux.js script.
var jsReduxDestination = './redux-core/assets/js/'; // Path to place the compiled JS custom scripts file.
var jsReduxFile = 'redux'; // Compiled JS custom file name.

// Images related.
var imagesSRC = './redux-core/assets/img/raw/**/*.{png,jpg,gif,svg}'; // Source folder of images which should be optimized.
var imagesDestination = './redux-core/assets/img/'; // Destination folder of optimized images. Must be different from the imagesSRC folder.

// Watch files paths.
// var styleWatchFiles      = './redux-core/assets/css/**/*.scss'; // Path to all *.scss files inside css folder and inside them.
// var vendorJSWatchFiles   = './redux-core/assets/js/vendor/*.js'; // Path to all vendor JS files.
var reduxJSWatchFiles = './redux-core/assets/js/redux/*.js'; // Path to all custom JS files.
var projectPHPWatchFiles = './**/*.php'; // Path to all PHP files.

// Browsers you care about for autoprefixing.
// Browserlist https://github.com/ai/browserslist.
var AUTOPREFIXER_BROWSERS = ['last 2 version', '> 1%', 'ie > 10', 'ie_mob > 10', 'ff >= 30', 'chrome >= 34', 'safari >= 7', 'opera >= 23', 'ios >= 7', 'android >= 4', 'bb >= 10'];

// STOP Editing Project Variables.

/**
 * Load Plugins.
 *
 * Load gulp plugins and assing them semantic names.
 */
var gulp = require( 'gulp' ); // Gulp of-course.

// CSS related plugins.
var sass = require( 'gulp-sass' )(require('node-sass')); // Gulp pluign for Sass compilation.
//sass.compiler = require( 'node-sass' );

var minifycss = require( 'gulp-uglifycss' ); // Minifies CSS files.
var autoprefixer = require( 'gulp-autoprefixer' ); // Autoprefixing magic.
var mmq = require( 'gulp-merge-media-queries' ); // Combine matching media queries into one media query definition.

// JS related plugins.
var concat = require( 'gulp-concat' ); // Concatenates JS files.
var uglify = require( 'gulp-uglify' ); // Minifies JS files.
var jshint = require( 'gulp-jshint' );
var jscs = require( 'gulp-jscs' );

// Image realted plugins.
var imagemin = require( 'gulp-imagemin' ); // Minify PNG, JPEG, GIF and SVG images with imagemin.

// Utility related plugins.
var rename = require( 'gulp-rename' );                // Renames files E.g. style.css -> style.min.css.
var lineec = require( 'gulp-line-ending-corrector' ); // Consistent Line Endings for non UIX systems. Gulp Plugin for Line Ending Corrector (A utility that makes sure your files have consistent line endings).
var filter = require( 'gulp-filter' );                // Enables you to work on a subset of the original files by filtering them using globbing.
var sourcemaps = require( 'gulp-sourcemaps' );            // Maps code in a compressed file (E.g. style.css) back to itâ€™s original position in a source file.
var browserSync = require( 'browser-sync' ).create();      // Reloads browser and injects CSS. Time-saving synchronised browser testing.
// var reload       = browserSync.reload;                  // For manual browser reload.
var wpPot = require( 'gulp-wp-pot' );               // For generating the .pot file.
var sort = require( 'gulp-sort' );                 // Recommended to prevent unnecessary changes in pot-file.
var fs = require( 'fs' );
var path = require( 'path' );
var merge = require( 'merge-stream' );
var sassPackager = require( 'gulp-sass-packager' );
var composer = require('gulp-composer');
var del = require('del');

/**
 * Task: `browser-sync`.
 *
 * Live Reloads, CSS injections, Localhost tunneling.
 *
 * This task does the following:
 *    1. Sets the project URL
 *    2. Sets inject CSS
 *    3. You may define a custom port
 *    4. You may want to stop the browser from openning automatically
 */
gulp.task(
	'browser-sync',
	function() {
		browserSync.init(
			{
				// For more options.
				// @link http://www.browsersync.io/docs/options.
				// Project URL.
				proxy: projectURL,

				// `true` Automatically open the browser with BrowserSync live server.
				// `false` Stop the browser from automatically opening.
				open: true,

				// Inject CSS changes.
				// Commnet it to reload browser for every CSS change.
				injectChanges: true,

				// Use a specific port (instead of the one auto-detected by Browsersync).
				// port: 7000.
			}
		);
	}
);

function getFolders( dir ) {
	return fs.readdirSync( dir ).filter(
		function( file ) {
			return fs.statSync( path.join( dir, file ) ).isDirectory();
		}
	);
}

function process_scss( source, dest, add_min ) {

	var process = gulp.src( source, {allowEmpty: true} )
	.pipe( sourcemaps.init() )
	.pipe(
		sass(
			{
				indentType: 'tab',
				indentWidth: 1,
				errLogToConsole: true,

				// outputStyle: 'compact',
				// outputStyle: 'compressed',
				// outputStyle: 'nested'.
				outputStyle: 'compact',
				precision: 10
			}
		)
	)
	.on( 'error', console.error.bind( console ) )
	.pipe( sourcemaps.write( {includeContent: false} ) )
	.pipe( sourcemaps.init( {loadMaps: true} ) )
	.pipe( autoprefixer( AUTOPREFIXER_BROWSERS ) )
	.pipe( sourcemaps.write( './' ) )
	.pipe( lineec() )                                       // Consistent Line Endings for non UNIX systems.
	.pipe( gulp.dest( dest ) ).pipe( filter( '**/*.css' ) ) // Filtering stream to only css files.
	.pipe( mmq( {log: true} ) )                     // Merge Media Queries only for .min.css version.
	.pipe( browserSync.stream() );                          // Reloads style.css if that is enqueued.

	if ( add_min ) {
		process = process.pipe( rename( {suffix: '.min'} ) ).pipe(
			minifycss(
				{
					maxLineLen: 0
				}
			)
		)
		.pipe( lineec() )               // Consistent Line Endings for non UNIX systems.
		.pipe( gulp.dest( dest ) )
		.pipe( filter( '**/*.css' ) )   // Filtering stream to only css files.
		.pipe( browserSync.stream() );  // Reloads style.min.css if that is enqueued.
	}

	return process;
}

/**
 * Task: `styles`.
 *
 * Compiles Sass, Autoprefixes it and Minifies CSS.
 *
 * This task does the following:
 *    1. Gets the source scss file
 *    2. Compiles Sass to CSS
 *    3. Writes Sourcemaps for it
 *    4. Autoprefixes it and generates style.css
 *    5. Renames the CSS file with suffix .min.css
 *    6. Minifies the CSS file and generates style.min.css
 *    7. Injects CSS or reloads the browser via browserSync
 */
function reduxStyles() {

	// Core styles.
	var core = styles.map(
		function( file ) {
			return process_scss( file.path, file.dest, true );
		}
	);

	var lib_dirs = getFolders( 'redux-core/inc/lib/' );
	lib_dirs.map(
		function( folder ) {
			var the_path = './redux-core/inc/lib/' + folder + '/';
			folder       = folder.replace( '_', '-' );

			return process_scss( the_path + folder + '.scss', the_path );
		}
	);

	// Colors.
	var color_dirs = getFolders( 'redux-core/assets/scss/colors/' );
	var colors = color_dirs.map(
		function( folder ) {
			var the_path = './redux-core/assets/css/colors/' + folder + '/';
			return process_scss( './redux-core/assets/scss/colors/' + folder + '/colors.scss', the_path, true );
		}
	);

	// Fields.
	var field_dirs = getFolders( 'redux-core/inc/fields/' );
	var fields = field_dirs.map(
		function( folder ) {
			var the_path = './redux-core/inc/fields/' + folder + '/';
			folder = folder.replace( '_', '-' );
			return process_scss( the_path + 'redux-' + folder + '.scss', the_path );
		}
	);

	// Extensions.
	var extension_dirs = getFolders( 'redux-core/inc/extensions/' );
	var extensions = extension_dirs.map(
		function( folder ) {
			var the_path = './redux-core/inc/extensions/' + folder + '/';
			folder = folder.replace( '_', '-' );

			return process_scss( the_path + 'redux-extension-' + folder + '.scss', the_path );
		}
	);

	var extension_fields = extension_dirs.map(
		function( folder ) {
			var the_path = './redux-core/inc/extensions/' + folder + '/' + folder + '/';
			folder = folder.replace( '_', '-' );
			return process_scss( the_path + 'redux-' + folder + '.scss', the_path );
		}
	);

	var redux_files = gulp.src(
		['./redux-core/inc/fields/**/*.scss', './redux-core/inc/extensions/*.scss', './redux-core/inc/extensions/**/*.scss'],
		{allowEmpty: true}
	)

	.pipe( sassPackager( {} ) )
	.pipe( concat( 'redux-fields.min.scss' ) )
	.pipe(
		sass(
			{
				errLogToConsole: true,
				outputStyle: 'compressed',
				// outputStyle: 'compact',
				// outputStyle: 'nested',
				// outputStyle: 'expanded'.
				precision: 10
			}
		)
	)
	.on( 'error', console.error.bind( console ) )
	.pipe( sourcemaps.write( {includeContent: false} ) )
	.pipe( sourcemaps.init( {loadMaps: true} ) )
	.pipe( autoprefixer( AUTOPREFIXER_BROWSERS ) )
	.pipe( sourcemaps.write( './' ) )
	.pipe( lineec() )                                       // Consistent Line Endings for non UNIX systems.
	.pipe( gulp.dest( 'redux-core/assets/css/' ) );

	return merge( core, colors, fields, extensions, extension_fields, redux_files );
}

function extFieldJS( done ) {

	var field_dirs = getFolders( 'redux-core/inc/extensions' );
	field_dirs.map(
		function( folder ) {
			var the_path = './redux-core/inc/extensions/' + folder + '/' + folder + '/';

			folder = folder.replace( '_', '-' );

			gulp.src( the_path + 'redux-' + folder + '.js', {allowEmpty: true} )
			.pipe( jshint() )
			.pipe( jshint.reporter( 'default' ) )
			.pipe( jscs() )
			.pipe( jscs.reporter() )

			.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
			.pipe( gulp.dest( the_path ) )
			.pipe(
				rename(
					{
						basename: 'redux-' + folder, suffix: '.min'
					}
				)
			)
			.pipe( uglify() )
			.pipe( lineec() )
			.pipe( gulp.dest( the_path ) );
		}
	);

	done();
}

function reduxLibJS ( done ) {
	var field_dirs = getFolders( 'redux-core/inc/lib' );

	field_dirs.map(
		function( folder ) {
			var the_path = './redux-core/inc/lib/' + folder + '/';

			gulp.src( the_path + '/' + folder + '.js' )
			.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
			.pipe( gulp.dest( the_path ) )
			.pipe(
				rename(
					{
						basename: folder,
						suffix: '.min'
					}
				)
			)
			.pipe( uglify() )
			.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
			.pipe( gulp.dest( the_path ) );
		}
	);

	done();
}

function extJS( done ) {

	var field_dirs = getFolders( 'redux-core/inc/extensions' );
	field_dirs.map(
		function( folder ) {
			var the_path = './redux-core/inc/extensions/' + folder + '/';

			folder = folder.replace( '_', '-' );

			if ( folder === 'metaboxes-lite' ) {
				folder = 'metaboxes';
			}

			gulp.src( the_path + 'redux-extension-' + folder + '.js', {allowEmpty: true} )
			.pipe( jshint() )
			.pipe( jshint.reporter( 'default' ) )
			.pipe( jscs() )
			.pipe( jscs.reporter() )

			.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
			.pipe( gulp.dest( the_path ) )
			.pipe(
				rename(
					{
						basename: 'redux-extension-' + folder, suffix: '.min'
					}
				)
			)
			.pipe( uglify() )
			.pipe( lineec() )
			.pipe( gulp.dest( the_path ) );
		}
	);

	done();
}

function fieldsJS( done ) {

	var field_dirs = getFolders( 'redux-core/inc/fields' );
	field_dirs.map(
		function( folder ) {
			var the_path = './redux-core/inc/fields/' + folder + '/';

			folder = folder.replace( '_', '-' );

			gulp.src( the_path + '/redux-' + folder + '.js', {allowEmpty: true} )
			.pipe( jshint() )
			.pipe( jshint.reporter( 'default' ) )
			.pipe( jscs() )
			.pipe( jscs.reporter() )

			.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
			.pipe( gulp.dest( the_path ) )
			.pipe(
				rename(
					{
						basename: 'redux-' + folder, suffix: '.min'
					}
				)
			)
			.pipe( uglify() )
			.pipe( lineec() )
			.pipe( gulp.dest( the_path ) );
		}
	);

	done();
}

/**
 * Task: `reduxCombineModules`.
 *
 * Concatenate redux.js modules into master redux.js file.
 * reduxJS task is dependant upon this task to properly compete.
 *
 * This task does the following:
 *     1. Gets the source folder for Redux JS javascrip modules.
 *     2. Concatenates all the files and generates redux.js
 */
function reduxCombineModules( done ) {

	gulp.src( jsReduxSRC )
	.pipe( jshint() )
	.pipe( jshint.reporter( 'default' ) )
	.pipe( jscs() )
	.pipe( jscs.reporter() )
	.pipe(
		rename(
			{
				basename: jsReduxFile,
				suffix: '.min'
			}
		)
	)
	.pipe( uglify() )
	.pipe( lineec() )
	.pipe( gulp.dest( jsReduxDestination ) );

	done();
}

function reduxMedia( done ) {

	gulp.src( './redux-core/assets/js/media/media.js' )
	.pipe( jshint() )
	.pipe( jshint.reporter( 'default' ) )
	.pipe( jscs() )
	.pipe( jscs.reporter() )

	.pipe(
		rename(
			{
				basename: 'media',
				suffix: '.min'
			}
		)
	)
	.pipe( uglify() )
	.pipe( lineec() )
	.pipe( gulp.dest( './redux-core/assets/js/media/' ) );

	done();
}

function reduxSpinner( done ) {

	gulp.src( './redux-core/inc/fields/spinner/vendor/jquery.ui.spinner.js' )
	.pipe( jshint() )
	.pipe( jshint.reporter( 'default' ) )
	.pipe( jscs() )
	.pipe( jscs.reporter() )

	.pipe(
		rename(
			{
				basename: 'jquery.ui.spinner',
				suffix: '.min'
			}
		)
	)
	.pipe( uglify() )
	.pipe( lineec() )
	.pipe( gulp.dest( './redux-core/inc/fields/spinner/vendor/' ) );

	done();
}

/**
 * Task: `reduxJS`.
 *
 * Concatenate redux.js modules into master file, then minifies & uglifies.
 *
 * This task does the following:
 *     1. Runs reduxCombineModules task
 *     2. Renames redux.js with suffix .min.js
 *     3. Uglifes/Minifies the JS file and generates redux.min.js
 */
function reduxJS( done ) {

	gulp.src( reduxJSWatchFiles )
	.pipe( jshint() )
	.pipe( jshint.reporter( 'default' ) )
	.pipe( jscs() )
	.pipe( jscs.reporter() )

	.pipe( concat( jsReduxFile + '.js' ) )
	.pipe( lineec() )
	.pipe( gulp.dest( jsReduxDestination ) );

	done();
}

/**
 * Task: `vendorJS`.
 *
 * Concatenate and uglify vendor JS scripts.
 *
 * This task does the following:
 *     1. Gets the source folder for JS vendor files
 *     2. Concatenates all the files and generates vendors.js
 *     3. Renames the JS file with suffix .min.js
 *     4. Uglifes/Minifies the JS file and generates vendors.min.js
 */
function vendorsJS( done ) {

	gulp.src( jsVendorSRC )
	.pipe( concat( jsVendorFile + '.js' ) )
	.pipe( lineec() ) // Consistent Line Endings for non UNIX systems.
	.pipe( gulp.dest( jsVendorDestination ) )
	.pipe(
		rename(
			{
				basename: jsVendorFile,
				suffix: '.min'
			}
		)
	)
	.pipe( uglify() )
	.pipe( lineec() )
	.pipe( gulp.dest( jsVendorDestination ) );

	done();
}

/**
 * Task: `images`.
 *
 * Minifies PNG, JPEG, GIF and SVG images.
 *
 * This task does the following:
 *     1. Gets the source of images raw folder
 *     2. Minifies PNG, JPEG, GIF and SVG images
 *     3. Generates and saves the optimized images
 *
 * This task will run only once, if you want to run it
 * again, do it with the command `gulp images`.
 */
function reduxImages( done ) {

	gulp.src( imagesSRC )
	.pipe(
		imagemin(
			{
				progressive: true,
				optimizationLevel: 3, // 0-7 low-high
				interlaced: true,
				svgoPlugins: [{removeViewBox: false}]
			}
		)
	)
	.pipe( gulp.dest( imagesDestination ) );

	done();
}

/**
 * WP POT Translation File Generator.
 *
 * * This task does the following:
 *     1. Gets the source of all the PHP files
 *     2. Sort files in stream by path or any custom sort comparator
 *     3. Applies wpPot with the variable set at the top of this file
 *     4. Generate a .pot file of i18n that can be used for l10n to build .mo file
 */
function translate() {
	return gulp.src( projectPHPWatchFiles )
	.pipe( sort() )
	.pipe(
		wpPot(
			{
				domain: text_domain,
				destFile: destFile,
				package: packageName,
				bugReport: bugReport,
				lastTranslator: lastTranslator,
				team: team
			}
		)
	)
	.pipe( gulp.dest( translatePath + '/' + destFile ) );
}

function installFontawesome( done ){
	composer(  'update' );

	del([
		'redux-core/assets/font-awesome/*.*',
		'redux-core/assets/font-awesome/js',
		'redux-core/assets/font-awesome/metadata',
		'redux-core/assets/font-awesome/js-packages',
		'redux-core/assets/font-awesome/less',
		'redux-core/assets/font-awesome/otfs',
		'redux-core/assets/font-awesome/scss',
		'redux-core/assets/font-awesome/sprites',
		'redux-core/assets/font-awesome/svgs',
		'redux-core/assets/font-awesome/css/brands.*',
		'redux-core/assets/font-awesome/css/fontawesome.*',
		'redux-core/assets/font-awesome/css/regular.*',
		'redux-core/assets/font-awesome/css/solid.*',
		'redux-core/assets/font-awesome/css/svg-with-js.*',
		'redux-core/assets/font-awesome/css/v4-font-face.*',
		'redux-core/assets/font-awesome/css/v5-font-face.*'
	]);

	done();
}

/**
 * Tasks
 */
gulp.task( 'styles', reduxStyles );
gulp.task( 'fieldsJS', gulp.series( fieldsJS, reduxLibJS, extJS, extFieldJS, reduxMedia, reduxSpinner ) );
gulp.task( 'media', reduxMedia );
gulp.task( 'reduxJS', gulp.series( reduxJS, reduxCombineModules, reduxMedia ) );
gulp.task( 'vendorsJS', vendorsJS );
gulp.task( 'images', reduxImages );
gulp.task( 'translate', translate );
gulp.task( 'composer', installFontawesome );

function cleanBuild() {
	return gulp.src( './build', {read: false, allowEmpty: true} )
	.pipe( clean() );
}

function makeBuild() {
	return gulp.src( [
		'./**/*.*',
		'!./assets/js/*.dev.*',
		'!./node_modules/**/*.*',
		'!./src/**/*.*',
		'!./.wordpress-org/**/*.*',
		'!./.github/**/*.*',
		'!./build/**/*.zip',
		'!./gulpfile.js',
		'!./yarn.lock',
		'!./yarn-error.log',
		'!.babelrc',
		'!./languages/**/*',
		'!.eslintrc',
		'!./package-lock.json',
		'!./composer-lock.json',
		'!./composer.lock',
		'!./webpack.*.js',
		'!./jest.config.js',
		'!./**/jest.config.js',
		'!./**/babel.config.js',
		'!./package.json',
		'!./composer.json',
		'!./ruleset.xml',
		'!./codestyles/*',
		'!./local_developer.txt',
		'!./jsconfig.json',
		'!./vendor/**/*',
		'!./tests/**/*',
		'!./redux-core/assets/scss/**/*',
		'!./redux-core/assets/img/raw/**/*',
		'!./redux-templates/src/**/*',
		'!./redux-templates/classes/*.json',
	] ).pipe( gulp.dest( 'build/' ) );
}

function admin_css() {
	return gulp.src( ['./redux-templates/src/scss/*.scss'] )
	.pipe( sass() )
	.pipe( autoprefixer( {
		cascade: false
	} ) )
	.pipe( minifyCSS() )
	.pipe( concat( 'admin.min.css' ) )
	.pipe( gulp.dest( 'redux-templates/assets/css/' ) );
}


function minify_js() {
	return gulp.src( ['./build/redux-templates/assets/js/*.js'] )
	.pipe( minifyJS( {
		ext: {
			src: '.js',
			min: '.min.js'
		},
		exclude: ['tasks'],
		ignoreFiles: ['redux-templates.min.js', '*-min.js', '*.min.js']
	} ) )
	.pipe( gulp.dest( ['./build/redux-templates/assets/js/'] ) );

}


function makeZip() {
	return gulp.src( './build/**/*.*' )
	.pipe( zip( './build/redux.zip' ) )
	.pipe( gulp.dest( './' ) );
}

gulp.task( 'makeBuild', makeBuild );
gulp.task( 'admin_css', admin_css );
gulp.task( 'minify_js', minify_js );
gulp.task( 'cleanBuild', cleanBuild );
gulp.task( 'makeZip', makeZip );

gulp.task( 'templates', gulp.series(
	'cleanBuild',
	'makeBuild',
	'admin_css',
	'minify_js',
	'makeZip'
) );
/**
 * Watch Tasks.
 *
 * Watches for file changes and runs specific tasks.
 */
gulp.task(
	'default',
	gulp.series(
		'styles',
		'vendorsJS',
		'reduxJS',
		'fieldsJS',
		'composer'
	)
);
