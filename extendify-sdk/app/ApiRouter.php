<?php
/**
 * API router
 */

namespace Extendify\Library;

use Extendify\Library\App;
use Extendify\Library\Http;

/**
 * Simple router for the REST Endpoints
 */
class ApiRouter extends \WP_REST_Controller
{

    /**
     * The class instance.
     *
     * @var $instance
     */
    protected static $instance = null;

    /**
     * The capablity required for access.
     *
     * @var $capability
     */
    protected $capability;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->capability = App::$requiredCapability;
        add_filter(
            'rest_request_before_callbacks',
            // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassBeforeLastUsed
            function ($response, $handler, $request) {
                // Add the request to our helper class.
                if ($request->get_header('x_extendify')) {
                    Http::init($request);
                }

                return $response;
            },
            10,
            3
        );
    }

    /**
     * Check the authorization of the request
     *
     * @return boolean
     */
    public function checkPermission()
    {
        // Check for the nonce on the server (used by WP REST).
        if (isset($_SERVER['HTTP_X_WP_NONCE']) && \wp_verify_nonce(sanitize_text_field(wp_unslash($_SERVER['HTTP_X_WP_NONCE'])), 'wp_rest')) {
            return \current_user_can($this->capability);
        }

        return false;
    }

    /**
     * Register dynamic routes
     *
     * @param string   $namespace - The api name space.
     * @param string   $endpoint  - The endpoint.
     * @param function $callback  - The callback to run.
     *
     * @return void
     */
    public function getHandler($namespace, $endpoint, $callback)
    {
        \register_rest_route(
            $namespace,
            $endpoint,
            [
                'methods' => 'GET',
                'callback' => $callback,
                'permission_callback' => [
                    $this,
                    'checkPermission',
                ],
            ]
        );
    }

    /**
     * The post handler
     *
     * @param string $namespace - The api name space.
     * @param string $endpoint  - The endpoint.
     * @param string $callback  - The callback to run.
     *
     * @return void
     */
    public function postHandler($namespace, $endpoint, $callback)
    {
        \register_rest_route(
            $namespace,
            $endpoint,
            [
                'methods' => 'POST',
                'callback' => $callback,
                'permission_callback' => [
                    $this,
                    'checkPermission',
                ],
            ]
        );
    }

    /**
     * The caller
     *
     * @param string $name      - The name of the method to call.
     * @param array  $arguments - The arguments to pass in.
     *
     * @return mixed
     */
    public static function __callStatic($name, array $arguments)
    {
        $name = "{$name}Handler";
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }

        $r = self::$instance;
        return $r->$name(APP::$slug . '/' . APP::$apiVersion, ...$arguments);
    }
}
