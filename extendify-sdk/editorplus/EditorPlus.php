<?php
/**
 * Handles editor related changes.
 * Loaded (or not) in /bootstrap.php
 */

if (!class_exists('edpl__EditorPlus')) {
    // phpcs:ignore Squiz.Classes.ClassFileName.NoMatch,Squiz.Commenting.ClassComment.Missing,PEAR.Commenting.ClassComment.Missing
    final class ExtendifySdkEditorPlus
    {

        /**
         * A reference to an instance of this class.
         *
         * @var $instance
         */
        public static $instance;

        /**
         * The array of templates that this plugin tracks.
         *
         * @var array $templates
         */
        protected $templates = ['editorplus-template.php' => 'Extendify Template'];

        /**
         * Returns an instance of this class.
         *
         * @return self
         */
        public static function getInstance()
        {
            if (!current_user_can('install_plugins')) {
                return;
            }

            if (is_null(self::$instance)) {
                self::$instance = new ExtendifySdkEditorPlus();
            }

            return self::$instance;
        }

        /**
         * Check whether we need to use the Extendify/EP template.
         */
        public function __construct()
        {
            // Maybe show the styles on the frontend.
            add_action('wp_head', function () {
                if ($this->useDeprecatedTemplate()) {
                    $this->showStylesheet();
                }
            });

            // Maybe show the styles in admin.
            add_action('admin_head', function () {
                if ($this->useDeprecatedTemplate()) {
                    $this->showStylesheet();
                }
            });

            // Maybe load the JS to inject the admin styles.
            add_action(
                'admin_enqueue_scripts',
                function () {
                    wp_enqueue_script(
                        'extendifysdk-editorplus-scripts',
                        EXTENDIFYSDK_BASE_URL . 'public/editorplus/editorplus.min.js',
                        [],
                        '1.0',
                        true
                    );
                }
            );

            // Maybe add the body class name to the front end.
            add_filter(
                'body_class',
                function ($classes) {
                    if ($this->useDeprecatedTemplate()) {
                        $classes[] = 'eplus_styles';
                    }

                    return $classes;
                }
            );

            // Maybe add the body class name to the admin.
            add_filter(
                'admin_body_class',
                function ($classes) {
                    if ($this->useDeprecatedTemplate()) {
                        $classes .= ' eplus_styles';
                    }

                    return $classes;
                }
            );

            // Maybe register the template into WP.
            add_filter('theme_page_templates', function ($templates) {
                if (!$this->useDeprecatedTemplate()) {
                    return $templates;
                }

                return array_merge($templates, $this->templates);
            });

            // Maybe add template to the dropdown list.
            add_filter('wp_insert_post_data', [$this, 'registerProjectTemplates']);

            // Maybe add template file path.
            add_filter('template_include', [$this, 'viewProjectTemplate']);
        }

        /**
         * Checks whether the page needs the EP template
         *
         * @return boolean
         */
        public function useDeprecatedTemplate()
        {
            $post = get_post();
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            if (is_admin() && isset($_GET['post'])) {
                // This will populate on the admin.
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $post = get_post(sanitize_text_field(wp_unslash($_GET['post'])));
            }

            return isset($post->ID) && get_post_meta($post->ID, '_wp_page_template', true) === 'editorplus-template.php';
        }

        /**
         * Used to echo out page template stylesheet if the page template is not active.
         *
         * @return void
         */
        public function showStylesheet()
        {
            $post = get_post();
            $cssContent = apply_filters(
                'extendifysdk_template_css',
                get_post_meta($post->ID, 'extendify_custom_stylesheet', true),
                $post
            );

            // Note that esc_html() cannot be used because `div &gt; span` is not interpreted properly.
            // See: https://github.com/WordPress/WordPress/blob/ccdb1766aead26d4cef79badb015bb2727fefd59/wp-includes/theme.php#L1824-L1833 for reference.
            if ($cssContent) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo "<style id='extendify-custom-stylesheet' type='text/css'>" . wp_strip_all_tags($cssContent) . '</style>';
            }
        }

        /**
         * Adds our template to the pages cache in order to trick WordPress,
         * into thinking the template file exists where it doens't really exist.
         *
         * @param array $attributes - The attributes.
         * @return array
         */
        public function registerProjectTemplates($attributes)
        {
            if (!$this->useDeprecatedTemplate()) {
                return $attributes;
            }

            // Create the key used for the themes cache.
            $cacheKey = 'page_templates-' . wp_hash(get_theme_root() . '/' . get_stylesheet());
            // Retrieve the cache list.
            // If it doesn't exist, or it's empty prepare an array.
            $templates = wp_get_theme()->get_page_templates();
            if (empty($templates)) {
                $templates = [];
            }

            // New cache, therefore remove the old one.
            wp_cache_delete($cacheKey, 'themes');
            // Now add our template to the list of templates by merging our templates.
            // with the existing templates array from the cache.
            $templates = array_merge($templates, $this->templates);
            // Add the modified cache to allow WordPress to pick it up for listing available templates.
            wp_cache_add($cacheKey, $templates, 'themes', 1800);
            return $attributes;
        }

        /**
         * Checks if the template is assigned to the page.
         *
         * @param string $template - The template.
         * @return string
         */
        public function viewProjectTemplate($template)
        {
            $post = get_post();
            if (!$post || !$this->useDeprecatedTemplate()) {
                return $template;
            }

            $currentTemplate = get_post_meta($post->ID, '_wp_page_template', true);

            // Check that the set template is one we have defined.
            if (!is_string($currentTemplate) || !array_key_exists($currentTemplate, $this->templates)) {
                return $template;
            }

            $file = plugin_dir_path(__FILE__) . $currentTemplate;
            if (!file_exists($file)) {
                return $template;
            }

            return $file;
        }
        // phpcs:ignore Squiz.Classes.ClassDeclaration.SpaceBeforeCloseBrace
    }

    add_action('after_setup_theme', ['ExtendifySdkEditorPlus', 'getInstance']);
}//end if
