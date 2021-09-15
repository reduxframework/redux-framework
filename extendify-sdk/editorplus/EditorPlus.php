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
        protected $templates;

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
         * Initializes the plugin by setting filters and administration functions.
         */
        public function __construct()
        {
            if ($this->isSupported()) {
                $this->templates = [];

                \add_action(
                    'admin_enqueue_scripts',
                    function () {
                        // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NoExplicitVersion
                        \wp_enqueue_script(
                            'extendifysdk-editorplus-scripts',
                            EXTENDIFYSDK_BASE_URL . 'public/editorplus/editorplus.min.js',
                            [],
                            false,
                            true
                        );
                    }
                );

                add_action('wp_head', [$this, 'enqueueStylesheet']);

                add_filter(
                    'theme_page_templates',
                    [
                        $this,
                        'addNewTemplate',
                    ]
                );

                // Add a filter to the save post to inject out template into the page cache.
                add_filter(
                    'wp_insert_post_data',
                    [
                        $this,
                        'registerProjectTemplates',
                    ]
                );
                // Add a filter to the template include to determine if the page has our template assigned and return it's path.
                add_filter(
                    'template_include',
                    [
                        $this,
                        'viewProjectTemplate',
                    ]
                );

                $this->templates = ['editorplus-template.php' => 'Extendify Template'];
                add_filter(
                    'body_class',
                    function ($classes) {
                        $classes[] = 'eplus_styles';
                        return $classes;
                    }
                );

                // Registering meta data to store editorplus generated stylesheet of template.
                $postTypes = get_post_types(['_builtin' => false], 'names', 'and');
                $postTypes['post'] = 'post';
                foreach ($postTypes as $postType) {
                    register_meta(
                        $postType,
                        'extendify_custom_stylesheet',
                        [
                            'show_in_rest' => true,
                            'single'       => true,
                            'type'         => 'string',
                            'default'       => '',
                        ]
                    );
                }
            }//end if
        }

        /**
         * Used to echo out page template stylesheet if the page template is not active.
         *
         * @return void
         */
        public function enqueueStylesheet()
        {
            if (!isset($GLOBALS['post']) || !$GLOBALS['post']) {
                return;
            }

            $post = $GLOBALS['post'];
            $cssContent = apply_filters(
                'extendifysdk_template_css',
                get_post_meta($post->ID, 'extendify_custom_stylesheet', true),
                $post
            );

            // Note that esc_html() cannot be used because `div &gt; span` is not interpreted properly.
            // See: https://github.com/WordPress/WordPress/blob/ccdb1766aead26d4cef79badb015bb2727fefd59/wp-includes/theme.php#L1824-L1833 for reference.
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo "<style id='extendify-custom-stylesheet' type='text/css'>" . wp_strip_all_tags($cssContent) . '</style>';
        }

        /**
         * Will check if page templates are supported in the installed wp version.
         *
         * @return bool
         */
        public function isSupported()
        {
            return version_compare(floatval(get_bloginfo('version')), '4.7', '>');
        }

        /**
         * Adds our template to the page dropdown for v4.7+
         *
         * @param array $postsTemplates - Array of page templates.
         * @return array
         */
        public function addNewTemplate($postsTemplates)
        {
            return array_merge($postsTemplates, $this->templates);
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
            // Create the key used for the themes cache.
            $cacheKey = 'page_templates-' . \wp_hash(get_theme_root() . '/' . get_stylesheet());
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
            $post = $GLOBALS['post'];
            if (!$post) {
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
