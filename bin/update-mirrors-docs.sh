language: php
php:
  - "5.2"
  - "5.4"
env:
  global:
    secure: "aDIYEmgxoF/+2vwPdvmbxFKMoz8pA9vtvemehyLNvH3LF05RgFiwNNv+7Lvy127p4Fxp3VBK+ZLKUEEL1gdpevzgni9EigpK8YZbIVHXRL3U+1eP9rcnjuGF9pKuB4kB2ivzoprcalg1ZDI9PnRYRDE4YUTHJiEN2MmLys1QWdc="
  cache:
    directories:
    - node_modules
  matrix:
  - WP_VERSION=latest WP_MULTISITE=0
  - WP_VERSION=latest WP_MULTISITE=1
install:
- npm install -g grunt-cli
- npm install
- find ReduxCore -type f | sort -u | xargs cat | md5sum > md5
before_script: bash bin/install-wp-tests.sh wordpress_test root '' localhost $WP_VERSION
script:
- phpunit
- grunt jshint
- grunt lintPHP
after_success:
  # Install the Heroku gem (or the Heroku toolbelt)
  #- gem install heroku
  # Add your Heroku git repo:
  #- git remote add heroku git@heroku.com:redux-premium.git
  # Turn off warnings about SSH keys:
  #- echo "Host heroku.com" >> ~/.ssh/config
  #- echo "   StrictHostKeyChecking no" >> ~/.ssh/config
  #- echo "   CheckHostIP no" >> ~/.ssh/config
  #- echo "   UserKnownHostsFile=/dev/null" >> ~/.ssh/config
  # Clear your current Heroku SSH keys:
  #- heroku keys:clear
  # Add a new SSH key to Heroku
  #- yes | heroku keys:add
  - ./bin/update-mirrors-docs.sh
after_script:
  #- ./bin/commit-uncompressed-files.sh
notifications:
  email:
    recipients:
    - dovy@reduxframework.com
    - kevin@reduxframework.com
    on_failure: always
branches:
  except:
  - gh-pages
  - setup
