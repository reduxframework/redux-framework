
module.exports = function(grunt) {
	var cfg = {
		jshint: {
			options: {
				boss: true,
				node: true
			},

			all: ["tasks/**/*.js", "test/**/*.js", "Gruntfile.js"]
		},

		simplemocha: {
			options: {
				reporter: "spec",
				ui: "bdd",
				compilers: "coffee:coffee-script"
			},

			all: ["test/*_spec.js"]
		},

		phplint: {
			good: ["test/rsrc/*-good.php"],
			good_nocache: {
				options: {
					cache: false
				},
				files: {
					src: ["test/rsrc/*-good.php"]
				}
			},
			bad: ["test/rsrc/*-fail.php"],

			explicit: {
				options: {
					phpCmd: "/usr/bin/php"
				},

				src: ["test/rsrc/*-good.php"]
			}
		}
	};

	grunt.initConfig(cfg);

	grunt.loadNpmTasks("grunt-contrib-jshint");
	grunt.loadNpmTasks("grunt-simple-mocha");
	grunt.loadTasks("./tasks");

	grunt.registerTask("default", ["jshint:all", "simplemocha:all", "phplint:good", "phplint:good_nocache"]);
};