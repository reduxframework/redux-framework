grunt-phplint
=============

A Grunt task for linting your php.  A simple wrapper around the `php -l <filename>` command.

### Example Gruntfile

```javascript
var cfg = {
	phplint: {
		good: ["test/rsrc/*-good.php"],
		bad: ["test/rsrc/*-fail.php"]
	}
};

grunt.initConfig(cfg);

grunt.loadNpmTasks("grunt-phplint");

grunt.loadTasks("./tasks");

grunt.registerTask("default", ["phplint:good"]);
```

### Options

By default we assume that the `php` command is in your path, if not, you can specify the full path to php like the example below.  You can also pass additional flags, like '-lf'.

Lastly, if you want to limit the number of files we process at a time, set the spawnLimit.

```javascript
var cfg = {
	phplint: {
		options: {
			phpCmd: "/usr/bin/php", // Or "c:\EasyPHP-5.3.8.1\PHP.exe"
			phpArgs: {
				"-l": null
			},
			spawnLimit: 10
		},

		good: ["test/rsrc/*-good.php"],
		bad: ["test/rsrc/*-fail.php"]
	}
};
```

### Caching

As of version 0.0.3, we cache previously hinted files to improve performance.  This is done by taking a hash of the contents of a file and checking it against previously successful linted files content hashes.

By default, we will use the `os.tmpDir()` as the location for holding our swapped files (the files are empty, just placeholders).  To change this you can pass in a `swapPath` option:

```javascript
var cfg = {
	phplint: {
		options: {
			swapPath: "/some/crazy/path/to/temp/dir"
		},

		good: ["test/rsrc/*-good.php"],
		bad: ["test/rsrc/*-fail.php"]
	}
};
```

### License

Licensed under the MIT License, Copyright 2013 Jacob Gable