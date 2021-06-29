#!/bin/bash

cd "$(dirname "$0")"
cd ../build
echo $(pwd)
git clone --depth 1 git@github.com:redux-templates/redux-templates.git --branch master master
mv master/.git redux-templates/
cd redux-templates
git add -A
git commit -m "Release"
git push origin master
