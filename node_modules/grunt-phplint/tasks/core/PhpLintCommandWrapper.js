
var spawn = require("child_process").spawn;

var grunt = require("grunt"),
	_ = grunt.util._,
	async = grunt.util.async;

var defaults = {
	phpCmd: "php",
	phpArgs: {
		"-l": null
	}
};

var PhpLintCommandWrapper = function(options) {
	this.options = _.defaults(options || {}, defaults);
};

PhpLintCommandWrapper.prototype = {

	lintFile: function(filePath, done) {
		var cmdArgs = this._parseOptions(this.options.phpArgs),
			phpLintCmd = spawn(this.options.phpCmd, cmdArgs.concat([filePath])),
			output = "";

		phpLintCmd.stdout.on("data", function(data) {
			output += data;
		});

		phpLintCmd.stderr.on("data", function(data) {
			done(new Error(data), output);
		});

		phpLintCmd.on("exit", function(code) {
			if(code !== 0) {
				return done(new Error("php returned non zero code: " + code), output);
			}

			done(null, output);
		});
	},

	//Parse an object into an array of command line arguments
	_parseOptions: function(opts) {
		var newOpts,
			currKey,
			pushVal;

		// Convert an object to an array of opts
		if(_.isObject(opts)) {
			newOpts = [];
			currKey = "";
			pushVal = function(v) {
				newOpts.push(currKey);
				if (v) {
					newOpts.push(v);
				}
			};

			_.each(opts, function(val, key) {
				currKey = key;

				if(_.isArray(val)) {
					// Push an array of values
					_.each(val, pushVal);
				} else {
					// Push a single value
					pushVal(val);
				}
			});

			opts = newOpts;
		}

		return opts;
	}

};

module.exports = PhpLintCommandWrapper;