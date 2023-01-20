<?php
/**
 * Simple router to handle admin page loading
 */

namespace Extendify;

use Extendify\Config;
use Extendify\Library\AdminPage as LibraryAdminPage;
use Extendify\Assist\AdminPage as AssistAdminPage;
use Extendify\Assist\Controllers\TasksController as AssistTasksController;
use Extendify\Onboarding\AdminPage as OnboardingAdminPage;

/**
 * This class handles routing when the main admin button is pressed.
 */
class AdminPageRouter
{
    /**
     * Adds various actions to set up the page
     *
     * @return void
     */
    public function __construct()
    {
        // Don't show the admin page if not using the standalone version (e.g. Redux).
        if (!Config::$standalone) {
            return;
        }

        // When Launch is finished, fire this to set the correct permalinks.
        // phpcs:ignore WordPress.Security.NonceVerification
        if (isset($_GET['extendify-launch-success'])) {
            \add_action('admin_init', function () {
                \flush_rewrite_rules();
            });
        }

        // Add top admin page to handle redirects. Everything
        // else will be a subpage of this.
        \add_action('admin_menu', [ $this, 'addAdminMenu' ]);

        \add_action('admin_menu', function () {
            // Load the Assist page when Launch is finished.
            if (Config::$showAssist && Config::$launchCompleted) {
                $assist = new AssistAdminPage();
                $cb = [$assist, 'pageContent'];
                $this->addSubMenu('Assist', $assist->slug, $cb);
            }

            // Always load the Library page.
            $library = new LibraryAdminPage();
            $cb = [$library, 'pageContent'];
            $this->addSubMenu('Library', $library->slug, $cb);

            // Show the Launch menu for dev users.
            if ((Config::$showOnboarding && !Config::$launchCompleted) || Config::$environment === 'DEVELOPMENT') {
                $onboarding = new OnboardingAdminPage();
                $cb = [$onboarding, 'pageContent'];
                $this->addSubMenu('Launch', $onboarding->slug, $cb);
            }
        });

        // Hide the menu items unless in dev mode.
        if (Config::$environment === 'PRODUCTION') {
            add_action('admin_head', function () {
                echo '<style>
                #toplevel_page_extendify-admin-page .wp-submenu {
                    display:none!important;
                }
                #toplevel_page_extendify-admin-page::after {
                    content:none!important;
                }
                </style>';
            });
        }

        // If the user is redirected to this while visiting our url, intercept it.
        \add_filter('wp_redirect', function ($url) {
            // Check for extendify-launch-success as other plugins will not override
            // this as they intercept the request.
            // Special treatment for Yoast to disable their redirect when installing.
            if ($url === \admin_url() . 'admin.php?page=wpseo_installation_successful_free') {
                return \admin_url() . 'admin.php?page=extendify-assist';
            }

            // phpcs:ignore WordPress.Security.NonceVerification
            if (isset($_GET['extendify-launch-success'])) {
                return \admin_url() . $this->getRoute();
            }

            return $url;
        }, 9999);

        // Intercept requests and redirect as needed.
        // phpcs:ignore WordPress.Security.NonceVerification
        if (isset($_GET['page']) && $_GET['page'] === 'extendify-admin-page') {
            header('Location: ' . \admin_url() . $this->getRoute(), true, 302);
            exit;
        }
    }

    /**
     * A helper for handling sub menus
     *
     * @param string   $name     - The menu name.
     * @param string   $slug     - The menu slug.
     * @param callable $callback - The callback to render the page.
     *
     * @return void
     */
    public function addSubMenu($name, $slug, $callback = '')
    {
        \add_submenu_page(
            'extendify-admin-page',
            $name,
            $name,
            Config::$requiredCapability,
            $slug,
            $callback
        );
    }

    /**
     * Adds Extendify top menu
     *
     * @return void
     */
    public function addAdminMenu()
    {
        $tasksController = new AssistTasksController();
        $remainingTasks = $tasksController->getRemainingCount();
        $badgeCount = $remainingTasks > 9 ? '9' : strval($remainingTasks);
        $menuLabel = Config::$launchCompleted ? __('Site Assistant', 'extendify') : __('Site Launcher', 'extendify');
        $menuLabel = Config::$showOnboarding ? $menuLabel : 'Extendify';
        $menuLabel = sprintf('%1$s <span class="extendify-assist-badge-count" data-test="assist-badge-count"></span>', $menuLabel);

        \add_menu_page(
            'Extendify',
            $menuLabel,
            Config::$requiredCapability,
            'extendify-admin-page',
            '__return_null',
            // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
            'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5009 2H14.9873C15.9747 2 16.3322 2.10161 16.6934 2.29127C17.0546 2.48169 17.3372 2.76092 17.5304 3.11616C17.7237 3.47216 17.826 3.8244 17.826 4.79756V8.23336C17.826 9.20652 17.7229 9.55876 17.5304 9.91475C17.3372 10.2708 17.0539 10.5492 16.6934 10.7396C16.5319 10.8248 16.3708 10.8948 16.154 10.945V13.6794C16.154 15.1824 15.9947 15.7264 15.6975 16.2762C15.3991 16.826 14.9615 17.2561 14.4048 17.5502C13.8469 17.8442 13.2949 18 11.7698 18H6.38538C4.86028 18 4.30828 17.8431 3.75038 17.5502C3.19247 17.2561 2.75606 16.8248 2.45765 16.2762C2.15923 15.7275 2 15.1824 2 13.6794V8.37426C2 6.87129 2.15923 6.32729 2.45647 5.77748C2.75488 5.22767 3.19247 4.79642 3.75038 4.50234C4.3071 4.20941 4.86028 4.05249 6.38538 4.05249H8.69081C8.73405 3.60597 8.82426 3.36234 8.95694 3.11692C9.15016 2.76092 9.4335 2.48169 9.79474 2.29127C10.1552 2.10161 10.5134 2 11.5009 2ZM9.709 4.18698C9.709 3.54929 10.2336 3.03234 10.8807 3.03234H15.6066C16.2538 3.03234 16.7784 3.54929 16.7784 4.18698V8.84395C16.7784 9.48164 16.2538 9.99859 15.6066 9.99859H10.8807C10.2336 9.99859 9.709 9.48164 9.709 8.84395V4.18698Z" fill="currentColor" /> </svg>'),
            Config::$showOnboarding ? 2 : null
        );
    }

    /**
     * Routes pages accordingly
     *
     * @return string
     */
    public function getRoute()
    {
        // If dev, redirect to assist always.
        if (Config::$environment === 'DEVELOPMENT') {
            return 'admin.php?page=extendify-assist';
        }

        // If Launch/Assist isn't enabled, show the Library page.
        if (!Config::$showOnboarding) {
            return 'admin.php?page=extendify-welcome';
        }

        // If they've yet to complete launch, send them back to Launch.
        if (!Config::$launchCompleted) {
            return 'admin.php?page=extendify-launch';
        }

        // If they made it this far, they can go to Assist.
        return 'admin.php?page=extendify-assist';
    }
}
