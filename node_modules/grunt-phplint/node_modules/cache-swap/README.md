cache-swap
==========

A lightweight file swap cache backed by temp files.

### Example

```javascript
var swap = new CacheSwap({
		cacheDirName: "HoganizeSwap"
	}),
	processTemplate = function(template, done) {
		var templateStr = template.content,
			templatePath = template.path,
			templateHash = files.shaIt(templateStr);

		swap.getCached("hoganize", templateHash, function(err, cached) {
			if(err) {
				return done(err);
			}

			var yeahbrotha,
				stringed;

			if(cached) {
				yeahbrotha = cached.contents;
				try {
					addToHoganized(yeahbrotha, templatePath);
				} catch(e){
					return done(e);
				}

				done();
			} else {
				yeahbrotha = self._compileTemplate(templateStr, templatePath);
				// Add the compiled template to the cache swap for next time.
				swap.addCached("hoganize", templateHash, yeahbrotha, function(err) {
					if(err) {
						return done(err);
					}

					try {
						addToHoganized(yeahbrotha, templatePath);
					} catch(e) {
						return done(e);
					}

					done();
				});
			}

		});
	};
```

### License

Licensed under the MIT License, Copyright 2013 Jacob Gable
