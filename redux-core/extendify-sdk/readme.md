# Extendify SDK

Notable additional tools used:

-   [Laravel Mix](https://laravel-mix.com/) - A webpack wrapper + dev server
-   [TailwindCSS](https://tailwindcss.com/) - A utility framework for styling. Also their [Headless UI](https://headlessui.dev/) package.
-   [Zustand](https://github.com/pmndrs/zustand) - A not-opinionated state management solution

<!-- TODO: Write doc on integration into other plugins -->

## Development guide

To get started, run `npm ci` then `npm run watch` to start a server. It's designed to proxy a server at `wordpres.test` to `localhost:3000` but we can extend that to be customizable via an environment variable or the like.

## Project structure

Below is a short description of the main files and directories of the application.
| Directory/File | Description |
| --- | --- |
| `/routes/api.php` | This file contains exposed REST endpoints. It's a custom router and essentially lets you define a route pattern and assign a class to handle it. |
| `/app` | This contains the server related files. It could even be renamed to `server` for clarity. |
| `/app/Controllers` | This directory contains controllers for various parts of the applications, like requesting templates or plugins. |
| `/app/App.php` | This file contains information about the application. The intent is to make it dynamically generated from the readme, configs, etc |
| `/app/ApiRouter.php` | An abstraction for making interfacing with the REST api a little less painless |
| `/app/Admin.php` | Loads in our script and styles to the admin area |
| `/app/Http.php` | Handles making external API calls. Every call exits here so it's a good place to add anything you want sent on every request |
| `/app/Plugin.php` | This file was taken from JetPack. Stylistically it doens't fit th rest of the application, so it proabably needs to be refactored |
| `/app/User.php` | Helper class to set up information about the user, like create a anonymous-ish uuid |
| `/public` | Assets are compiled to the build folder. Everything else should be static. The mix-manifest.json is a list of the compiled files. It's not in use today but we could use it for dynamic script versioning |
| `/loader.php` | This file is to be used by 3rd party plugins to decide who gets to load the SDK. The SDK while in develop mode doesn't use this file. |
| `/extendify-sdk.php` | This file decides whetehr to load the applocation, then loads it. This file shoudl probably remain withoug much logic. |
| `/bootstrap.php` | This file essentially does the loading. It could probably be combined with extendify-sdk, but it's an abstraction (and used to be much larger). |

## React application

The React part of the application starts by injecting a few buttons through the editor. You can find these in `buttons.js`. The app itself however is comprised of the main modal view, the api, some listeners to handle button clicks, and a "middleware" type section that will cycle through checks then take action based on pass/fail (this part was written quickly so will need tweaking with every addition).

TODO: complete this
| Directory/File | Description |
| --- | --- |
| `/src` | This is where the components are, as well as global listeners and helpers |

## Build files and configurations

Below is a just a few notes on the various build files we are currenly using
| Directory/File | Description |
| --- | --- |
| `/webpack.mix.js` | This handles the build process and starts the dev server. Handled by Laravel mix. Important to note here is that it includes a listing of the WP components we ignore and configure as global objects. TODO: maybe that can be dynamic instead of populating a list |
| `/tailwind.config.js` | This contains anything style related. Instead of creating a custom class, you should add the the config and let Tailwind build the class |
| `/phpcs.xml.dist` | This does some basic linting and security linting on PHP files. The config is highly customized. |
| `/.editorconfig` | Your editor should be able to auto adapt to these settings. |
| `/.eslintrc.js` | A very opinionated style guide. |

## Testing

Coming soon!
