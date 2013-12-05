var os = require("os"),
	fs = require("fs"),
	crypto = require("crypto");

var grunt = require("grunt"),
	_ = grunt.util._,
	async = grunt.util.async,
	CacheSwap = require("cache-swap");

var PhpLintCommandWrapper = require("./PhpLintCommandWrapper");

var SWAP_CATEGORY = "linted";

function PhpLintTask(task) {
	_.bindAll(this, "_lintFile");

	this.options = task.options({
		spawnLimit: 10,
		swapPath: os.tmpDir(),
		cache: true
	});
	this.files = task.filesSrc;
	this.async = task.async;

	this.swap = new CacheSwap({
		tmpDir: this.options.swapPath,
		cacheDirName: "grunt-phplint"
	});
}

PhpLintTask.prototype = {

	run: function() {
		var self = this,
			done = this.async();

		async.forEachLimit(this.files, this.options.spawnLimit, this._lintFile, function(err) {
			if(err) {
				return grunt.fail.warn(err);
			}

			grunt.log.ok(self.files.length + " files php linted.");

			done();
		});
	},

	_lintFile: function(filePath, cb) {
		var self = this;

		this._checkCached(filePath, function(err, isCached, hash) {
			if(err) {
				return cb(err);
			}

			if(isCached){
				grunt.verbose.write(filePath.cyan + ": Not linting due to cache...");
				grunt.verbose.ok();
				return cb();
			}

			var linter = new PhpLintCommandWrapper(self.options);

			linter.lintFile(filePath, function(err, output) {
				// Get rid of trailing \n
				if(output && output.slice(-1) === "\n") { 
					output = output.slice(0, -1);
				}

				if(err) {
					grunt.verbose.write(filePath.cyan + ": " + output + "...");
					if (output === "") {
						output = err.message;
					}

					grunt.verbose.error();
					grunt.fail.warn(output);

					return cb(err);
				}

				// Skip the caching if it's not necessary.
				if(!self.options.cache) {
					grunt.verbose.write(filePath.cyan + ": " + output + "...");
					grunt.verbose.ok();
					return cb();
				}

				// Add to the cached swap so we don't have to lint unchanged files again
				self.swap.addCached(SWAP_CATEGORY, hash, "", function(err) {
					grunt.verbose.write(filePath.cyan + ": " + output + "...");
					if(err) {
						return cb(err);
					}

					grunt.verbose.ok();
					cb();		
				});
			});
		});
	},

	_checkCached: function(filePath, done) {
		var self = this;

		if(!this.options.cache) {
			return done(null, false);
		}

		fs.readFile(filePath, function(err, contents) {
			if(err) {
				return done(err);
			}

			var sha1 = crypto.createHash("sha1"),
				fileHash = sha1.update(contents.toString()).digest("hex");

			self.swap.hasCached(SWAP_CATEGORY, fileHash, function(isCached, cachedPath) {
				done(null, isCached, fileHash);
			});
		});
	},

	_clearCached: function(done) {
		this.swap.clear(null, done);
	}
};

module.exports = PhpLintTask;