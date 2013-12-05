<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     Redux_Framework
 * @subpackage  Extensions
 * @author      Dovy Paukstys (dovy)
 * @version 1.0.0
 * @since 3.1.1
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_extension_shortcodes' ) ) {

    /**
     * Redux Framework shortcode extension class. Takes the common Wordpress functions `wp_get_theme()` and `bloginfo()` and a few other functions and makes them accessible via shortcodes. Below you will find a table for the possible shortcodes and their values.

| shortcode | Function | Description |
|-----------|----------|-------------|
| blog-name | bloginfo("name") | Displays the "Site Title" set in Settings > General. This data is retrieved from the "blogname" record in the wp_options table. |
| blog-description | bloginfo("description") |  Displays the "Tagline" set in Settings > General. This data is retrieved from the "blogdescription" record in the wp_options table.|
| blog-wpurl | bloginfo("wpurl") |  Displays the "WordPress address (URL)" set in Settings > General. This data is retrieved from the "siteurl" record in the wp_options table. Consider using **blog-root_url** instead, especially for multi-site configurations using paths instead of subdomains (it will return the root site not the current sub-site). |
| blog-root_url | site_url() |  Return the root site, not the current sub-site. |
| blog-url | home_url() |  Displays the "Site address (URL)" set in Settings > General. This data is retrieved from the "home" record in the wp_options table. |
| blog-admin_email | bloginfo("admin_email") |  Displays the "E-mail address" set in Settings > General. This data is retrieved from the "admin_email" record in the wp_options table.|
| blog-charset | bloginfo("charset") |  Displays the "Encoding for pages and feeds" set in Settings > Reading. This data is retrieved from the "blog_charset" record in the wp_options table. Note: In Version 3.5.0 and later, character encoding is no longer configurable from the Administration Panel. Therefore, this parameter always echoes "UTF-8", which is the default encoding of WordPress.|
| blog-version | bloginfo("version") |  Displays the WordPress Version you use. This data is retrieved from the $wp_version variable set in wp-includes/version.php.|
| blog-html_type | bloginfo("html_type") |  Displays the Content-Type of WordPress HTML pages (default: "text/html"). This data is retrieved from the "html_type" record in the wp_options table. Themes and plugins can override the default value using the pre_option_html_type filter.|
| blog-text_direction | bloginfo("text_direction") |  Displays the Text Direction of WordPress HTML pages. Consider using **blog-text_direction_boolean** instead if you want a true/false response. |
| blog-text_direction_boolean | is_rtl() |  Displays true/false check if the Text Direction of WordPress HTML pages is left instead of right |
| blog-language | bloginfo("language") |  Displays the language of WordPress.|
| blog-stylesheet_url | get_stylesheet_uri() |  Displays the primary CSS (usually style.css) file URL of the active theme. |
| blog-stylesheet_directory | bloginfo("stylesheet_directory") |  Displays the stylesheet directory URL of the active theme. (Was a local path in earlier WordPress versions.) Consider echoing get_stylesheet_directory_uri() instead.|
| blog-template_url | get_template_directory_uri() |  Parent template uri. Consider using **blog-child_template_url** for the child template URI. |
| blog-child_template_url | get_stylesheet_directory_uri() | Child template URI. |
| blog-pingback_url | bloginfo("pingback_url") |  Displays the Pingback XML-RPC file URL (xmlrpc.php).|
| blog-atom_url | bloginfo("atom_url") |  Displays the Atom feed URL (/feed/atom).|
| blog-rdf_url | bloginfo("rdf_url") |  Displays the RDF/RSS 1.0 feed URL (/feed/rfd).|
| blog-rss_url | bloginfo("rss_url") |  Displays the RSS 0.92 feed URL (/feed/rss).|
| blog-rss2_url | bloginfo("rss2_url") |  Displays the RSS 2.0 feed URL (/feed).|
| blog-comments_atom_url | bloginfo("comments_atom_url") |  Displays the comments Atom feed URL (/comments/feed).|
| blog-comments_rss2_url | bloginfo("comments_rss2_url") |  Displays the comments RSS 2.0 feed URL (/comments/feed).|
| login-url | wp_login_url() | Returns the Wordpress login URL. |
| login-url | wp_logout_url() | Returns the Wordpress logout URL. |
| current_year | date("Y") | Returns the current year. |
| theme-name | $theme_info->get("Name") | Theme name as given in theme's style.css |
| theme-uri | $theme_info->get("ThemeURI") | The path to the theme's directory |
| theme-description | $theme_info->get("Description") | The description of the theme |
| theme-author | $theme_info->get("Author") | The theme's author |
| theme-author_uri | $theme_info->get("AuthorURI") | The website of the theme author |
| theme-version | $theme_info->get("Version") | The version of the theme |
| theme-template | $theme_info->get("Template") | The folder name of the current theme |
| theme-status | $theme_info->get("Status") | If the theme is published |
| theme-tags | $theme_info->get("Tags") | Tags used to describe the theme |
| theme-text_domain | $theme_info->get("TextDomain") | The text domain used in the theme for translation purposes |
| theme-domain_path | $theme_info->get("DomainPath") | Path to the theme translation files |

 
     *
     * @version 1.0.0
     * @since 3.1.1
     */
    class ReduxFramework_extension_shortcodes {

      // Protected vars
      protected $parent;

      /**
       * Class Constructor. Defines the args for the extions class
       *
       * @since       1.0.0
       * @access      public
       * @param       array $parent Redux_Options class instance
       * @return      void
       */
      public function __construct( $parent ) {

      	$this->parent = $parent;
      	
      	if (!isset($parent->theme_info) || empty($parent->theme_info)) {
      		$this->parent->theme_info = wp_get_theme();
      	}
		
		// Shortcodes used within the framework
		if ( !shortcode_exists( 'blog-admin_email' ) ) {
			add_shortcode('blog-admin_email', array($this, 'blog_admin_email'));
		}
		if ( !shortcode_exists( 'blog-atom_url' ) ) {
			add_shortcode('blog-atom_url', array($this, 'blog_atom_url'));
		}
		if ( !shortcode_exists( 'blog-charset' ) ) {
			add_shortcode('blog-charset', array($this, 'blog_charset'));
		}
		if ( !shortcode_exists( 'blog-comments_atom_url' ) ) {
			add_shortcode('blog-comments_atom_url', array($this, 'blog_comments_atom_url'));
		}
		if ( !shortcode_exists( 'blog-comments_rss2_url' ) ) {
			add_shortcode('blog-comments_rss2_url', array($this, 'blog_comments_rss2_url'));
		}
		if ( !shortcode_exists( 'blog-description' ) ) {
			add_shortcode('blog-description', array($this, 'blog_description'));
		}
		if ( !shortcode_exists( 'blog-html_type' ) ) {
			add_shortcode('blog-html_type', array($this, 'blog_html_type'));
		}
		if ( !shortcode_exists( 'blog-language' ) ) {
			add_shortcode('blog-language', array($this, 'blog_language'));
		}
		if ( !shortcode_exists( 'blog-name' ) ) {
			add_shortcode('blog-name', array($this, 'blog_name'));
		}
		if ( !shortcode_exists( 'blog-pingback_url' ) ) {
			add_shortcode('blog-pingback_url', array($this, 'blog_pingback_url'));
		}
		if ( !shortcode_exists( 'blog-rdf_url' ) ) {
			add_shortcode('blog-rdf_url', array($this, 'blog_rdf_url'));
		}
		if ( !shortcode_exists( 'blog-rss2_url' ) ) {
			add_shortcode('blog-rss2_url', array($this, 'blog_rss2_url'));
		}
		if ( !shortcode_exists( 'blog-rss_url' ) ) {
			add_shortcode('blog-rss_url', array($this, 'blog_rss_url'));
		}
		if ( !shortcode_exists( 'blog-stylesheet_directory' ) ) {
			add_shortcode('blog-stylesheet_directory', array($this, 'blog_stylesheet_directory'));
		}
		if ( !shortcode_exists( 'blog-stylesheet_url' ) ) {
			add_shortcode('blog-stylesheet_url', array($this, 'blog_stylesheet_url'));
		}
		if ( !shortcode_exists( 'blog-template_directory' ) ) {
			add_shortcode('blog-template_directory', array($this, 'blog_template_directory'));
		}
		if ( !shortcode_exists( 'blog-template_url' ) ) {
			add_shortcode('blog-template_url', array($this, 'blog_template_url'));
		}
		if ( !shortcode_exists( 'blog-child_template_url' ) ) {
			add_shortcode('blog-child_template_url', array($this, 'blog_child_template_url'));
		}		
		if ( !shortcode_exists( 'blog-text_direction' ) ) {
			add_shortcode('blog-text_direction', array($this, 'blog_text_direction'));
		}
		if ( !shortcode_exists( 'blog-text_direction_boolean' ) ) {
			add_shortcode('blog-text_direction_boolean', array($this, 'blog_text_direction_boolean'));
		}		
		if ( !shortcode_exists( 'blog-url' ) ) {
			add_shortcode('blog-url', array($this, 'blog_url'));
		}
		if ( !shortcode_exists( 'blog-version' ) ) {
			add_shortcode('blog-version', array($this, 'blog_version'));
		}
		if ( !shortcode_exists( 'blog-wpurl' ) ) {
			add_shortcode('blog-wpurl', array($this, 'blog_wpurl'));
		}
		if ( !shortcode_exists( 'blog-root_url' ) ) {
			add_shortcode('blog-root_url', array($this, 'blog_root_url'));
		}		
		if ( !shortcode_exists( 'login-url' ) ) {
			add_shortcode('login-url', array($this, 'login_url'));
		}
		if ( !shortcode_exists( 'logout-url' ) ) {
			add_shortcode('logout-url', array($this, 'logout_url'));
		}
		if ( !shortcode_exists( 'current-year' ) ) {
			add_shortcode('current-year', array($this, 'current_year'));
		}		
		if ( !shortcode_exists( 'theme-name' ) ) {
			add_shortcode('theme-name', array($this, 'theme_name'));
		}	
		if ( !shortcode_exists( 'theme-uri' ) ) {
			add_shortcode('theme-uri', array($this, 'theme-theme_uri'));
		}
		if ( !shortcode_exists( 'theme-description' ) ) {
			add_shortcode('theme-description', array($this, 'theme_description'));
		}
		if ( !shortcode_exists( 'theme-author' ) ) {
			add_shortcode('theme-author', array($this, 'theme_author'));
		}
		if ( !shortcode_exists( 'theme-author_uri' ) ) {
			add_shortcode('theme-author_uri', array($this, 'theme_author_uri'));
		}
		if ( !shortcode_exists( 'theme-version' ) ) {
			add_shortcode('theme-version', array($this, 'theme_version'));
		}
		if ( !shortcode_exists( 'theme-template' ) ) {
			add_shortcode('theme-template', array($this, 'theme_template'));
		}
		if ( !shortcode_exists( 'theme-status' ) ) {
			add_shortcode('theme-status', array($this, 'theme_status'));
		}
		if ( !shortcode_exists( 'theme-tags' ) ) {
			add_shortcode('theme-tags', array($this, 'theme_tags'));
		}
		if ( !shortcode_exists( 'theme-text_domain' ) ) {
			add_shortcode('theme-text_domain', array($this, 'theme_text_domain'));
		}
		if ( !shortcode_exists( 'theme-domain_path' ) ) {
			add_shortcode('theme-domain_path', array($this, 'theme_domain_path'));
		}
		// Allow users to extend if they want
		do_action('redux/shorcodes/'.$parent->args['opt_name'].'/construct');

      }



		/**
			Shortcode - blog-admin_email
			@return bloginfo('admin_email')
		**/
		function blog_admin_email($atts,$content=NULL) {
			return bloginfo('admin_email');
		}

		/**
			Shortcode - blog-atom_url
			@return bloginfo('atom_url')
		**/
		function blog_atom_url($atts,$content=NULL) {
			return bloginfo('atom_url');
		}		
		/**
			Shortcode - blog-charset
			@return bloginfo('charset')
		**/
		function blog_charset($atts,$content=NULL) {
			return bloginfo('charset');
		}
		/**
			Shortcode - blog-comments_rss2_url
			@return bloginfo('comments_rss2_url')
		**/
		function blog_comments_rss2_url($atts,$content=NULL) {
			return bloginfo('comments_rss2_url');
		}
		/**
			Shortcode - blog-description
			@return bloginfo('description')
		**/
		function blog_description($atts,$content=NULL) {
			return bloginfo('description');
		}
		/**
			Shortcode - blog-html_type
			@return bloginfo('html_type')
		**/
		function blog_html_type($atts,$content=NULL) {
			return bloginfo('html_type');
		}						
		/**
			Shortcode - blog-language
			@return bloginfo('language')
		**/
		function blog_language($atts,$content=NULL) {
			return bloginfo('language');
		}
		/**
			Shortcode - blog-name
			@return bloginfo('name')
		**/
		function blog_name($atts,$content=NULL) {
			return bloginfo('name');
		}
		/**
			Shortcode - blog-pingback_url
			@return bloginfo('pingback_url')
		**/
		function blog_pingback_url($atts,$content=NULL) {
			return bloginfo('pingback_url');
		}
		/**
			Shortcode - blog-rdf_url
			@return bloginfo('rdf_url')
		**/
		function blog_rdf_url($atts,$content=NULL) {
			return bloginfo('rdf_url');
		}
		/**
			Shortcode - blog-rss2_url
			@return bloginfo('rss2_url')
		**/
		function blog_rss2_url($atts,$content=NULL) {
			return bloginfo('rss2_url');
		}	
		/**
			Shortcode - blog-stylesheet_directory
			@return bloginfo('stylesheet_directory')
		**/
		function blog_stylesheet_directory($atts,$content=NULL) {
			return bloginfo('stylesheet_directory');
		}
		/**
			Shortcode - blog-stylesheet_url
			@return get_stylesheet_uri()
		**/
		function blog_stylesheet_url($atts,$content=NULL) {
			return get_stylesheet_uri();
		}
		/**
			Shortcode - blog-template_directory
			@return bloginfo('template_directory')
		**/
		function blog_template_directory($atts,$content=NULL) {
			return bloginfo('template_directory');
		}
		/**
			Shortcode - blog-template_url
			@return get_template_directory_uri()
		**/
		function blog_template_url($atts,$content=NULL) {
			return get_template_directory_uri();
		}
		/**
			Shortcode - blog-child_template_url
			@return get_stylesheet_directory_uri()
		**/
		function blog_child_template_url($atts,$content=NULL) {
			return get_stylesheet_directory_uri();
		}		
		/**
			Shortcode - blog-text_direction
			@return bloginfo('text_direction')
		**/
		function blog_text_direction($atts,$content=NULL) {
			return bloginfo('text_direction');
		}
		/**
			Shortcode - blog-text_direction_boolean
			@return is_rtl()
		**/
		function blog_text_direction_boolean($atts,$content=NULL) {
			return is_rtl();
		}		
		/**
			Shortcode - blog-url
			@return bloginfo('url')
		**/
		function blog_url($atts,$content=NULL) {
			return home_url();
		}
		/**
			Shortcode - blog-version
			@return bloginfo('version')
		**/
		function blog_version($atts,$content=NULL) {
			return bloginfo('version');
		}
		/**
			Shortcode - blog-wpurl
			@return bloginfo('wpurl')
		**/
		function blog_wpurl($atts,$content=NULL) {
			return bloginfo('wpurl');
		}
		/**
			Shortcode - blog-root_url
			@return site_url()
		**/
		function blog_root_url($atts,$content=NULL) {
			return site_url();
		}		
		/**
			Shortcode - theme-name
			@return theme_info('Name')
		**/
		function blog_theme_name($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'Name' );
		}
		/**
			Shortcode - theme-uri
			@return theme_info('ThemeURI')
		**/
		function theme_uri($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'ThemeURI' );
		}	
		/**
			Shortcode - theme-description
			@return theme_info('Description')
		**/
		function theme_description($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'Description' );
		}	
		/**
			Shortcode - theme-author
			@return theme_info('Author')
		**/
		function theme_author($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'Author' );
		}	
		/**
			Shortcode - theme-author_uri
			@return theme_info('AuthorURI')
		**/
		function theme_author_uri($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'AuthorURI' );
		}	
		/**
			Shortcode - theme-version
			@return theme_info('Version')
		**/
		function theme_version($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'Version' );
		}	
		/**
			Shortcode - theme-template
			@return theme_info('Template')
		**/
		function theme_template($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'Template' );
		}	
		/**
			Shortcode - theme-status
			@return theme_info('Status')
		**/
		function theme_status($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'Status' );
		}	
		/**
			Shortcode - theme-tags
			@return theme_info('Tags')
		**/
		function theme_tags($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'Tags' );
		}	
		/**
			Shortcode - theme-text_domain
			@return theme_info('TextDomain')
		**/
		function theme_text_domain($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'TextDomain' );
		}	
		/**
			Shortcode - theme-domain_path
			@return theme_info('DomainPath')
		**/
		function theme_domain_path($atts,$content=NULL) {
			return $this->parent->theme_info->get( 'DomainPath' );
		}			
		/**
			Shortcode - login-url
			@return wp_login_url()
		**/
		function login_url($atts,$content=NULL) {
			return wp_login_url();
		}
		/**
			Shortcode - logout-url 
			@return wp_logout_url()
		**/
		function logout_url($atts,$content=NULL) {
			return wp_logout_url();
		}
		/**
			Shortcode - current-year
			@return date("Y")
		**/
		function current_year($atts,$content=NULL) {
			return date("Y");
		}

    } // class

} // if
