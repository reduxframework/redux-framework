#!/usr/bin/env bash

if [[ "$TRAVIS_PULL_REQUEST" == "false" && "$TRAVIS_JOB_NUMBER" == *.1 ]]; then

  # update the mirror repo for composer/packagist
  echo -e "Pushing to composer mirror\n"
  git push --mirror https://${GH_TOKEN}@github.com/redux-framework/redux-framework.git

  # Re-Deploy the heroku demo app and pull the newest code
  echo -e "Starting dev demo push to Heroku\n"
  git clone git@heroku.com:redux-premium.git redux-premium
  cd redux-premium
  git remote add heroku git@heroku.com:redux-premium.git 
  git reset HEAD~; git push -f heroku master;
  cd ..
  rm -fr redux-premium

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

  

fi