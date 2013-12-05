var fs = require("fs"),
	path = require("path");

var should = require("should");

var CacheSwap = require("../lib/cacheSwap");

describe("cacheSwap", function() {
	var swap,
		category = "testcat",
		hash = "1234",
		contents = "Some test contents";

	beforeEach(function(done) {
		swap = new CacheSwap();
		swap.clear(category, function(err) {
			if (err) { 
				throw err;
			}

			done();
		});
	});

	it("getCachedFilePath", function() {
		var	expect = path.join(swap.options.tmpDir, swap.options.cacheDirName, category, hash);

		swap.getCachedFilePath(category, hash).should.equal(expect);
	});

	it("addCached", function(done) {
		swap.addCached(category, hash, contents, function(err, filePath) {
			if(err) {
				throw err;
			}

			fs.stat(filePath, function(err, stats) {
				if(err) {
					throw err;
				}

				var mode = '0' + (stats.mode & parseInt('777', 8)).toString(8);
				mode.should.equal('0777');

				fs.readFile(filePath, function(err, tmpContents) {
					if(err) {
						throw err;
					}

					tmpContents = tmpContents.toString();

					tmpContents.should.equal(contents);

					done();
				});
			});
		});
	});

	it("getCached (doesn't exist)", function(done) {
		swap.getCached(category, hash, function(err, details) {
			if(err) {
				throw err;
			}

			should.not.exist(details);
			done();
		});
	});

	it("getCached (does exist)", function(done) {
		swap.addCached(category, hash, contents, function(err, filePath) {
			swap.getCached(category, hash, function(err, details) {
				if(err) {
					throw err;
				}

				should.exist(details);
				details.contents.should.equal(contents);
				details.path.should.equal(filePath);
				done();
			});
		});
	});

	it("hasCached (doesn't exist)", function(done) {
		swap.hasCached(category, hash, function(exists, filePath) {
			exists.should.equal(false);
			should.not.exist(filePath);

			done();
		});
	});

	it("hasCached (does exist)", function(done) {
		swap.addCached(category, hash, contents, function(err, filePath) {
			swap.hasCached(category, hash, function(exists, existsFilePath) {
				exists.should.equal(true);
				existsFilePath.should.equal(filePath);

				done();
			});
		});
	});
});