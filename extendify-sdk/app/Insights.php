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
    ];

    /**
     * Process the readme file to get version and name
     *
     * @return void
     */
    public function __construct()
    {
        $this->setUpActiveTests();
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
}
