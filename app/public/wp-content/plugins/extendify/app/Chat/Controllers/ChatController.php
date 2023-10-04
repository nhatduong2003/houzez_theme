<?php
/**
 * Controls Quick Links
 */

namespace Extendify\Chat\Controllers;

use Extendify\Chat\Admin;
use Extendify\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for fetching quick links
 */
class ChatController
{

    /**
     * Retrieve settings for chat interface.
     *
     * @return \WP_REST_Response
     */
    public static function getOptions()
    {
        $options = get_user_option(Admin::OPTIONS_KEY);

        return new \WP_REST_Response([
            'success' => is_array($options),
            'options' => $options,
        ]);
    }

    /**
     * Apply settings for chat interface.
     *
     * @param \WP_REST_Request $request The request.
     * @return \WP_REST_Response
     */
    public static function updateOptions($request)
    {
        $options = get_user_option( Admin::OPTIONS_KEY, [] );
        $updatedOptions = $request->get_param('options');

        if (isset($updatedOptions['experienceLevel'])) {
            $options['experienceLevel'] = $updatedOptions['experienceLevel'];
        }

        if (isset($updatedOptions['showChat'])) {
            $options['showChat'] = $updatedOptions['showChat'];
        }

        $result = update_user_option(get_current_user_id(), Admin::OPTIONS_KEY, $options);

        return new \WP_REST_Response([
            'success' => $result,
            'options' => $options,
        ]);
    }

    /**
     * Rate the answer you got from the chat api.
     *
     * @param \WP_REST_Request $request The request.
     * @return \WP_REST_Response
     */
    public static function rateAnswer($request)
    {
        $response = Http::post('/rate-answer', $request->get_params());
        return new \WP_REST_Response(
            $response,
            wp_remote_retrieve_response_code($response)
        );
    }

    /**
     * Persist the data
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function updateUserMeta($request)
    {
        $params = $request->get_json_params();
        \update_user_meta($params['user'], $params['option'], $params['value']);

        return new \WP_REST_Response(['success' => true]);
    }
}
