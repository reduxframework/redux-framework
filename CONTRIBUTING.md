First and foremost, PLEASE check to see if you are using the latest version of Redux by checking the repository.  If you plan on reporting an issue with any version BUT the latest version, we are going to ask you to upgrade to the latest code base anyway to see if your issue persists.  Please save us and yourself some time by taking this simple step first.  Thanks!

When submitting a ticket, please make sure you include the following information,  It is critical, and required.  As Team Redux has not yet earned their certificates in mind reading, we need you to provide for us the following information.  :)

1. The version of Redux you are using.  Please do not say 'the latest version' as what you perceive as the latest and what might be the latest could easily be two different things.  At the bottom of the Redux options panel is a four digit version number.  Please include it.

2. The version of Wordpress you are running.

3. Please indicate if you are using Redux in dev_mode.

4. If you are having difficultly with a particular field, please include the config code for that entire field.  If the field is dependent on other fields in the section (for example, required arguments are used), then please include the entire section.

5. If you are having difficulty with configuration, please specify if you are using a standalone theme, a child theme, a plugin, etc.

6. Please indicate if you are using Redux as a plugin or embedded in your project.

7. Please check your browser's output console.  If there are any javascript errors pertaining to redux, please list them, including the module/file they occurred in and the line number

The follow only applies if Redux is not loading properly:

8. If you are using Redux embedded, please specify the location in which Redux is installed, where you config is located, and the lines of code you are using to initialize Redux and your config.

The way in which we diagnose bugs or config difficulties is to attempt to recreate them on our end.  This is why we need the very specific information.  Once we are able to confirm the issue, we will either push an update, or assist you in correcting any mistakes in your config.

What we do NOT do is debug your code.  We support the Redux code and the way in which the config is put together.  Any other issue pertaining to your project is your own, or we might be able to assist with premium support.

- Team Redux

## Running the tests

The tests are built using [wordpress's make subversion repository](https://make.wordpress.org/core/handbook/automated-testing/)

`/var/www/wordpress-develop` as the destination for the core test files.
First download the wordress core tests repository, for these files.

```bash
cd /var/www
svn co http://develop.svn.wordpress.org/trunk/ wordpress-develop
```

In the newly created `/var/www/wordpress-develop` directory rename
`wp-tests-config-sample.php` to `wp-tests-config.php`. Now add your database
details to the new file:
```php
// WARNING WARNING WARNING!
// These tests will DROP ALL TABLES in the database with the prefix named below.
// DO NOT use a production database or one that is shared with something else.

define( 'DB_NAME', 'wordpress-tests' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'passowrd' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );
```
 - <b>n.b.</b> you may need to create the database first.
 - <b>n.b. n.b.</b> also note that the database used will be emptied on each run.

Set the `WP_TESTS_DIR` environment variable so that the `redux-framework` test bootstrap file can find the wordpress core tests:
```bash
export WP_TESTS_DIR='/var/www/wordpress-develop/tests/phpunit/includes/'
```

You should now be able to run the `redux-framework` unit tests:
```bash
redux-framework$ phpunit
Welcome to the TIVWP Test Suite
Version: 1.0

Tests folder: /var/www/wordpress-develop/tests/phpunit/includes/

Installing...
...
Configuration read from
redux-framework/phpunit.xml
...
```
