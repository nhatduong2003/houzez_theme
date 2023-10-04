<?php
/**
 * Admin.
 */

namespace Extendify\Chat;

use Extendify\PartnerData;
use Extendify\Config;

/**
 * This class handles any file loading for the admin area.
 */
class Admin
{
    /**
     * The instance
     *
     * @var $instance
     */
    public static $instance = null;

    /**
     * Whether to show the chat or not.
     *
     * @var boolean
     */
    public $showChat = false;

    /**
     * The support URL.
     *
     * @var string
     */
    public $supportUrl = '';

    /**
     * The options key.
     *
     * @var string
     */
    const OPTIONS_KEY = 'extendify_chat_globals';

    /**
     * Adds various actions to set up the page
     *
     * @return self|void
     */
    public function __construct()
    {
        if (self::$instance) {
            return self::$instance;
        }

        self::$instance = $this;

        if (!defined('EXTENDIFY_PARTNER_ID')) {
            return;
        }

        $chatData = $this->fetchChatData();
        if (! $chatData) {
            return;
        }

        $this->showChat = isset($chatData['showChat']) ? $chatData['showChat'] : false;
        $this->supportUrl = isset($chatData['supportUrl']) ? $chatData['supportUrl'] : '';

        $this->loadScripts();
    }

    /**
     * Adds scripts to the admin
     *
     * @return void
     */
    public function loadScripts()
    {
        \add_action(
            'admin_enqueue_scripts',
            function () {
                if (!current_user_can(Config::$requiredCapability)) {
                    return;
                }

                // Don't load on the update page (update-core is ok).
                $currentScreen = get_current_screen();
                if ($currentScreen && $currentScreen->id === 'update') {
                    return;
                }

                // Don't show on Launch pages.
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                if (isset($_GET['page']) && $_GET['page'] === 'extendify-launch') {
                    return;
                }

                if (!$this->showChat) {
                    return;
                }

                $this->enqueueGutenbergAssets();

                $version = Config::$environment === 'PRODUCTION' ? Config::$version : uniqid();

                $cssColorVars = PartnerData::cssVariableMapping();
                $cssString = implode('; ', array_map(function ($k, $v) {
                    return "$k: $v";
                }, array_keys($cssColorVars), $cssColorVars));
                wp_add_inline_style(Config::$slug . '-chat-styles', "body { $cssString; }");

                \wp_enqueue_style(
                    Config::$slug . '-chat-styles',
                    EXTENDIFY_BASE_URL . 'public/build/' . Config::$assetManifest['extendify-chat.css'],
                    [],
                    Config::$version,
                    'all'
                );
            }
        );
    }

    /**
     * Fetch the data from the partner-data API endpoint.
     *
     * @return array
     */
    private function fetchChatData()
    {
        $chatData = get_transient('extendify_chat_data');

        if ($chatData !== false) {
            return $chatData;
        }

        if (!defined('EXTENDIFY_PARTNER_ID')) {
            return [];
        }

        $apiUrl = Config::$config['api']['onboarding'];

        $response = wp_remote_get(
            add_query_arg(
                ['partner' => EXTENDIFY_PARTNER_ID],
                $apiUrl . '/partner-data/'
            ),
            ['headers' => ['Accept' => 'application/json']]
        );

        if (is_wp_error($response)) {
            return [];
        }

        $result = json_decode(wp_remote_retrieve_body($response), true);
        $data = isset($result['data']) ? $result['data'] : [];

        $chatData = [
            'showChat' => isset($data['showChat']) ? $data['showChat'] : false,
            'supportUrl' => isset($data['supportUrl']) ? $data['supportUrl'] : '',
            'showAIConsent' => isset($data['showAIConsent']) ? $data['showAIConsent'] : false,
            'consentTermsUrl' => isset($data['consentTermsUrl']) ? $data['consentTermsUrl'] : '',
            'data' => $data,
        ];

        if (Config::$environment === 'DEVELOPMENT') {
            $chatData['showChat'] = true;
        }

        set_transient('extendify_chat_data', $chatData, DAY_IN_SECONDS);
        return $chatData;
    }

    /**
     * Initialize the options if they don't exist.
     * Shouldn't be run during construct, since get_user_option isn't available.
     *
     * @return array
     */
    private function getOptions()
    {
        $options = get_user_option(self::OPTIONS_KEY);

        if (!$options) {
            $options = [
                'showChat' => true,
                'experienceLevel' => 'beginner',
            ];

            update_user_option(get_current_user_id(), self::OPTIONS_KEY, $options);
        }

        return $options;
    }

    /**
     * Enqueues Gutenberg stuff on a non-Gutenberg page.
     *
     * @return void
     */
    public function enqueueGutenbergAssets()
    {
        $version = Config::$environment === 'PRODUCTION' ? Config::$version : uniqid();
        $scriptAssetPath = EXTENDIFY_PATH . 'public/build/' . Config::$assetManifest['extendify-chat.php'];
        $fallback = [
            'dependencies' => [],
            'version' => $version,
        ];

        $chatDependencies = file_exists($scriptAssetPath) ? require $scriptAssetPath : $fallback;

        foreach ($chatDependencies['dependencies'] as $style) {
            wp_enqueue_style($style);
        }

        \wp_enqueue_script(
            Config::$slug . '-chat-scripts',
            EXTENDIFY_BASE_URL . 'public/build/' . Config::$assetManifest['extendify-chat.js'],
            $chatDependencies['dependencies'],
            $chatDependencies['version'],
            true
        );

        \wp_set_script_translations(Config::$slug . '-chat-scripts', 'extendify');

        $chatOptions = $this->getOptions();

        $chatData = $this->fetchChatData();
        $userConsent = get_user_meta(get_current_user_id(), 'extendify_ai_consent', true);
        $userGaveConsent = $userConsent ? $userConsent : false;

        \wp_add_inline_script(
            Config::$slug . '-chat-scripts',
            'window.extChatData = ' . wp_json_encode([
                'nonce' => \wp_create_nonce('wp_rest'),
                'root' => \esc_url_raw(\rest_url(Config::$slug . '/' . Config::$apiVersion)),
                'showChat' => isset($chatOptions['showChat']) ? $chatOptions['showChat'] : false,
                'api' => \esc_url_raw(Config::$config['api']['chat']),
                'devbuild' => \esc_attr(Config::$environment === 'DEVELOPMENT'),
                'partnerId' => \esc_attr(EXTENDIFY_PARTNER_ID),
                'siteId' => \get_option('extendify_site_id', false),
                'wpVersion' => \get_bloginfo('version'),
                'isBlockTheme' => function_exists('wp_is_block_theme') ? wp_is_block_theme() : false,
                'supportUrl' => $this->supportUrl,
                'experienceLevel' => isset($chatOptions['experienceLevel']) ? $chatOptions['experienceLevel'] : 'beginner',
                'showAIConsent' => isset($chatData['showAIConsent']) ? $chatData['showAIConsent'] : false,
                'consentTermsUrl' => isset($chatData['consentTermsUrl']) ? $chatData['consentTermsUrl'] : '',
                'userId' => get_current_user_id(),
                'userGaveConsent' => $userGaveConsent,
                'data' => $chatData,
            ]),
            'before'
        );
    }
}
