<?php
/**
 * The Partner Settings
 */

namespace Extendify;

/**
 * Controller for handling partner settings
 */
class PartnerData
{

    /**
     * The partner id
     *
     * @var string
     */
    public static $id = 'no-partner';

    /**
     * The partner logo
     *
     * @var string
     */
    public static $logo = '';

    /**
     * The partner display name
     *
     * @var string
     */
    public static $name = '';

    /**
     * The partner display name
     *
     * @var string
     */
    public static $colors = [];

    /**
     * The partner display name
     *
     * @var boolean
     */
    public static $disableRecommendations = false;

    /**
     * Set up and collect partner data
     *
     * @return void
     */
    public function __construct()
    {
        if (isset($GLOBALS['extendify_sdk_partner']) && $GLOBALS['extendify_sdk_partner']) {
            self::$id = $GLOBALS['extendify_sdk_partner'];
        }

        // Always use the partner ID if set as a constant.
        if (defined('EXTENDIFY_PARTNER_ID')) {
            self::$id = constant('EXTENDIFY_PARTNER_ID');
        }

        // If the plugin has no partner, don't fetch data.
        if (self::$id === 'no-partner') {
            return;
        }

        $data = self::getPartnerData();

        if (isset($data['disableRecommendations'])) {
            self::$disableRecommendations = $data['disableRecommendations'];
            unset($data['disableRecommendations']);
        }

        if (isset($data['logo'])) {
            self::$logo = $data['logo'][0]['thumbnails']['large']['url'];
            unset($data['logo']);
        }

        if (isset($data['Name'])) {
            self::$name = $data['Name'];
            unset($data['Name']);
        }

        self::$colors = $data;
    }

    /**
     * Retrieve partner data from a transient or from the API.
     *
     * @return array
     */
    public static function getPartnerData()
    {
        // If the transient is already set, don't fetch again.
        $transientData = get_transient('extendify_partner_data');
        // Check the secondaryColor as the Launch Command does not add this in some versions.
        if ($transientData !== false && isset($transientData['secondaryColor'])) {
            return get_option('extendify_partner_data', []);
        }

        $response = wp_remote_get(
            add_query_arg(
                ['partner' => self::$id],
                'https://dashboard.extendify.com/api/onboarding/partner-data/'
            ),
            ['headers' => ['Accept' => 'application/json']]
        );

        if (is_wp_error($response)) {
            // If the request fails, try again in 24 hours.
            set_transient('extendify_partner_data', [], DAY_IN_SECONDS);
            return get_option('extendify_partner_data', []);
        }

        $result = json_decode(wp_remote_retrieve_body($response), true);

        $data = [];
        if (isset($result['data'])) {
            $keys = ['foregroundColor', 'backgroundColor', 'secondaryColor', 'logo', 'Name', 'disableRecommendations'];
            $data = array_intersect_key($result['data'], array_flip($keys));
        }

        $data['secondaryColorText'] = '#ffffff';
        if (!isset($data['secondaryColor'])) {
            $data['secondaryColor'] = $data['backgroundColor'];
        }

        // Transient is used to mark the time, but the data is put into an option,
        // so that in case of network issues, we can still retrun old data.
        set_transient('extendify_partner_data', $data, MONTH_IN_SECONDS);
        update_option('extendify_partner_data', $data);
        return $data;
    }

    /**
     * Return colors mapped as css variables
     *
     * @return array
     */
    public static function cssVariableMapping()
    {
        $mapping = [
            'backgroundColor' => '--ext-banner-main',
            'foregroundColor' => '--ext-banner-text',
            'secondaryColor' => '--ext-design-main',
            'secondaryColorText' => '--ext-design-text',
        ];
        $cssVariables = [];
        $colors = self::$colors;
        foreach ($mapping as $color => $variable) {
            if (isset($colors[$color])) {
                $cssVariables[$variable] = $colors[$color];
            }
        }

        return $cssVariables;
    }
}
