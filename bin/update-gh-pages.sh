#!/usr/bin/env bash

if [[ "$TRAVIS_PULL_REQUEST" == "false" && "$TRAVIS_JOB_NUMBER" == *.1 ]]; then

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

  # Re-Deploy the heroku demo app and pull the newest code
  gem install heroku
  ruby travis_deployer.rb
  heroku keys:clear
  heroku keys:add
  heroku plugins:install https://github.com/heroku/heroku-repo.git
  heroku repo:rebuild -a redux-premium

fi