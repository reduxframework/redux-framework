var PhpLintTask = require("./core/PhpLintTask");

module.exports = function(grunt) {

	grunt.registerMultiTask("phplint", "Run PHP lint on your php files", function() {
		var task = new PhpLintTask(this);

		task.run();
	});
};