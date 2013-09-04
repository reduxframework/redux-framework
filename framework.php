<?php
if(!class_exists('Redux_Framework') ){

	// Windows-proof constants: replace backward by forward slashes - thanks to: https://github.com/peterbouwmeester
	$fslashed_dir = trailingslashit(str_replace('\\','/', dirname(__FILE__)));
	$fslashed_abs = trailingslashit(str_replace('\\','/', ABSPATH));

	if(!defined('REDUX_DIR')) {
		define('REDUX_DIR', $fslashed_dir);
	}

	if(!defined('REDUX_URL')) {
		define('REDUX_URL', site_url(str_replace($fslashed_abs, '', $fslashed_dir)));
	}

	class Redux_Framework {

		protected $framework_url = 'http://www.reduxframework.com/';
		protected $framework_version = '3.0.0';

		public $page = '';
		public $args = array();
		public $sections = array();
		public $extra_tabs = array();
		public $errors = array();
		public $warnings = array();
		public $options = array();
		public $options_defaults = null;

		/**
		 * Class Constructor. Defines the args for the theme options class
		 *
		 * @since Redux_Framework 1.0.0
		 * @param $array $args Arguments. Class constructor arguments.
		 */
		function __construct($sections = array(), $args = array(), $extra_tabs = array()) {
			$defaults = array();

			$defaults['opt_name'] = ''; // Must be defined by theme/plugin
			$defaults['google_api_key'] = ''; // Must be defined to add google fonts to the typography module
			$defaults['last_tab'] = '0';
			$defaults['menu_icon'] = REDUX_URL . 'assets/img/menu_icon.png';
			$defaults['menu_title'] = __('Options', 'redux-framework');
			$defaults['page_icon'] = 'icon-themes';
			$defaults['page_title'] = __('Options', 'redux-framework');
			$defaults['page_slug'] = '_options';
			$defaults['page_cap'] = 'manage_options';
			$defaults['page_type'] = 'menu';
			$defaults['page_parent'] = 'themes.php';
			$defaults['page_position'] = null;
			$defaults['allow_sub_menu'] = true;
			$defaults['show_import_export'] = true;
			$defaults['dev_mode'] = true;
			$defaults['admin_stylesheet'] = 'standard';
			$defaults['footer_credit'] = __('<span id="footer-thankyou">Options panel created using <a href="' . $this->framework_url . '" target="_blank">Redux Framework</a> v' . $this->framework_version . '</span>', 'redux-framework');
			$defaults['help_tabs'] = array();
			$defaults['help_sidebar'] = __('', 'redux-framework');

			// The defaults are set so it will preserve the old behavior.
			$defaults['std_show'] = false; // If true, it shows the std value
			$defaults['std_mark'] = ''; // What to print by the field's title if the value shown is std

			// Get args
			$this->args = wp_parse_args($args, $defaults);
			$this->args = apply_filters('redux-args-'.$this->args['opt_name'], $this->args);

			// Get sections
			$this->sections = apply_filters('redux-sections-' . $this->args['opt_name'], $sections);

			// Get extra tabs
			$this->extra_tabs = apply_filters('redux-extra-tabs-' . $this->args['opt_name'], $extra_tabs);

			// Set option with defaults
			add_action('init', array(&$this, '_set_default_options'));

			// Options page
			add_action('admin_menu', array(&$this, '_options_page'));

			// Register setting
			add_action('admin_init', array(&$this, '_register_setting'));

			// Hook into the WP feeds for downloading exported settings
			add_action('do_feed_reduxopts-' . $this->args['opt_name'], array(&$this, '_download_options'), 1, 1);

			// Get the options for use later on
			$this->options = get_option($this->args['opt_name']);
		}

		/**
		 * ->_get_std(); This is used to return the std value if std_show is set
		 *
		 * @since Redux_Framework 1.0.1
		 * @param string $opt_name: option name to return
		 * @param mixed $default (null): value to return if std not set
		 */
		function _get_std($opt_name, $default = null) {
			if($this->args['std_show'] == true) {
				if(isset($_GET['settings-updated']) || $_GET['settings-updated'] == 'true' || get_transient('redux-saved') == '1')
					return;
				if(is_null($this->options_defaults))
					$this->_default_values(); // fill cache
				$default = array_key_exists($opt_name, $this->options_defaults) ? $this->options_defaults[$opt_name] : $default;
			}
			return $default;
		}

		/**
		 * ->get(); This is used to return and option value from the options array
		 *
		 * @since Redux_Framework 1.0.0
		 * @param string $opt_name: option name to return
		 * @param mixed $default (null): value to return if option not set
		 */
		function get($opt_name, $default = null) {
			return ( !empty($this->options[$opt_name]) ) ? $this->options[$opt_name] : $this->_get_std($opt_name, $default);
		}

		/**
		 * ->set(); This is used to set an arbitrary option in the options array
		 *
		 * @since Redux_Framework 1.0.0
		 * @param string $opt_name the name of the option being added
		 * @param mixed $value the value of the option being added
		 */
		function set($opt_name = '', $value = '') {
			if($opt_name != '') {
				$this->options[$opt_name] = $value;
				update_option($this->args['opt_name'], $this->options);
			}
		}

		/**
		 * ->show(); This is used to echo and option value from the options array
		 *
		 * @since Redux_Framework 1.0.0
		 * @param $array $args Arguments. Class constructor arguments.
		 */
		function show($opt_name, $default = '') {
			$option = $this->get($opt_name);
			if(!is_array($option) && $option != '') {
				echo $option;
			} elseif($default != '') {
				echo $this->_get_std($opt_name, $default);
			}
		}

		/**
		 * Get default options into an array suitable for the settings API
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _default_values() {
			if(!is_null($this->sections) && is_null($this->options_defaults)) {
				// fill the cache
				foreach($this->sections as $section) {
					if(isset($section['fields'])) {
						foreach($section['fields'] as $field) {
							if(isset($field['std']))
								$this->options_defaults[$field['id']] = $field['std'];
						}
					}
				}
			}

			return $this->options_defaults;
		}


		/**
		 * Set default options on admin_init if option doesn't exist
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _set_default_options() {
			if(!get_option($this->args['opt_name'])) {
				add_option($this->args['opt_name'], $this->_default_values());
			}
			$this->options = get_option($this->args['opt_name']);
		}

		/**
		 * Class Options Page Function, creates main options page.
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _options_page() {
			if($this->args['page_type'] == 'submenu') {
				$this->page = add_submenu_page(
					$this->args['page_parent'],
					$this->args['page_title'],
					$this->args['menu_title'],
					$this->args['page_cap'],
					$this->args['page_slug'],
					array(&$this, '_options_page_html')
				);
			} else {
				$this->page = add_menu_page(
					$this->args['page_title'],
					$this->args['menu_title'],
					$this->args['page_cap'],
					$this->args['page_slug'],
					array(&$this, '_options_page_html'),
					$this->args['menu_icon'],
					$this->args['page_position']
				);

				if(true === $this->args['allow_sub_menu']) {
					if (!isset($section['type']) || $section['type'] != "divide") {

						// This is needed to prevent the top level menu item from showing in the submenu
						add_submenu_page($this->args['page_slug'], $this->args['page_title'], '', $this->args['page_cap'], $this->args['page_slug'], create_function('$a', "return null;"));

						foreach($this->sections as $k => $section) {
							if ( !isset( $section['title']) ) {
								continue;
							}
							add_submenu_page(
								$this->args['page_slug'],
								$section['title'],
								$section['title'],
								$this->args['page_cap'],
								$this->args['page_slug'].'&tab=' . $k, create_function('$a', "return null;")
							);
						}
					}

					if(true === $this->args['show_import_export']) {
						add_submenu_page(
							$this->args['page_slug'],
							__('Import / Export', 'redux-framework'),
							__('Import / Export', 'redux-framework'),
							$this->args['page_cap'],
							$this->args['page_slug'] . '&tab=import_export_default', create_function('$a', "return null;")
						);
					}

					foreach($this->extra_tabs as $k => $tab) {
						add_submenu_page(
							$this->args['page_slug'],
							$tab['title'],
							$tab['title'],
							$this->args['page_cap'],
							$this->args['page_slug'].'&tab=' . $k, create_function('$a', "return null;")
						);
					}

					if(true === $this->args['dev_mode']) {
						add_submenu_page(
							$this->args['page_slug'],
							__('Dev Mode Info', 'redux-framework'),
							__('Dev Mode Info', 'redux-framework'),
							$this->args['page_cap'],
							$this->args['page_slug'] . '&tab=dev_mode_default', create_function('$a', "return null;")
						);
					}
				}
			}

			add_action('admin_print_styles-' . $this->page, array(&$this, '_enqueue'));
			add_action('load-' . $this->page, array(&$this, '_load_page'));
		}

		/**
		 * enqueue styles/js for options page
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _enqueue() {
			global $wp_styles;

			wp_register_style(
				'redux-css',
				REDUX_URL . 'assets/css/style.css',
				array('farbtastic'),
				time(),
				'all'
			);

			wp_register_style(
				'redux-custom-css',
				REDUX_URL . 'assets/css/custom.css',
				array('farbtastic'),
				time(),
				'all'
			);
			/*
          wp_register_style(
              'redux-lte-ie7',
              REDUX_URL . 'css/vendor/lte-ie7.css',
              array('farbtastic'),
              time(),
              'all'
					);

					$wp_styles->add_data( 'redux-lte-ie7', 'conditional', 'lte IE 7' );
					*/
			wp_register_style(
				'redux-elusive-icon',
				REDUX_URL . 'assets/css/vendor/elusive-icons/elusive-webfont.css',
				array(),
				time(),
				'all'
			);

			wp_register_style(
				'redux-elusive-icon-ie7',
				REDUX_URL . 'assets/css/vendor/elusive-icons/elusive-webfont-ie7.css',
				array(),
				time(),
				'all'
			);

			wp_register_style(
				'select2-css',
				REDUX_URL . 'assets/js/vendor/select2/select2.css',
				array(),
				time(),
				'all'
			);			

			$wp_styles->add_data('redux-elusive-icon-ie7', 'conditional', 'lte IE 7' );

			wp_register_style(
				'redux-jquery-ui-css',
				apply_filters('redux-ui-theme', REDUX_URL . 'assets/css/vendor/jquery-ui-aristo/aristo.css'),
				'',
				time(),
				'all'
			);

			wp_enqueue_style('redux-lte-ie8');

			if($this->args['admin_stylesheet'] == 'standard') {
				wp_enqueue_style('redux-css');
			} elseif($this->args['admin_stylesheet'] == 'custom') {
				wp_enqueue_style('redux-custom-css');
			}

			wp_enqueue_style('redux-elusive-icon');
			wp_enqueue_style('redux-elusive-icon-ie7');

			wp_enqueue_script(
				'redux-js',
				REDUX_URL . 'assets/js/admin.min.js',
				array('jquery','jquery-cookie'),
				time(),
				true
			);

			wp_enqueue_script(
				'jquery-cookie',
				REDUX_URL . 'assets/js/vendor/cookie.js',
				array('jquery'),
				time(),
				true
			);	

			wp_register_script( 
				'select2-js', 
				REDUX_URL . 'assets/js/vendor/select2/select2.min.js',
				array('jquery'),
				time(),
				true				
			);

			wp_register_script(
				'jquery-tipsy',
				REDUX_URL . 'assets/js/vendor/jquery.tipsy.js',
				array('jquery'),
				time(),
				true
			);			

			wp_register_script(
				'jquery-numeric',
				REDUX_URL . 'assets/js/vendor/jquery.numeric.js ',
				array('jquery'),
				time(),
				true
			);								

			wp_localize_script('redux-js', 'redux_opts', array('save_pending' => __('You have changes that are not saved. Would you like to save them now?', 'redux-framework'), 'reset_confirm' => __('Are you sure? Resetting will loose all custom values.', 'redux-framework'), 'preset_confirm' => __('Your current options will be replaced with the values of this preset. Would you like to proceed?', 'redux-framework'), 'opt_name' => $this->args['opt_name']));

			do_action('redux-enqueue-' . $this->args['opt_name']);

			foreach($this->sections as $k => $section) {
				if(isset($section['fields'])) {
					foreach($section['fields'] as $fieldk => $field) {
						if(isset($field['type'])) {
							$field_class = 'Redux_Framework_' . $field['type'];
							if(!class_exists($field_class)) {
								$class_file = apply_filters('redux-typeclass-load', REDUX_DIR . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field_class);
								if($class_file)
									require_once($class_file);
							}

							if(class_exists($field_class) && method_exists($field_class, 'enqueue')) {
								$enqueue = new $field_class('','',$this);
								$enqueue->enqueue();
							}
						}
					}
				}
			}
		}

		/**
		 * Download the options file, or display it
		 *
		 * @since Redux_Framework 3.0.0
		 */
		function _download_options(){
			//-'.$this->args['opt_name']
			if(!isset($_GET['secret']) || $_GET['secret'] != md5(AUTH_KEY.SECURE_AUTH_KEY)) {
				wp_die('Invalid Secret for options use');
				exit;
			}
			if(!isset($_GET['feed'])){
				wp_die('No Feed Defined');
				exit;
			}
			$backup_options = get_option(str_replace('redux-','',$_GET['feed']));
			$backup_options['redux-backup'] = '1';
			$content = json_encode($backup_options);

			if(isset($_GET['action']) && $_GET['action'] == 'download_options'){
				header('Content-Description: File Transfer');
				header('Content-type: application/txt');
				header('Content-Disposition: attachment; filename="'.str_replace('redux-','',$_GET['feed']).'_backup_'.date('d-m-Y').'.json"');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				echo $content;
				exit;
			}else{
				header( 'Content-type: text/json' );
				header( 'Content-type: application/json' );
				header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
				header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
				header( 'Cache-Control: no-store, no-cache, must-revalidate' );
				header( 'Cache-Control: post-check=0, pre-check=0', false );
				header( 'Pragma: no-cache' );
				echo $content;
				exit;
			}
		}

		/**
		 * Show page help
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _load_page() {

			// Do admin head action for this page
			add_action('admin_head', array(&$this, 'admin_head'));

			// Do admin footer text hook
			add_filter('admin_footer_text', array(&$this, 'admin_footer_text'));

			$screen = get_current_screen();

			if(is_array($this->args['help_tabs'])) {
				foreach($this->args['help_tabs'] as $tab) {
					$screen->add_help_tab($tab);
				}
			}

			if($this->args['help_sidebar'] != '') {
				$screen->set_help_sidebar($this->args['help_sidebar']);
			}

			do_action('redux-load-page-' . $this->args['opt_name'], $screen);
		}

		/**
		 * Do action redux-admin-head for options page
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function admin_head() {
			do_action('redux-admin-head-' . $this->args['opt_name'], $this);
		}

		function admin_footer_text($footer_text) {
			return $this->args['footer_credit'];
		}

		/**
		 * Register Option for use
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _register_setting() {
			register_setting($this->args['opt_name'] . '_group', $this->args['opt_name'], array(&$this,'_validate_options'));

			if(is_null($this->sections))
				return;

			$runUpdate = false;

			foreach($this->sections as $k => $section) {
				if (isset($section['type']) && $section['type'] == "divide") {
					continue;
				}
				// DOVY! Replace $k with $section['id'] when ready
				$section = apply_filters('redux-section-'.$k.'-modifier-'.$this->args['opt_name'], $section);

				add_settings_section($this->args['opt_name'] . $k . '_section', $section['title'], array(&$this, '_section_desc'), $this->args['opt_name'] . $k . '_section_group');

				if(isset($section['fields'])) {
					foreach($section['fields'] as $fieldk => $field) {
						if(isset($field['title'])) {
							$std_mark = ( !isset($this->options[$field['id']]) && $this->args['std_show'] == true && isset($field['std']) ) ? $this->args['std_mark'] : '';
							$th = $field['title'] . $std_mark;
							if (isset($field['subtitle']))
								$th .= '<span class="description">' . $field['subtitle'] . '</span>';
						} else {
							$th = '';
						}
						$field = apply_filters('redux-field-'.$field['id'].'modifier-'.$this->args['opt_name'], $field);
						add_settings_field($fieldk . '_field', $th, array(&$this,'_field_input'), $this->args['opt_name'] . $k . '_section_group', $this->args['opt_name'] . $k . '_section', $field); // checkbox
					}
				}
			}
			do_action('redux-register-settings-' . $this->args['opt_name']);
		}

		/**
		 * Validate the Options options before insertion
		 *
		 * @since Redux_Framework 3.0.0
		 */
		function _validate_options($plugin_options) {
			set_transient('redux-saved', '1', 1000 );

			if(!empty($plugin_options['import'])) {
				if($plugin_options['import_code'] != '') {
					$import = $plugin_options['import_code'];
				} elseif($plugin_options['import_link'] != '') {
					$import = wp_remote_retrieve_body(wp_remote_get($plugin_options['import_link']));
				}

				$imported_options = json_decode(htmlspecialchars_decode($import), true);

				if(is_array($imported_options) && isset($imported_options['redux-backup']) && $imported_options['redux-backup'] == '1'){
					$imported_options['imported'] = 1;
					$plugin_options = wp_parse_args( $imported_options, $plugin_options ); // Allows for partial import (presets)
					if ($_COOKIE["redux_current_tab"] == "import_export_default") {
						setcookie('redux_current_tab', '', 1, '/');
					}
					set_transient('redux-compiler', '1', 1000 );
					unset($plugin_options['defaults'], $plugin_options['compiler'], $plugin_options['import'], $plugin_options['import_code']);
					return $plugin_options;
				}
			}

			if(!empty($plugin_options['defaults'])) {
				$plugin_options = $this->_default_values();
				set_transient('redux-compiler', '1', 1000 );
				unset($plugin_options['defaults'], $plugin_options['compiler'], $plugin_options['import'], $plugin_options['import_code']);
				return $plugin_options;
			}

			// Validate fields (if needed)
			$plugin_options = $this->_validate_values($plugin_options, $this->options);

			if($this->errors) {
				set_transient('redux-errors-' . $this->args['opt_name'], $this->errors, 1000);
			}

			if($this->warnings) {
				set_transient('redux-warnings-' . $this->args['opt_name'], $this->warnings, 1000);
			}

			do_action('redux-validate-' . $this->args['opt_name'], $plugin_options, $this->options);

			if (!empty($plugin_options['compiler'])) {
				set_transient('redux-compiler', '1', 1000 );
			}

			unset($plugin_options['defaults']);
			unset($plugin_options['import']);
			unset($plugin_options['import_code']);
			unset($plugin_options['import_link']);
			unset($plugin_options['compiler']);

			return $plugin_options;
		}

		/**
		 * Validate values from options form (used in settings api validate function)
		 * calls the custom validation class for the field so authors can override with custom classes
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _validate_values($plugin_options, $options) {
			foreach($this->sections as $k => $section) {
				if(isset($section['fields'])) {
					foreach($section['fields'] as $fieldk => $field) {
						$field['section_id'] = $k;

						if(isset($field['type']) && ( $field['type']=='checkbox' || $field['type']=='checkbox_hide_below' || $field['type']=='checkbox_hide_all' )){
							if(!isset($plugin_options[$field['id']]))
								$plugin_options[$field['id']] = 0;
						}

						if(isset($field['type']) && $field['type'] == 'multi_text'){ continue; } // We can't validate this yet

						if(!isset($plugin_options[$field['id']]) || $plugin_options[$field['id']] == '') {
							continue;
						}

						// Force validate of custom field types
						if(isset($field['type']) && !isset($field['validate'])) {
							if($field['type'] == 'color' || $field['type'] == 'color_gradient') {
								$field['validate'] = 'color';
							} elseif($field['type'] == 'date') {
								$field['validate'] = 'date';
							}
						}

						if(isset($field['validate'])) {
							$validate = 'Redux_Validation_' . $field['validate'];

							if(!class_exists($validate)) {
								$class_file = apply_filters('redux-validateclass-load', REDUX_DIR . 'inc/validation/' . $field['validate'] . '/validation_' . $field['validate'] . '.php', $validate);
								if($class_file)
									require_once($class_file);
							}

							if(class_exists($validate)) {
								$validation = new $validate($field, $plugin_options[$field['id']], $options[$field['id']]);
								$plugin_options[$field['id']] = $validation->value;
								if(isset($validation->error)) {
									$this->errors[] = $validation->error;
								}
								if(isset($validation->warning)) {
									$this->warnings[] = $validation->warning;
								}
								continue;
							}
						}

						if(isset($field['validate_callback']) && function_exists($field['validate_callback'])) {
							$callbackvalues = call_user_func($field['validate_callback'], $field, $plugin_options[$field['id']], $options[$field['id']]);
							$plugin_options[$field['id']] = $callbackvalues['value'];
							if(isset($callbackvalues['error'])) {
								$this->errors[] = $callbackvalues['error'];
							}
							if(isset($callbackvalues['warning'])) {
								$this->warnings[] = $callbackvalues['warning'];
							}
						}
					}
				}
			}
			return $plugin_options;
		}

		/**
		 * HTML OUTPUT.
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _options_page_html() {

			// Add the js for error handling before the form
			add_action('redux-page-before-form-' . $this->args['opt_name'], array(&$this, '_errors_js'), 1);

			// Add the js for warning handling before the form
			add_action('redux-page-before-form-' . $this->args['opt_name'], array(&$this, '_warnings_js'), 2);

			$saved = get_transient('redux-saved');
			delete_transient('redux-saved');

			echo '<div class="clear"></div><div class="wrap">';
			echo '<noscript><div class="no-js">Warning- This options panel will not work properly without javascript!</div></noscript>';
			echo '<input type="hidden" id="ajaxsecurity" name="security" value="'.wp_create_nonce('of_ajax_nonce').'" />';
			if ( $title = get_admin_page_title() && get_admin_page_title() != "" ) {
				echo '<div id="'.$this->args['page_icon'].'" class="icon32"><br/></div>';
				echo '<h2 id="redux-heading">'.get_admin_page_title().'</h2>';
			}
			echo (isset($this->args['intro_text']))?$this->args['intro_text']:'';

			do_action('redux-page-before-form-' . $this->args['opt_name']);
			echo '<div id="redux-container">';
			echo '<form method="post" action="options.php" enctype="multipart/form-data" id="redux-form-wrapper">';
			echo '<input type="hidden" id="redux-compiler-hook" name="'.$this->args['opt_name'].'[compiler]" value="" />';
			settings_fields($this->args['opt_name'] . '_group');
			if (empty($this->options['last_tab'])) {
				$this->options['last_tab'] = "";
			}
			$this->options['last_tab'] = (isset($_GET['tab']) && !$saved)?$_GET['tab']:$this->options['last_tab'];

			echo '<input type="hidden" id="last_tab" name="'.$this->args['opt_name'].'[last_tab]" value="'.$this->options['last_tab'].'" />';

			echo '<div id="redux-header">';
				if (!empty($this->args['display_name'])) {
					echo '<div class="logo">';
						echo '<h2>'.$this->args['display_name'].'</h2>';
						if (!empty($this->args['display_version'])) {
							echo '<span>'.$this->args['display_version'].'</span>';
						}
					echo '</div>';
				}
				// DOVY!
				echo '<div class="icon-option '.$this->args['page_icon'].'"></div>';
				echo '<div class="clear"></div><!--clearfix-->';
			echo '</div>';
			echo '<div id="redux-sticky">';
			echo '<div id="info_bar">';
			echo '<a href="javascript:void(0);" id="expand_options">Expand</a>';
			echo '<div class="redux-action_bar">';
			submit_button('', 'primary', 'redux_save', false);
			echo "&nbsp;";
			submit_button(__('Reset to Defaults', 'redux-framework'), 'secondary', $this->args['opt_name'].'[defaults]', false);
			echo "</div>";
			echo '<div class="redux-ajax-loading" alt="Working...">&nbsp;</div>';
			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div>';
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && $saved == '1'){
				if(isset($this->options['imported']) && $this->options['imported'] == 1){
					echo '<div id="redux-imported">'.apply_filters('redux-imported-text-'.$this->args['opt_name'], ''.__('<strong>Settings Imported!</strong>', 'redux-framework')).'</div>';
				}else{
					echo '<div id="redux-save">'.apply_filters('redux-saved-text-'.$this->args['opt_name'], __('<strong>Settings Saved!</strong>', 'redux-framework')).'</div>';
				}
			}
			echo '<div id="redux-save-warn">'.apply_filters('redux-changed-text-'.$this->args['opt_name'], __('<strong>Settings have changed, you should save them!</strong>', 'redux-framework')).'</div>';
			echo '<div id="redux-field-errors">'.__('<strong><span></span> error(s) were found!</strong>', 'redux-framework').'</div>';

			echo '<div id="redux-field-warnings">'.__('<strong><span></span> warning(s) were found!</strong>', 'redux-framework').'</div>';
			echo '</div>';
			echo '<div class="clear"></div><!--clearfix-->';

			echo '<div id="redux-sidebar">';
			echo '<ul id="redux-group-menu">';
			foreach($this->sections as $k => $section) {
				if(!empty($this->args['icon_type']) && $this->args['icon_type'] == 'image') {
					$icon = (!isset($section['icon'])) ? '' : '<img src="' . $section['icon'] . '" /> ';
				} else {
					$icon_class = (!isset($section['icon_class'])) ? '' : ' ' . $section['icon_class'];
					$icon = (!isset($section['icon'])) ? '<i class="icon-cog' . $icon_class . '"></i> ' : '<i class="icon-' . $section['icon'] . $icon_class . '"></i> ';
				}
				// DOVY! REPLACE $k with $section['ID'] when used properly.
				echo '<li id="' . $k . '_section_group_li" class="redux-group-tab-link-li">';
				echo '<a href="javascript:void(0);" id="' . $k . '_section_group_li_a" class="redux-group-tab-link-a" data-rel="' . $k . '">' . $icon . '<span>' . $section['title'] . '</span></a>';
				echo '</li>';
			}

			echo '<li class="divide">&nbsp;</li>';

			do_action('redux-page-after-sections-menu-' . $this->args['opt_name'], $this);

			if(true === $this->args['show_import_export']) {
				echo '<li id="import_export_default_section_group_li" class="redux-group-tab-link-li">';
				if(!empty($this->args['icon_type']) && $this->args['icon_type'] == 'image') {
					$icon = (!isset($this->args['import_icon'])) ? '' : '<img src="' . $this->args['import_icon'] . '" /> ';
				} else {
					$icon_class = (!isset($this->args['import_icon_class'])) ? '' : ' ' . $this->args['import_icon_class'];
					$icon = (!isset($this->args['import_icon'])) ? '<i class="icon-refresh' . $icon_class . '"></i>' : '<i class="icon-' . $this->args['import_icon'] . $icon_class . '"></i> ';
				}
				echo '<a href="javascript:void(0);" id="import_export_default_section_group_li_a" class="redux-group-tab-link-a" data-rel="import_export_default">' . $icon . ' <span>' . __('Import / Export', 'redux-framework') . '</span></a>';
				echo '</li>';
				echo '<li class="divide">&nbsp;</li>';
			}

			if(is_array($this->extra_tabs)) {
				foreach($this->extra_tabs as $k => $tab) {
					if(!empty($this->args['icon_type']) && $this->args['icon_type'] == 'image') {
						$icon = (!isset($tab['icon'])) ? '' : '<img src="' . $tab['icon'] . '" /> ';
					} else {
						$icon_class = (!isset($tab['icon_class'])) ? '' : ' ' . $tab['icon_class'];
						$icon = (!isset($tab['icon'])) ? '<i class="icon-cog' . $icon_class . '"></i> ' : '<i class="icon-' . $tab['icon'] . $icon_class . '"></i> ';
					}
					echo '<li id="' . $k . '_section_group_li" class="redux-group-tab-link-li">';
					echo '<a href="javascript:void(0);" id="' . $k . '_section_group_li_a" class="redux-group-tab-link-a custom-tab" data-rel="' . $k . '">' . $icon . '<span>' . $tab['title'] . '</span></a>';
					echo '</li>';
				}
			}

			if(true === $this->args['dev_mode']) {
				echo '<li id="dev_mode_default_section_group_li" class="redux-group-tab-link-li">';
				if(!empty($this->args['icon_type']) && $this->args['icon_type'] == 'image') {
					$icon = (!isset($this->args['dev_mode_icon'])) ? '' : '<img src="' . $this->args['dev_mode_icon'] . '" /> ';
				} else {
					$icon_class = (!isset($this->args['dev_mode_icon_class'])) ? '' : ' ' . $this->args['dev_mode_icon_class'];
					$icon = (!isset($this->args['dev_mode_icon'])) ? '<i class="icon-info-sign' . $icon_class . '"></i>' : '<i class="icon-' . $this->args['dev_mode_icon'] . $icon_class . '"></i> ';
				}
				echo '<a href="javascript:void(0);" id="dev_mode_default_section_group_li_a" class="redux-group-tab-link-a custom-tab" data-rel="dev_mode_default">' . $icon . ' <span>' . __('Dev Mode Info', 'redux-framework') . '</span></a>';
				echo '</li>';
			}

			echo '</ul>';
			echo '</div>';

			echo '<div id="redux-main">';

			foreach($this->sections as $k => $section) {
				echo '<div id="' . $k . '_section_group' . '" class="redux-group-tab">';
				do_settings_sections($this->args['opt_name'] . $k . '_section_group');
				echo '</div>';
			}

			if(true === $this->args['show_import_export']) {
				echo '<div id="import_export_default_section_group' . '" class="redux-group-tab">';

				echo '<h3>' . __('Import / Export Options', 'redux-framework') . '</h3>';
				echo '<h4>' . __('Import Options', 'redux-framework') . '</h4>';
				echo '<p><a href="javascript:void(0);" id="redux-import-code-button" class="button-secondary">' . __('Import from file') . '</a> <a href="javascript:void(0);" id="redux-import-link-button" class="button-secondary">' . __('Import from URL', 'redux-framework') . '</a></p>';

				echo '<div id="redux-import-code-wrapper">';

				echo '<div class="redux-section-desc">';
				echo '<p class="description" id="import-code-description">' . apply_filters('redux-import-file-description', __('Input your backup file below and hit Import to restore your sites options from a backup.', 'redux-framework')) . '</p>';
				echo '</div>';

				echo '<textarea id="import-code-value" name="' . $this->args['opt_name'] . '[import_code]" class="large-text noUpdate" rows="8"></textarea>';

				echo '</div>';

				echo '<div id="redux-import-link-wrapper">';

				echo '<div class="redux-section-desc">';
				echo '<p class="description" id="import-link-description">' . apply_filters('redux-import-link-description', __('Input the URL to another sites options set and hit Import to load the options from that site.', 'redux-framework')) . '</p>';
				echo '</div>';

				echo '<input type="text" id="import-link-value" name="' . $this->args['opt_name'] . '[import_link]" class="large-text noUpdate" value="" />';

				echo '</div>';

				echo '<p id="redux-import-action"><input type="submit" id="redux-import" name="' . $this->args['opt_name'] . '[import]" class="button-primary" value="' . __('Import', 'redux-framework') . '"> <span>' . apply_filters('redux-import-warning', __('WARNING! This will overwrite any existing options, please proceed with caution!', 'redux-framework')) . '</span></p>';
				echo '<div class="hr"/><div class="inner"><span>&nbsp;</span></div></div>';

				echo '<h4>' . __('Export Options', 'redux-framework') . '</h4>';
				echo '<div class="redux-section-desc">';
				echo '<p class="description">' . apply_filters('redux-backup-description', __('Here you can copy/download your current option settings. Keep this safe as you can use it as a backup should anything go wrong, or you can use it to restore your settings on this site (or any other site).', 'redux-framework')).'</p>';
				echo '</div>';

				echo '<p><a href="javascript:void(0);" id="redux-export-code-copy" class="button-secondary">' . __('Copy', 'redux-framework') . '</a> <a href="'.add_query_arg(array('feed' => 'reduxopts-'.$this->args['opt_name'], 'action' => 'download_options', 'secret' => md5(AUTH_KEY.SECURE_AUTH_KEY)), site_url()).'" id="redux-export-code-dl" class="button-primary">Download</a> <a href="javascript:void(0);" id="redux-export-link" class="button-secondary">' . __('Copy Link', 'redux-framework') . '</a></p>';
				$backup_options = $this->options;
				$backup_options['redux-backup'] = '1';
				echo '<textarea class="large-text noUpdate" id="redux-export-code" rows="8">';
				print_r(json_encode($backup_options));
				echo '</textarea>';
				echo '<input type="text" class="large-text noUpdate" id="redux-export-link-value" value="' . add_query_arg(array('feed' => 'reduxopts-' . $this->args['opt_name'], 'secret' => md5(AUTH_KEY.SECURE_AUTH_KEY)), site_url()) . '" />';

				echo '</div>';
			}

			if(is_array($this->extra_tabs)) {
				foreach($this->extra_tabs as $k => $tab) {
					echo '<div id="' . $k . '_section_group' . '" class="redux-group-tab">';
					echo '<h3>' . $tab['title'] . '</h3>';
					echo $tab['content'];
					echo '</div>';
				}
			}

			if(true === $this->args['dev_mode']) {
				echo '<div id="dev_mode_default_section_group' . '" class="redux-group-tab">';
				echo '<h3>' . __('Dev Mode Info', 'redux-framework') . '</h3>';
				echo '<div class="redux-section-desc">';
				echo '<textarea class="large-text" rows="24">' . print_r($this, true) . '</textarea>';
				echo '</div>';
				// Javascript object debug
				echo '<script>'
					.'function redux_object() {'
					.'console.log( jQuery.parseJSON( decodeURIComponent( jQuery("#redux-object").val() ) ) );'
					.'return;'
					.'}'
					.'</script>';
				echo '<input type="hidden" id="redux-object" value="'.urlencode(json_encode($this)).'" /><a href="javascript:redux_object()" class="button">Show Object in Javascript Console Object</a>';
				// END Javascript object debug
				echo '</div>';
			}

			do_action('redux-page-after-sections-' . $this->args['opt_name'], $this);

			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div>';
			echo '<div class="clear"></div><!--clearfix-->';

			echo '<div id="redux-sticky-padder" style="display: none;">&nbsp;</div>';
			echo '<div id="redux-footer-sticky"><div id="redux-footer">';

			if(isset($this->args['share_icons'])){
				echo '<div id="redux-share">';
				foreach($this->args['share_icons'] as $link){
					echo '<a href="'.$link['link'].'" title="'.$link['title'].'" target="_blank"><img src="'.$link['img'].'"/></a>';
				}
				echo '</div>';
			}

			echo '<div class="redux-action_bar">';
			submit_button('', 'primary', 'redux_save', false);
			echo "&nbsp;";
			submit_button(__('Reset to Defaults', 'redux-framework'), 'secondary', $this->args['opt_name'].'[defaults]', false);
			echo "</div>";
			echo '<div class="redux-ajax-loading" alt="Working...">&nbsp;</div>';
			echo '<div class="clear"></div><!--clearfix-->';

			echo '</div>';
			echo '</form>';
			echo '</div></div>';
			echo (isset($this->args['footer_text'])) ? '<div id="redux-sub-footer">' . $this->args['footer_text'] . '</div>' : '';

			do_action('redux-page-after-form-' . $this->args['opt_name']);

			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div><!--wrap-->';
			if (true === $this->args['dev_mode']) {
				echo '<br /><div class="redux-timer">'.get_num_queries().' queries in '.timer_stop(0).' seconds</div>';
			}

		}

		/**
		 * JS to display the errors on the page
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _errors_js() {
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('redux-errors-' . $this->args['opt_name'])) {
				$errors = get_transient('redux-errors-' . $this->args['opt_name']);
				$section_errors = array();
				foreach($errors as $error) {
					$section_errors[$error['section_id']] = (isset($section_errors[$error['section_id']])) ? $section_errors[$error['section_id']] : 0;
					$section_errors[$error['section_id']]++;
				}
				// DOVY! MOVE THIS TO THE ADMIN JS, shouldn't be here
				echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function(){';
				echo 'jQuery("#redux-field-errors span").html("' . count($errors) . '");';
				echo 'jQuery("#redux-field-errors").show();';

				foreach($section_errors as $sectionkey => $section_error) {
					echo 'jQuery("#' . $sectionkey . '_section_group_li_a").append("<span class=\"redux-menu-error\">' . $section_error . '</span>");';
				}

				foreach($errors as $error) {
					echo 'jQuery("#' . $error['id'] . '").addClass("redux-field-error");';
					echo 'jQuery("#' . $error['id'] . '").closest("td").append("<span class=\"redux-th-error\">' . $error['msg'] . '</span>");';
				}
				echo '});';
				echo '</script>';
				delete_transient('redux-errors-' . $this->args['opt_name']);
			}
		}

		/**
		 * JS to display the warnings on the page
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _warnings_js() {
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('redux-warnings-' . $this->args['opt_name'])) {
				$warnings = get_transient('redux-warnings-' . $this->args['opt_name']);
				$section_warnings = array();
				foreach($warnings as $warning) {
					$section_warnings[$warning['section_id']] = (isset($section_warnings[$warning['section_id']])) ? $section_warnings[$warning['section_id']] : 0;
					$section_warnings[$warning['section_id']]++;
				}
				// DOVY! MOVE THIS TO THE ADMIN JS, shouldn't be here
				echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function(){';
				echo 'jQuery("#redux-field-warnings span").html("' . count($warnings) . '");';
				echo 'jQuery("#redux-field-warnings").show();';

				foreach($section_warnings as $sectionkey => $section_warning) {
					echo 'jQuery("#' . $sectionkey . '_section_group_li_a").append("<span class=\"redux-menu-warning\">' . $section_warning . '</span>");';
				}

				foreach($warnings as $warning) {
					echo 'jQuery("#' . $warning['id'] . '").addClass("redux-field-warning");';
					echo 'jQuery("#' . $warning['id'] . '").closest("td").append("<span class=\"redux-th-warning\">' . $warning['msg'] . '</span>");';
				}
				echo '});';
				echo '</script>';
				delete_transient('redux-warnings-' . $this->args['opt_name']);
			}
		}

		/**
		 * Section HTML OUTPUT.
		 *
		 * @since Redux_Framework 1.0.0
		 */
		function _section_desc($section) {
			$id = trim(rtrim($section['id'], '_section'), $this->args['opt_name']);

			if(isset($this->sections[$id]['desc']) && !empty($this->sections[$id]['desc'])) {
				echo '<div class="redux-section-desc">' . $this->sections[$id]['desc'] . '</div>';
			}
		}

		/**
		 * Field HTML OUTPUT.
		 *
		 * Gets option from options array, then calls the specific field type class - allows extending by other devs
		 * @since Redux_Framework 1.0.0
		 */
		function _field_input($field) {

			// Setup the fold hidden object to be used by the JS
			if (!empty($field['fold'])) {

				if ( !is_array( $field['fold'] ) ) {
					$field['fold'] = array($field['fold']);
				}

				$data = $val = "";
				foreach( $field['fold'] as $foldk => $foldv ) {

					if ($foldv === true || $foldv === "1" || (is_int($foldv) && $foldv === 1)) {
						$foldv = array(1);
					} else if ($foldv === false || $foldv === null) {
							$foldv = array(0);
						} else if (!is_array($foldv)) {
							$foldk = $foldv;
							$foldv = array("0");
						}

					$data .= ' data-'.$foldk.'="'.implode(",", $foldv).'"';
					$val .= $foldk.",";
				}

				$val = rtrim($val, ',');
				if ($field['type'] != "info") {
					echo '<input type="hidden" '.$data.' id="foldChild-'.$field['id'].'" class="fold-data" value="'.$val.'" />';
				} else {
					$field['fold-ids'] = $data;
					$field['fold-vals'] = $val;
				}

			}
			// END foldobject if

			if(isset($field['callback']) && function_exists($field['callback'])) {
				$value = (isset($this->options[$field['id']])) ? $this->options[$field['id']] : '';
				do_action('redux-before-field-' . $this->args['opt_name'], $field, $value);
				call_user_func($field['callback'], $field, $value);
				do_action('redux-after-field-' . $this->args['opt_name'], $field, $value);
				return;
			}

			if(isset($field['type'])) {
				$field_class = 'Redux_Framework_'.$field['type'];

				if(!class_exists($field_class)) {
					$class_file = apply_filters('redux-typeclass-load', REDUX_DIR . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field_class);
					if($class_file) {
						require_once($class_file);
					}
				}

				if(class_exists($field_class)) {
					$value = $this->get($field['id'], '');
					do_action('redux-before-field-' . $this->args['opt_name'], $field, $value);
					$render = '';
					$render = new $field_class($field, $value, $this);
					$render->render();
					do_action('redux-after-field-' . $this->args['opt_name'], $field, $value);
				}
			}
		} // cunction
	}// class
} // if
