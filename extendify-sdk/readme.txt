=== Extendify ===
Contributors: extendify, richtabor, kbat82, clubkert, arturgrabo
Tags: page builder, editor, patterns, drag-and-drop, blocks, visual editor, wysiwyg, design, website builder, landing page builder, front-end builder
Requires at least: 5.4
Tested up to: 5.8.2
Stable tag: 0.1.0
Requires PHP: 5.6
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Extendify is the platform of site design and creation tools for people that want to build a beautiful WordPress website with a library of patterns and full page layouts for the Gutenberg block editor.

== Description ==

Make a beautiful WordPress website easier, and faster, than ever before with Extendify's suite of design and publishing tools.

= A library of web components =
Our library of reusable website patterns and full page layouts can be assembled to rapidly build beautiful websites. These best-in-class templates enable you to drag, drop and publish in WordPress, without a single line of code.

= Built for your theme and your workflow =
Extendify is a theme-agnostic design experience platform that works with your Gutenberg-friendly WordPress theme — instantly leveling-up your editing and publishing flow today.

= Like Extendify? =
- Follow us on [Twitter](https://www.twitter.com/extendifyinc).
- Rate us on [WordPress](https://wordpress.org/support/plugin/extendify/reviews/?filter=5/#new-post) :)

= Privacy =
Extendify is a SaaS (software as a service) connector plugin that uses a custom API to fetch block patterns and page layouts from our servers. API requests are only made when a user clicks on the Library button. In order to provide and improve this service, Extendify passes site data along with an API request, including:

* Browser
* Referring site
* Category selection
* WP language
* Active theme
* Active plugins
* Anonymized UUID
* Anonymized IP address

By activating the Extendify plugin and accessing the library, you agree to our [privacy policy](https://extendify.com/privacy-policy) and [terms of service](https://extendify.com/terms-of-service).

== Installation ==

1. Install using the WordPress plugin installer, or extract the zip file and drop the contents into the `wp-content/plugins/` directory of your WordPress site.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Edit or create a new page on your site
4. Press the 'Library' button at the top left of the editor header
5. Browse and import beautiful patterns and full page layouts; click one to add it directly to the page.

For documentation and tutorials, read our [guides](https://extendify.com/guides/?utm_source=wp-repo&utm_medium=link&utm_campaign=readme).

== Frequently Asked Questions ==

**Can I use Extendify with any theme?**
Yes! You can create a beautiful site in just a few clicks with just about any Gutenberg-friendly WordPress theme.

**Can I use Extendify with WooCommerce?**
Yes! Extendify is compatible with WooCommerce. You’ll need to install and configure WooCommerce separately to set up eCommerce functionality.

**Is Extendify free?**
Extendify is a free plugin available through the WordPress.org directory that allows users to extend the power of the Gutenberg Block Editor. Each user receives a limited number of imports completely free. We offer a paid subscription for folks who want unlimited access to all of our beautiful patterns and page layouts.

**What is Extendify Pro?**
Extendify Pro gives you unlimited access to the entire library of our patterns and page layouts, without restrictions.

**Will Extendify slow down my website?**
Nope! Extendify imports lightweight block-based content that is served directly from your WordPress site. Our templates use the latest WordPress technologies, leveraging the best that Gutenberg has to offer, without any of the bloat of traditional page builders.

== Changelog ==

Read our full changelog articles [here](https://extendify.com/changelog)

= 0.1.0 - 2022-01-06 =
* Add null check on import position
* Add support for importing patterns to a specific location
* Add `/extendify` slash command to open the library
* Add preview optimizations
* Add check for live preview visibility
* Fix pattern display bug with TT1 CSS Grid galleries
