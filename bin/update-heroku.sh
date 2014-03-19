#!/usr/bin/env bash
if [[ "$TRAVIS_PULL_REQUEST" == "false" && "$TRAVIS_JOB_NUMBER" == *.1 ]]; then

    # Install the Heroku gem (or the Heroku toolbelt)
  	gem install heroku
  	# Add your Heroku git repo:
  	git remote add heroku git@heroku.com:redux-premium.git
  	# Turn off warnings about SSH keys:
  	echo "Host heroku.com" >> ~/.ssh/config
  	echo "   StrictHostKeyChecking no" >> ~/.ssh/config
  	echo "   CheckHostIP no" >> ~/.ssh/config
  	echo "   UserKnownHostsFile=/dev/null" >> ~/.ssh/config
  	# Clear your current Heroku SSH keys:
  	heroku keys:clear
  	# Add a new SSH key to Heroku
  	heroku keys:add

	# Re-Deploy the heroku demo app and pull the newest code
	git clone git@heroku.com:redux-premium.git
	cd redux-premium
	git reset HEAD~; git push -f heroku master;

	cd ..
	rm -fr redux-premium

fi