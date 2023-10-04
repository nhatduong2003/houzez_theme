<?php
/**
 * Admin.
 */

namespace Extendify\Assist;

use Extendify\Assist\DataProvider\ResourceData;
use Extendify\Assist\Controllers\GlobalsController;
use Extendify\Assist\Controllers\RecommendationsController;
use Extendify\Assist\Controllers\RouterController;
use Extendify\Assist\Controllers\SupportArticlesController;
use Extendify\Assist\Controllers\TasksController;
use Extendify\Assist\Controllers\TourController;
use Extendify\Assist\Controllers\UserSelectionController;
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

        if (PartnerData::$id === 'no-partner' && Config::$environment === 'PRODUCTION') {
            return;
        }

        $this->loadScripts();

        add_action('after_setup_theme', function () {
            // phpcs:ignore WordPress.Security.NonceVerification
            if (isset($_GET['extendify-disable-admin-bar'])) {
                show_admin_bar(false);
            }
        });

        ResourceData::scheduleCache();
    }

    /**
     * Adds scripts to the admin
     *
     * @return void
     */
    public function loadScripts()
    {
        \add_action('admin_enqueue_scripts', [$this, 'loadPageScripts']);

        \add_action('admin_enqueue_scripts', [$this, 'loadGlobalScripts']);
        \add_action('wp_enqueue_scripts', [$this, 'loadGlobalScripts']);
    }

    /**
     * Adds scripts to the main admin page
     *
     * @return void
     */
    public function loadPageScripts()
    {
        if (!current_user_can(Config::$requiredCapability)) {
            return;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!isset($_GET['page']) || $_GET['page'] !== 'extendify-assist') {
            return;
        }

        $this->enqueueWithContext('page');
    }

    /**
     * Adds scripts to every page
     *
     * @return void
     */
    public function loadGlobalScripts()
    {
        if (!current_user_can(Config::$requiredCapability)) {
            return;
        }

        // Don't load on Launch.
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (isset($_GET['page']) && $_GET['page'] === 'extendify-launch') {
            return;
        }

        $this->enqueueWithContext('global');
    }

    /**
     * Enqueue the defined scripts
     *
     * @param string $context The context to enqueue the scripts in.
     *
     * @return void
     */
    public function enqueueWithContext($context)
    {
        $version = Config::$environment === 'PRODUCTION' ? Config::$version : uniqid();

        $siteInstalled = \get_users([
            'orderby' => 'registered',
            'order' => 'ASC',
            'number' => 1,
            'fields' => ['user_registered'],
        ])[0]->user_registered;

        $version = Config::$environment === 'PRODUCTION' ? Config::$version : uniqid();
        $scriptAssetPath = EXTENDIFY_PATH . 'public/build/' . Config::$assetManifest["extendify-assist-{$context}.php"];
        $fallback = [
            'dependencies' => [],
            'version' => $version,
        ];
        $scriptAsset = file_exists($scriptAssetPath) ? require $scriptAssetPath : $fallback;

        if ($context === 'page') {
            wp_enqueue_media();
        }

        foreach ($scriptAsset['dependencies'] as $style) {
            wp_enqueue_style($style);
        }

        \wp_enqueue_script(
            Config::$slug . "-assist-{$context}-scripts",
            EXTENDIFY_BASE_URL . 'public/build/' . Config::$assetManifest["extendify-assist-{$context}.js"],
            $scriptAsset['dependencies'],
            $scriptAsset['version'],
            true
        );

        $assistState = get_option('extendify_assist_globals');
        $dismissed = isset($assistState['state']['dismissedNotices']) ? $assistState['state']['dismissedNotices'] : [];
        \wp_add_inline_script(
            Config::$slug . "-assist-{$context}-scripts",
            'window.extAssistData = ' . wp_json_encode([
                'devbuild' => \esc_attr(Config::$environment === 'DEVELOPMENT'),
                'siteId' => \get_option('extendify_site_id', ''),
                // Only send insights if they have opted in explicitly.
                'insightsEnabled' => defined('EXTENDIFY_INSIGHTS_URL'),
                'root' => \esc_url_raw(\rest_url(Config::$slug . '/' . Config::$apiVersion)),
                'nonce' => \wp_create_nonce('wp_rest'),
                'adminUrl' => \esc_url_raw(\admin_url()),
                'home' => \esc_url_raw(\get_home_url()),
                'siteCreatedAt' => $siteInstalled ? $siteInstalled : null,
                'asset_path' => \esc_url(EXTENDIFY_URL . 'public/assets'),
                'launchCompleted' => Config::$launchCompleted,
                'dismissedNotices' => $dismissed,
                'partnerLogo' => PartnerData::$logo,
                'partnerName' => PartnerData::$name,
                'disableRecommendations' => PartnerData::$disableRecommendations,
                'blockTheme' => wp_is_block_theme(),
                'themeSlug' => get_option('stylesheet'),
                'wpLanguage' => \get_locale(),
                'userData' => [
                    'taskData' => TasksController::get(),
                    'tourData' => TourController::get(),
                    'globalData' => GlobalsController::get(),
                    'userSelectionData' => UserSelectionController::get(),
                    'recommendationData' => RecommendationsController::get(),
                    'supportArticlesData' => SupportArticlesController::get(),
                    'routerData' => RouterController::get(),
                ],
                'resourceData' => (new ResourceData())->getData(),
            ]),
            'before'
        );

        \wp_set_script_translations(Config::$slug . "-assist-{$context}-scripts", 'extendify');

        \wp_enqueue_style(
            Config::$slug . '-assist-page-styles',
            EXTENDIFY_BASE_URL . 'public/build/' . Config::$assetManifest["extendify-assist-{$context}.css"],
            [],
            Config::$version,
            'all'
        );

        $cssColorVars = PartnerData::cssVariableMapping();
        $cssString = implode('; ', array_map(function ($k, $v) {
            return "$k: $v";
        }, array_keys($cssColorVars), $cssColorVars));
        wp_add_inline_style(Config::$slug . "-assist-{$context}-styles", "body { $cssString; }");
    }
}
