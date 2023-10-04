<?php
/**
 * Controls Draft
 */

namespace Extendify\Draft\Controllers;

use Extendify\Draft\Admin;
use Extendify\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for fetching Draft user settings
 */
class DraftController
{
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
