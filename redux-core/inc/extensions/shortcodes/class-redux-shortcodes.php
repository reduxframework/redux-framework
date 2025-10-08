<?php
/**
 * Redux Shortcodes Class
 *
 * @package Redux
 * @author  Dovy Paukstys (dovy) & Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Shortcodes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Shortcodes' ) ) {

	/**
	 * Redux Framework shortcode extension class. Takes the common WordPress functions `wp_get_theme()` and `bloginfo()` and a few other functions and makes them accessible via shortcodes. Below you will find a table for the possible shortcodes and their values.
	 * | shortcode | Function | Description |
	 * |-----------|----------|-------------|
	 * | blog-name | bloginfo("name") | Displays the "Site Title" set in Settings > General. This data is retrieved from the "blogname" record in the wp_options table. |
	 * | blog-description | bloginfo("description") |  Displays the "Tagline" set in Settings > General. This data is retrieved from the "blogdescription" record in the wp_options table.|
	 * | blog-wpurl | bloginfo("wpurl") |  Displays the "WordPress address (URL)" set in Settings > General. This data is retrieved from the "siteurl" record in the wp_options table. Consider using **blog-root_url** instead, especially for multi-site configurations using paths instead of subdomains (it will return the root site not the current sub-site). |
	 * | blog-root_url | site_url() |  Return the root site, not the current sub-site. |
	 * | blog-url | home_url() |  Displays the "Site address (URL)" set in Settings > General. This data is retrieved from the "home" record in the wp_options table. |
	 * | blog-admin_email | bloginfo("admin_email") |  Displays the "E-mail address" set in Settings > General. This data is retrieved from the "admin_email" record in the wp_options table.|
	 * | blog-charset | bloginfo("charset") |  Displays the "Encoding for pages and feeds" set in Settings > Reading. This data is retrieved from the "blog_charset" record in the wp_options table. Note: In Version 3.5.0 and later, character encoding is no longer configurable from the Administration Panel. Therefore, this parameter always echoes "UTF-8", which is the default encoding of WordPress.|
	 * | blog-version | bloginfo("version") |  Displays the WordPress Version you use. This data is retrieved from the $wp_version variable set in wp-includes/version.php.|
	 * | blog-html_type | bloginfo("html_type") |  Displays the Content-Type of WordPress HTML pages (default: "text/html"). This data is retrieved from the "html_type" record in the wp_options table. Themes and plugins can override the default value using the pre_option_html_type filter.|
	 * | blog-text_direction | bloginfo("text_direction") |  Displays the Text Direction of WordPress HTML pages. Consider using **blog-text_direction_boolean** instead if you want a true/false response. |
	 * | blog-text_direction_boolean | is_rtl() |  Displays true/false check if the Text Direction of WordPress HTML pages is left instead of right |
	 * | blog-language | bloginfo("language") |  Displays the language of WordPress.|
	 * | blog-stylesheet_url | get_stylesheet_uri() |  Displays the primary CSS (usually style.css) file URL of the active theme. |
	 * | blog-stylesheet_directory | bloginfo("stylesheet_directory") |  Displays the stylesheet directory URL of the active theme. (Was a local path in earlier WordPress versions.) Consider echoing get_stylesheet_directory_uri() instead.|
	 * | blog-template_url | get_template_directory_uri() |  Parent template uri. Consider using **blog-child_template_url** for the child template URI. |
	 * | blog-child_template_url | get_stylesheet_directory_uri() | Child template URI. |
	 * | blog-pingback_url | bloginfo("pingback_url") |  Displays the Pingback XML-RPC file URL (xmlrpc.php).|
	 * | blog-atom_url | bloginfo("atom_url") |  Displays the Atom feed URL (/feed/atom).|
	 * | blog-rdf_url | bloginfo("rdf_url") |  Displays the RDF/RSS 1.0 feed URL (/feed/rfd).|
	 * | blog-rss_url | bloginfo("rss_url") |  Displays the RSS 0.92 feed URL (/feed/rss).|
	 * | blog-rss2_url | bloginfo("rss2_url") |  Displays the RSS 2.0 feed URL (/feed).|
	 * | blog-comments_atom_url | bloginfo("comments_atom_url") |  Displays the comments Atom feed URL (/comments/feed).|
	 * | blog-comments_rss2_url | bloginfo("comments_rss2_url") |  Displays the comments RSS 2.0 feed URL (/comments/feed).|
	 * | login-url | wp_login_url() | Returns the WordPress login URL. |
	 * | login-url | wp_logout_url() | Returns the WordPress logout URL. |
	 * | current_year | date("Y") | Returns the current year. |
	 * | theme-name | $theme_info->get("Name") | Theme name as given in theme's style.css |
	 * | theme-uri | $theme_info->get("ThemeURI") | The path to the theme's directory |
	 * | theme-description | $theme_info->get("Description") | The description of the theme |
	 * | theme-author | $theme_info->get("Author") | The theme's author |
	 * | theme-author_uri | $theme_info->get("AuthorURI") | The website of the theme author |
	 * | theme-version | $theme_info->get("Version") | The version of the theme |
	 * | theme-template | $theme_info->get("Template") | The folder name of the current theme |
	 * | theme-status | $theme_info->get("Status") | If the theme is published |
	 * | theme-tags | $theme_info->get("Tags") | Tags used to describe the theme |
	 * | theme-text_domain | $theme_info->get("TextDomain") | The text domain used in the theme for translation purposes |
	 * | theme-domain_path | $theme_info->get("DomainPath") | Path to the theme translation files |
	 *
	 * @version 1.0.0
	 */

	/**
	 * Class Redux_Shortcodes
	 */
	class Redux_Shortcodes {

		/**
		 * Redux_Shortcodes constructor.
		 */
		public function __construct() {
			if ( ! shortcode_exists( 'bloginfo' ) ) {
				add_shortcode( 'bloginfo', array( $this, 'blog_info' ) );
			} else {
				add_shortcode( 'redux_bloginfo', array( $this, 'blog_info' ) );
			}

			if ( ! shortcode_exists( 'themeinfo' ) ) {
				add_shortcode( 'themeinfo', array( $this, 'theme_info' ) );
			} else {
				add_shortcode( 'redux_themeinfo', array( $this, 'theme_info' ) );
			}

			if ( ! shortcode_exists( 'date' ) ) {
				add_shortcode( 'date', array( $this, 'date' ) );
			} else {
				add_shortcode( 'redux_date', array( $this, 'date' ) );
			}
		}

		/**
		 * Return site/blog info by a validated selector.
		 * Assumes the return value is printed into HTML (text node).
		 * If you need raw values for other contexts, remove the esc_* here
		 * and escape at the final render point instead.
		 *
		 * @param array|string $atts    Attributes.
		 * @param string|null  $content Content.
		 *
		 * @return string Escaped for HTML text (URLs are escaped with esc_url()).
		 */
		public function blog_info( $atts = array(), ?string $content = null ): string {

			// Normalize and merge defaults.
			$atts = is_array( $atts ) ? wp_unslash( $atts ) : array();
			$atts = shortcode_atts(
				array(
					'data' => '',
				),
				$atts
			);

			// Allow content as fallback for the selector.
			if ( null !== $content && '' === $atts['data'] ) {
				$atts['data'] = $content;
			}

			// Validate selector as a key and clamp length.
			$key = substr( sanitize_key( $atts['data'] ), 0, 64 );

			// Map aliases -> handlers.
			// Keep your original intent (filesystem paths vs URIs) but make it safe and explicit.
			switch ( $key ) {
				// Filesystem paths (escape as text).
				case 'stylesheet_directory':
				case 'child_template_directory':
					return esc_html( get_stylesheet_directory() );

				case 'template_directory':
					return esc_html( get_template_directory() );

				// Theme URIs.
				case 'parent_template_url':
					return esc_url( get_template_directory_uri() );

				case 'child_template_url':
				case 'template_url':
					return esc_url( get_stylesheet_directory_uri() );

				// URLs.
				case 'url':
					return esc_url( home_url() );

				case 'root_url':
					return esc_url( site_url() );

				case 'stylesheet_url':
					return esc_url( get_stylesheet_uri() );

				case 'logout_url':
					return esc_url( wp_logout_url() );

				case 'login_url':
					return esc_url( wp_login_url() );

				case 'register_url':
					return esc_url( wp_registration_url() );

				case 'lostpassword_url':
				case 'lost_password_url':
					return esc_url( wp_lostpassword_url() );

				// Booleans.
				case 'text_direction_bool':
				case 'text_direction_boolean':
					return esc_html( is_rtl() ? '1' : '0' );

				case 'is_multisite':
					return esc_html( is_multisite() ? '1' : '0' );

				// Text direction as string.
				case 'text_direction':
					return esc_html( is_rtl() ? 'rtl' : 'ltr' );

				default:
					// Safe fallback: pull raw blog info, then escape as text.
					// (Avoid over-sanitizing: this is trusted core data).
					$value = get_bloginfo( $key );
					return esc_html( (string) $value );
			}
		}

		/**
		 * Get theme info.
		 *
		 * @param array|string $atts    Attributes.
		 * @param string|null  $content Content.
		 *
		 * @return string
		 */
		public function theme_info( array $atts = array(), ?string $content = null ): string {
			// Normalize input and merge defaults.
			$atts = wp_unslash( $atts );
			$atts = shortcode_atts(
				array(
					'data' => '',
				),
				$atts
			);

			// Allow content as a fallback selector.
			if ( null !== $content && '' === $atts['data'] ) {
				$atts['data'] = $content;
			}

			// Validate selector as a key.
			$key = sanitize_key( $atts['data'] );

			// Map aliases -> canonical WP_Theme header keys or special handlers.
			$map = array(
				'name'        => 'Name',
				'themeuri'    => 'ThemeURI',
				'theme_uri'   => 'ThemeURI',
				'description' => 'Description',
				'author'      => 'Author',
				'authoruri'   => 'AuthorURI',
				'author_uri'  => 'AuthorURI',
				'version'     => 'Version',
				'template'    => 'Template',
				'status'      => 'Status',
				'tags'        => 'Tags',
				'textdomain'  => 'TextDomain',
				'text_domain' => 'TextDomain',
				'domainpath'  => 'DomainPath',
				'domain_path' => 'DomainPath',
				'is_child'    => 'is_child', // special case.
			);

			// Default to "name" when empty or unknown.
			if ( '' === $key || ! isset( $map[ $key ] ) ) {
				$key = 'name';
			}

			// Cache theme object.
			if ( empty( $this->theme_info ) ) {
				$this->theme_info = wp_get_theme();
			}

			$canonical = $map[ $key ];

			// Special case: is_child — return "1" or "0" as string.
			if ( 'is_child' === $canonical ) {
				$bool = Redux_Helpers::is_child_theme( get_template_directory() );
				return esc_html( $bool ? '1' : '0' );
			}

			$value = $this->theme_info->get( $canonical );

			// WP_Theme::get('Tags') may be array; normalize to string.
			if ( is_array( $value ) ) {
				$value = implode( ', ', array_filter( array_map( 'trim', $value ) ) );
			}

			$value = (string) $value;

			// Escape by context.
			switch ( $canonical ) {
				case 'ThemeURI':
				case 'AuthorURI':
					// If you ultimately print this inside an href, escape at that final context instead.
					// Here we assume the plain text output of the URL.
					return esc_url( $value );

				default:
					return esc_html( $value );
			}
		}

		/**
		 * Get date info.
		 *
		 * @param array|string $atts    Attributes.
		 * @param string|null  $content Content.
		 *
		 * @return string
		 */
		public function date( $atts = array(), ?string $content = null ): string {

			// 1) Normalize + unslash
			$atts = is_array( $atts ) ? wp_unslash( $atts ) : array();

			// 2) Default/merge (if this is a shortcode handler, pass the tag as the 3rd arg)
			$atts = shortcode_atts(
				array(
					'data' => '',
				),
				$atts
			);

			// 3) Allow content as a fallback for the format
			if ( null !== $content && '' === $atts['data'] ) {
				// Keep content simple text; strip tags and control chars.
				$atts['data'] = sanitize_text_field( wp_unslash( $content ) );
			}

			// 4) Validate and constrain the format string
			$format = (string) $atts['data'];
			$format = substr( $format, 0, 64 ); // avoid absurdly long inputs.

			// Allow common date format tokens + typical literal punctuation and backslash for escaping.
			// If invalid chars are present, fall back to a safe default.
			if ( '' === $format || ! preg_match( '/^[A-Za-z0-9\s:\-.,\/\\\\T]+$/', $format ) ) {
				$format = 'Y';
			}

			// 5) Use wp_date() to respect WP timezone and locale (date_i18n is deprecated).
			$output = wp_date( $format );

			// 6) Escape for HTML context before returning (safe default for shortcode/rendered output).
			return esc_html( $output );
		}
	}
}
