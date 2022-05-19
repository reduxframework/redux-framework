<?php
/**
 * Admin Welcome page.
 */

namespace Extendify\Library;

use Extendify\Library\App;

/**
 * This class handles the Welcome page on the admin panel.
 */
class Welcome
{

    /**
     * Adds various actions to set up the page
     *
     * @return void
     */
    public function __construct()
    {
        if (App::$standalone) {
            \add_action('admin_menu', [ $this, 'addAdminMenu' ]);

            $this->loadScripts();
        }
    }

    /**
     * Adds Extendify menu to admin panel.
     *
     * @return void
     */
    public function addAdminMenu()
    {
        $raw = \wp_remote_get(EXTENDIFY_URL . 'public/assets/extendify-logo.svg');
        if (\is_wp_error($raw)) {
            $svg = '';
        } else {
            $svg = \wp_remote_retrieve_body($raw);
        }

        add_menu_page(
            'Extendify',
            'Extendify',
            App::$requiredCapability,
            'extendify',
            [
                $this,
                'createAdminPage',
            ],
            // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
            'data:image/svg+xml;base64,' . base64_encode($svg)
        );
    }

    /**
     * Settings page output
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function createAdminPage()
    {
        ?>
        <div class="extendify-outer-container">
            <div class="wrap welcome-container">
                <div class="welcome-header">
                    <img alt="<?php \esc_html_e('Extendify Banner', 'extendify'); ?>" src="<?php echo \esc_url(EXTENDIFY_URL . 'public/assets/welcome-banner.jpg'); ?>">
                </div>
                <hr class="is-small"/>
                <div class="welcome-section">
                    <h2 class="aligncenter">
                        <?php \esc_html_e('Welcome to Extendify', 'extendify'); ?>
                    </h2>
                    <p class="aligncenter is-subheading">
                        <?php \esc_html_e('Extendify is a massive library of drop-in black patterns easily customized to your liking. Each pattern is meticulously designed to work with your existing WordPress theme.', 'extendify'); ?>
                    </p>
                </div>
                <hr/>
                <div class="welcome-section has-2-columns has-gutters is-wider-right">
                    <div class="column is-edge-to-edge">
                        <h3>
                            <?php \esc_html_e('1. Open the Extendify Library', 'extendify'); ?>
                        </h3>
                        <p>
                            <?php \esc_html_e("When editing a page or post within the block editor, you'll see the Extendify library button within the editor's header", 'extendify'); ?>
                        </p>
                        <p>
                            <?php
                            // translators: %1$s = URL.
                            echo \wp_sprintf(\esc_html__('You may also add a new page with the library opened for you by %1$s.', 'extendify'), '<a href="' . \esc_url(\admin_url('post-new.php?post_type=page&ext-open')) . '">' . \esc_html__('clicking here', 'extendify') . '</a>'); ?>
                        </p>
                    </div>
                    <div class="column welcome-image is-vertically-aligned-center is-edge-to-edge">
                        <img src="<?php echo \esc_url(EXTENDIFY_URL . 'public/assets/welcome-block-1.jpg'); ?>" alt=""/>
                    </div>
                </div>
                <div class="welcome-section has-2-columns has-gutters is-wider-left">
                    <div class="column welcome-image is-vertically-aligned-center is-edge-to-edge">
                        <img src="<?php echo \esc_url(EXTENDIFY_URL . 'public/assets/welcome-block-2.jpg'); ?>" alt=""/>
                    </div>
                    <div class="column is-edge-to-edge">
                        <h3>
                            <?php \esc_html_e('2. Choose a site industry', 'extendify'); ?>
                        </h3>
                        <p>
                            <?php \esc_html_e('With the library open, you can set your site industry - or type - which will surface the perfect industry-specific patterns and full page layouts to drop onto your website.', 'extendify'); ?>
                        </p>
                        <p>
                            <?php \esc_html_e('Extendify supports over sixty types with new industries added regularly.', 'extendify'); ?>
                        </p>
                    </div>
                </div>
                <div class="welcome-section has-2-columns has-gutters is-wider-right">
                    <div class="column is-edge-to-edge">
                        <h3>
                            <?php \esc_html_e('3. Browse Patterns & Layouts.', 'extendify'); ?>
                        </h3>
                        <p>
                            <?php \esc_html_e('Search by industry, contents, and design attributes. Extendify has thousands of best-in-class block patterns. Find what you love and add it to the page - done!', 'extendify'); ?>
                        </p>
                        <p>
                            <?php \esc_html_e("You'll find beautiful high fidelity Gutenberg content to add to your pages in no time!", 'extendify'); ?>
                        </p>
                    </div>
                    <div class="column welcome-image is-vertically-aligned-center is-edge-to-edge">
                        <img src="<?php echo \esc_url(EXTENDIFY_URL . 'public/assets/welcome-block-3.jpg'); ?>" alt=""/>
                    </div>
                </div>
                <hr class="is-small"/>
                <div class="welcome-section">
                    <h2 class="aligncenter">
                        <?php \esc_html_e('Upgrade to Extendify Pro', 'extendify'); ?>
                    </h2>
                    <p class="aligncenter is-subheading">
                        <?php \esc_html_e('Do you want more patterns and layouts - without limits? Choose one of our plans and receive unlimited access to our complete library.', 'extendify'); ?>
                    </p>
                </div>
                <a href="https://extendify.com/pricing/?utm_source=welcome&amp;utm_medium=settings&amp;utm_campaign=get_started&amp;utm_campaign=get_started" class="button button-primary components-button">
                    <?php echo \esc_html__('View Pricing', 'extendify'); ?></a>
                <hr/>
            </div>
        </div>
        <?php
    }

    /**
     * Adds scripts and styles to every page is enabled
     *
     * @return void
     */
    public function loadScripts()
    {
        // No nonce for _GET.
        // phpcs:ignore WordPress.Security.NonceVerification
        if (isset($_GET['page']) && $_GET['page'] === 'extendify') {
            \add_action(
                'in_admin_header',
                function () {
                    \remove_all_actions('admin_notices');
                    \remove_all_actions('all_admin_notices');
                },
                1000
            );

            \add_action(
                'admin_enqueue_scripts',
                function () {
                    \wp_enqueue_style(
                        'extendify-welcome',
                        EXTENDIFY_URL . 'public/admin-page/welcome.css',
                        [],
                        App::$version
                    );
                }
            );
        }//end if
    }
}
