#!/usr/bin/env bash
#&& "$TRAVIS_PHP_VERSION" >= 5.3 
if [[ "$TRAVIS_PULL_REQUEST" == "false" && "$TRAVIS_JOB_NUMBER" == *.1 ]]; then

	# Update the mirror repo for composer/packagist
	git push --mirror https://${GH_TOKEN}@github.com/redux-framework/redux-framework.git

	# Re-Deploy the heroku demo app and pull the newest code
	git clone git@heroku.com:redux-premium.git $HOME/redux-premium
	cd redux-premium
	git reset HEAD~; git push -f heroku master;

	cd $HOME

#	cd ..
#	rm -fr $HOME/redux-premium

	echo -e "Starting to update documentation\n"

	# Make sure we don't have any old files
	rm -fr $HOME/docs

	# Install phpDocumentor
	pear channel-discover pear.phpdoc.org
	pear install phpdoc/phpDocumentor
	pear install Image_GraphViz
	phpenv rehash #Have to run this for travis

	# Generate the docs
	grunt phpdocumentor

	# Copy the github CNAME file to the docs
	cp bin/CNAME docs/

	# Publish the docs to gh-pages
	grunt gh-pages:travis

	# Clean out the docs directory
	#git rm -fr $HOME/docs/  


fi