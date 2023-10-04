<?php
/**
 * Data Controller
 */

namespace Extendify\Onboarding\Controllers;

use Extendify\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for handling general data
 */
class DataController
{
    /**
     * Get Goals information.
     *
     * @return \WP_REST_Response
     */
    public static function getGoals()
    {
        $response = Http::get('/goals');
        return new \WP_REST_Response(
            $response,
            wp_remote_retrieve_response_code($response)
        );
    }

    /**
     * Get Goals information.
     *
     * @return \WP_REST_Response
     */
    public static function getSuggestedPlugins()
    {
        $response = Http::get('/suggested-plugins');
        return new \WP_REST_Response(
            $response,
            wp_remote_retrieve_response_code($response)
        );
    }

    /**
     * Just here to check for 200 (vs server rate limting)
     *
     * @return \WP_REST_Response
     */
    public static function ping()
    {
        return new \WP_REST_Response(true, 200);
    }
}
