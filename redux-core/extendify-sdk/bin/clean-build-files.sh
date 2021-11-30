#!/bin/bash
if ! git config user.email | grep -q 'users.noreply.github.com'; then
    echo 'Cleaning build files before commit...'
    for i in $(dirname "pwd")/public/build/* ;do truncate -s 0 "$i";done
    truncate -s 0 $(dirname "pwd")/public/editorplus/editorplus.min.js
    git add -A $(dirname "pwd")/public/
fi
