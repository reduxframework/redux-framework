<?php
/**
 * Admin Welcome page.
 */

namespace Extendify\Library;

use Extendify\Config;

/**
 * This class handles the Welcome page on the admin panel.
 */
class AdminPage
{

    /**
     * The admin page slug
     *
     * @var $string
     */
    public $slug = 'extendify-welcome';

    /**
     * Adds various actions to set up the page
     *
     * @return void
     */
    public function __construct()
    {
        \add_action(
            'in_admin_header',
            function () {
                // phpcs:ignore WordPress.Security.NonceVerification
                if (isset($_GET['page']) && $_GET['page'] === $this->slug) {
                    \remove_all_actions('admin_notices');
                    \remove_all_actions('all_admin_notices');
                }
            },
            1000
        );

        \add_action(
            'admin_enqueue_scripts',
            function () {
                // phpcs:ignore WordPress.Security.NonceVerification
                if (isset($_GET['page']) && $_GET['page'] === $this->slug) {
                    \wp_enqueue_style(
                        'extendify-welcome',
                        EXTENDIFY_URL . 'public/admin-page/welcome.css',
                        [],
                        Config::$environment === 'PRODUCTION' ? Config::$version : uniqid()
                    );
                }
            }
        );
    }

    /**
     * Settings page output
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function pageContent()
    {
        ?>
        <div class="extendify-outer-container">
            <div class="wrap welcome-container">
                <?php if (Config::$showAssist) : ?>
                    <ul class="extendify-welcome-tabs">
                        <li><a href="<?php echo \esc_url(\admin_url('admin.php?page=extendify-assist')); ?>">Assist</a></li>
                        <li class="active"><a href="<?php echo \esc_url(\admin_url('admin.php?page=extendify-welcome')); ?>">Library</a></li>
                        <li class="cta-button"><a href="<?php echo \esc_url_raw(\get_home_url()); ?>" target="_blank"><?php echo \esc_html(__('View Site', 'extendify')); ?></a></li>
                    </ul>
                <?php endif; ?>
                <div class="welcome-header">
                    <img alt="
                        <?php
                            echo \wp_sprintf(
                                /* translators: %s: The name of the plugin, Extendify */
                                esc_html__('%s Banner', 'extendify'),
                                'Extendify'
                            );
                        ?>
                    " src="<?php echo \esc_url(EXTENDIFY_URL . 'public/assets/welcome-banner.jpg'); ?>">
                </div>
                <hr class="is-small"/>
                <div class="welcome-section">
                    <h2 class="aligncenter">
                        <?php
                            echo \wp_sprintf(
                                /* translators: %s: The name of the plugin, Extendify */
                                esc_html__('Welcome to %s', 'extendify'),
                                'Extendify'
                            );
                        ?>
                    </h2>
                    <p class="aligncenter is-subheading">
                        <?php
                            echo \wp_sprintf(
                                /* translators: %s: The name of the plugin, Extendify */
                                esc_html__('%s is a massive library of drop-in block patterns easily customized to your liking. Each pattern is meticulously designed to work with your existing WordPress theme.', 'extendify'),
                                'Extendify'
                            );
                        ?>
                    </p>
                </div>
                <hr/>
                <div class="welcome-section has-2-columns has-gutters is-wider-right">
                    <div class="column is-edge-to-edge">
                        <h3>
                            <?php
                            echo \wp_sprintf(
                                /* translators: %s: Extendify Library term */
                                esc_html__('1. Open the %s', 'extendify'),
                                'Extendify Library'
                            );
                            ?>
                        </h3>
                        <p>
                            <?php
                                echo \wp_sprintf(
                                    /* translators: %s: Extendify Library term */
                                    esc_html__("When editing a page or post within the block editor, you'll see the %s button within the editor's header", 'extendify'),
                                    'Extendify Library'
                                );
                            ?>
                        </p>
                        <p>
                            <?php
                            // translators: %1$s = URL.
                            echo \wp_sprintf(esc_html__('You may also add a new page with the library opened for you by %1$s.', 'extendify'), '<a href="' . \esc_url(\admin_url('post-new.php?post_type=page&ext-open')) . '">' . esc_html__('clicking here', 'extendify') . '</a>'); ?>
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
                            <?php esc_html_e('2. Choose a site industry', 'extendify'); ?>
                        </h3>
                        <p>
                            <?php esc_html_e('With the library open, you can set your site industry - or type - which will surface the perfect industry-specific patterns and full page layouts to drop onto your website.', 'extendify'); ?>
                        </p>
                        <p>
                            <?php
                                echo \wp_sprintf(
                                    /* translators: %s: The name of the plugin, Extendify */
                                    esc_html__('%s supports over sixty types with new industries added regularly.', 'extendify'),
                                    'Extendify'
                                );
                            ?>
                        </p>
                    </div>
                </div>
                <div class="welcome-section has-2-columns has-gutters is-wider-right">
                    <div class="column is-edge-to-edge">
                        <h3>
                            <?php esc_html_e('3. Browse Patterns & Layouts.', 'extendify'); ?>
                        </h3>
                        <p>
                            <?php
                                echo \wp_sprintf(
                                    /* translators: %s: The name of the plugin, Extendify */
                                    esc_html__('Search by industry, contents, and design attributes. %s has thousands of best-in-class block patterns. Find what you love and add it to the page - done!', 'extendify'),
                                    'Extendify'
                                );
                            ?>
                        </p>
                        <p>
                            <?php esc_html_e("You'll find beautiful high fidelity Gutenberg content to add to your pages in no time!", 'extendify'); ?>
                        </p>
                    </div>
                    <div class="column welcome-image is-vertically-aligned-center is-edge-to-edge">
                        <img src="<?php echo \esc_url(EXTENDIFY_URL . 'public/assets/welcome-block-3.jpg'); ?>" alt=""/>
                    </div>
                </div>
                <hr class="is-small"/>
                <?php if (!defined('EXTENDIFY_SITE_LICENSE')) : ?>
                    <div class="welcome-section">
                        <h2 class="aligncenter">
                            <?php
                                echo \wp_sprintf(
                                    /* translators: %s: The name of the plugin, Extendify */
                                    esc_html__('Upgrade to %s Pro', 'extendify'),
                                    'Extendify'
                                );
                            ?>
                        </h2>
                        <p class="aligncenter is-subheading">
                            <?php esc_html_e('Do you want more patterns and layouts - without limits? Choose one of our plans and receive unlimited access to our complete library.', 'extendify'); ?>
                        </p>
                    </div>
                    <a href="https://extendify.com/pricing/?utm_source=welcome&amp;utm_medium=settings&amp;utm_campaign=get_started&amp;utm_campaign=get_started" class="button button-primary components-button">
                        <?php echo esc_html__('View Pricing', 'extendify'); ?></a>
                    <hr/>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
