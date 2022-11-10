<?php
/**
 * Insights setup
 */

namespace Extendify;

/**
 * Controller for handling various Insights related things.
 */
class Insights
{
    /**
     * An array of active tests. 'A' should be the control.
     * For weighted tests, try ['A', 'A', 'A', 'A', 'B']
     *
     * @var array
     */
    protected $activeTests = [
        'remove-dont-see-inputs' => [
            'A',
            'B',
        ],
        'launch-site-vs-next' => [
            'A',
            'B',
        ],
    ];

    /**
     * Process the readme file to get version and name
     *
     * @return void
     */
    public function __construct()
    {
        // If there isn't a siteId, then create one.
        if (!\get_option('extendify_site_id', false)) {
            \update_option('extendify_site_id', \wp_generate_uuid4());
            if (defined('EXTENDIFY_INSIGHTS_URL') && class_exists('ExtendifyInsights')) {
                // If we are generating an ID, then trigger the job here too.
                // This only runs if they have opted in.
                add_action('init', function () {
                    wp_schedule_single_event(time(), 'extendify_insights');
                    spawn_cron();
                });
            }
        }

        $this->setUpActiveTests();
        $this->filterExternalInsights();
    }

    /**
     * Returns the active tests for the user, and sets up tests as needed.
     *
     * @return void
     */
    public function setUpActiveTests()
    {
        // Make sure that the active tests are set.
        $currentTests = \get_option('extendify_active_tests', []);
        $newTests = array_map(function ($test) {
            // Pick from value randomly.
            return $test[array_rand($test)];
        }, array_diff_key($this->activeTests, $currentTests));
        $testsCombined = array_merge($currentTests, $newTests);
        if ($newTests) {
            \update_option('extendify_active_tests', $testsCombined);
        }
    }

    /**
     * Add additional data to the opt-in insights
     *
     * @return void
     */
    public function filterExternalInsights()
    {
        add_filter('extendify_insights_data', function ($data) {
            $insights = array_merge($data, [
                'launch' => defined('EXTENDIFY_SHOW_ONBOARDING') && constant('EXTENDIFY_SHOW_ONBOARDING'),
                'partner' => defined('EXTENDIFY_PARTNER_ID') ? constant('EXTENDIFY_PARTNER_ID') : null,
                'siteCreatedAt' => get_user_option('user_registered', 1),
            ]);
            return $insights;
        });
    }
}
