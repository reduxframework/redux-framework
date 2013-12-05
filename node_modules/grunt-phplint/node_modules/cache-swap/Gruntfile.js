var matchdep = require("matchdep");

module.exports = function(grunt) {
	
	// load all grunt tasks
	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
	
	var cfg = {
		jshint2: {
			options: {
				jshint: {
					boss: true,
					node: true
				}
			},

			all: ["lib/**/*.js", "test/**/*.js", "Gruntfile.js"]
		},

		simplemocha: {
			options: {
				reporter: "spec",
				ui: "bdd",
				compilers: "coffee:coffee-script"
			},

			all: ["test/*_spec.js"]
		}
	};

	grunt.initConfig(cfg);

	grunt.registerTask("default", ["jshint2:all", "simplemocha:all"]);
};