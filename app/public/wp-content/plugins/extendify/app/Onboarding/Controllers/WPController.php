<?php
/**
 * WP Controller
 */

namespace Extendify\Onboarding\Controllers;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for interacting with WordPress.
 */
class WPController
{
    /**
     * Persist the data
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function updateOption($request)
    {
        $params = $request->get_json_params();
        \update_option($params['option'], $params['value']);

        return new \WP_REST_Response(['success' => true]);
    }

    /**
     * Get a setting from the options table
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function getOption($request)
    {
        $value = \get_option($request->get_param('option'), null);
        return new \WP_REST_Response([
            'success' => true,
            'data' => $value,
        ]);
    }

    /**
     * Get the list of active plugins slugs
     *
     * @return \WP_REST_Response
     */
    public static function getActivePlugins()
    {
        return new \WP_REST_Response([
            'success' => true,
            'data' => \get_option('active_plugins', null),
        ]);
    }

    /**
     * This function will force the regenerating of the cache.
     *
     * @return \WP_REST_Response
     */
    public static function prefetchAssistData()
    {
        if (class_exists(\Extendify\Assist\DataProvider\ResourceData::class)) {
            (new \Extendify\Assist\DataProvider\ResourceData())->cache();
        }

        return new \WP_REST_Response(true, 200);
    }

}
