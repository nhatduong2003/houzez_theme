<?php
/**
 * Admin.
 */

namespace Extendify\Library;

use Extendify\PartnerData;
use Extendify\Config;
use Extendify\User;

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
        $this->loadScripts();

        // Check if legacy classes are present.
        if (!get_option('extendify_has_legacy_classes', false)) {
            // TODO: Remove the ! in the following if statement once patterns and templates no longer use utility classes.
            if (!version_compare(get_option('extendify_first_installed_version', '0.0.0'), '1.7.0', '<')) {
                if (!$this->patternWasImported()) {
                    return;
                }

                $siteSettings = json_decode(get_option('extendifysdk_sitesettings', '{ "state": {} }'));
                if (!isset($siteSettings->state->activateLegacyClasses) || $siteSettings->state->activateLegacyClasses === false) {
                    $siteSettings->state->activateLegacyClasses = true;
                    update_option('extendifysdk_sitesettings', wp_json_encode($siteSettings));
                }

                update_option('extendify_has_legacy_classes', true);
            }
        }//end if
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
            function ($hook) {
                if (!current_user_can(Config::$requiredCapability)) {
                    return;
                }

                $this->maybeAddDeactivationScript();

                if (!$this->checkItsGutenbergPost($hook)) {
                    return;
                }

                if (!$this->isLibraryEnabled()) {
                    return;
                }

                $this->addScopedScriptsAndStyles();
            }
        );
    }

    /**
     * Checks if an Extendify pattern exists in any post type
     *
     * @return boolean
     */
    public function patternWasImported()
    {
        // check if Launch has been completed to avoid database search.
        if (Config::$launchCompleted) {
            return true;
        }

        // This is set when a user imports a pattern from the library.
        $wasImported = get_option('extendify_pattern_was_imported', null);
        if ($wasImported !== null) {
            return $wasImported;
        }

        // We only check this once (for bw compatibility).
        $wpdb = $GLOBALS['wpdb'];
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        $patternExists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $wpdb->posts WHERE post_content LIKE %s  AND post_type != 'revision'",
                '%extUtilities%'
            )
        ) !== '0';

        update_option('extendify_pattern_was_imported', $patternExists);
        return $patternExists;
    }

    /**
     * Makes sure we are on the correct page
     *
     * @param string $hook - An optional hook provided by WP to identify the page.
     * @return boolean
     */
    public function checkItsGutenbergPost($hook = '')
    {
        // Check for the post type, or on the FSE page.
        $type = isset($GLOBALS['typenow']) ? $GLOBALS['typenow'] : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!$type && isset($_GET['postType'])) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $type = sanitize_text_field(wp_unslash($_GET['postType']));
        }

        if (\use_block_editor_for_post_type($type)) {
            return $hook && in_array($hook, ['post.php', 'post-new.php'], true);
        }

        // Temporarily disable the library on the site editor page until the issues with 6.3 are fixed.
        return false;
    }

    /**
     * Adds various JS scripts
     *
     * @return void
     */
    public function addScopedScriptsAndStyles()
    {
        $user = json_decode(User::data('extendifysdk_user_data'), true);
        $openOnNewPage = isset($user['state']['openOnNewPage']) ? $user['state']['openOnNewPage'] : Config::$launchCompleted;
        $version = Config::$environment === 'PRODUCTION' ? Config::$version : uniqid();
        $scriptAssetPath = EXTENDIFY_PATH . 'public/build/' . Config::$assetManifest['extendify.php'];
        $fallback = [
            'dependencies' => [],
            'version' => $version,
        ];
        $scriptAsset = file_exists($scriptAssetPath) ? require $scriptAssetPath : $fallback;
        foreach ($scriptAsset['dependencies'] as $style) {
            wp_enqueue_style($style);
        }

        \wp_register_script(
            Config::$slug . '-scripts',
            EXTENDIFY_BASE_URL . 'public/build/' . Config::$assetManifest['extendify.js'],
            $scriptAsset['dependencies'],
            $scriptAsset['version'],
            true
        );

        \wp_localize_script(
            Config::$slug . '-scripts',
            'extendifyData',
            array_merge([
                'root' => \esc_url_raw(rest_url(Config::$slug . '/' . Config::$apiVersion)),
                'nonce' => \wp_create_nonce('wp_rest'),
                'partnerLogo' => PartnerData::$logo,
                'partnerName' => PartnerData::$name,
                'user' => $user,
                'openOnNewPage' => $openOnNewPage,
                'sitesettings' => json_decode(SiteSettings::data()),
                'sdk_partner' => \esc_attr(PartnerData::$id),
                'asset_path' => \esc_url(EXTENDIFY_URL . 'public/assets'),
                'devbuild' => \esc_attr(Config::$environment === 'DEVELOPMENT'),
                'siteId' => \get_option('extendify_site_id', ''),
                'hasLegacyClasses' => (bool) \get_option('extendify_has_legacy_classes', false),
            ])
        );

        \wp_enqueue_script(Config::$slug . '-scripts');

        \wp_set_script_translations(Config::$slug . '-scripts', 'extendify');

        // Inline the library styles to keep them out of the iframe live preview.
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $css = file_get_contents(EXTENDIFY_PATH . 'public/build/' . Config::$assetManifest['extendify.css']);
        \wp_register_style(Config::$slug, false, [], Config::$version);
        \wp_enqueue_style(Config::$slug);
        \wp_add_inline_style(Config::$slug, $css);

        $cssColorVars = PartnerData::cssVariableMapping();
        $cssString = implode('; ', array_map(function ($k, $v) {
            return "$k: $v";
        }, array_keys($cssColorVars), $cssColorVars));
        wp_add_inline_style(Config::$slug, "body { $cssString; }");
    }

    /**
     * Adds deactivation prompt JS script
     *
     * @return void
     */
    public function maybeAddDeactivationScript()
    {
        $screen = get_current_screen();
        if (!isset($screen->id) || $screen->id !== 'plugins') {
            return;
        }

        if (version_compare(get_bloginfo('version'), '6.2', '<')) {
            return;
        }

        if (!$this->patternWasImported()) {
            return;
        }

        // phpcs:disable Squiz.PHP.CommentedOutCode
        // TODO: Uncomment this when the CSS utility setting is implemented.

        /*
        $hasLegacyClasses = json_decode(\get_option('extendifysdk_sitesettings', null));
        if (!isset($hasLegacyClasses->state->activateLegacyClasses) || $hasLegacyClasses->state->activateLegacyClasses === false) {
        return;
        }
        */
        // phpcs:enable

        $version = Config::$environment === 'PRODUCTION' ? Config::$version : uniqid();
        $scriptAssetPath = EXTENDIFY_PATH . 'public/build/' . Config::$assetManifest['extendify-deactivate.php'];
        $fallback = [
            'dependencies' => [],
            'version' => $version,
        ];
        $scriptAsset = file_exists($scriptAssetPath) ? require $scriptAssetPath : $fallback;
        foreach ($scriptAsset['dependencies'] as $style) {
            wp_enqueue_style($style);
        }

        \wp_register_script(
            Config::$slug . '-deactivate-scripts',
            EXTENDIFY_BASE_URL . 'public/build/' . Config::$assetManifest['extendify-deactivate.js'],
            $scriptAsset['dependencies'],
            $scriptAsset['version'],
            true
        );

        \wp_localize_script(
            Config::$slug . '-deactivate-scripts',
            'extendifyData',
            array_merge([
                'root' => \esc_url_raw(rest_url(Config::$slug . '/' . Config::$apiVersion)),
                'nonce' => \wp_create_nonce('wp_rest'),
                'partnerLogo' => PartnerData::$logo,
                'partnerName' => PartnerData::$name,
                'adminUrl' => \esc_url_raw(\admin_url()),
            ])
        );

        \wp_enqueue_script(Config::$slug . '-deactivate-scripts');

        \wp_set_script_translations(Config::$slug . '-deactivate-scripts', 'extendify');

        \wp_enqueue_style(Config::$slug, EXTENDIFY_BASE_URL . '/public/build/' . Config::$assetManifest['extendify.css'], [], Config::$version);
    }

    /**
     * Check if current user is Admin
     *
     * @return Boolean
     */
    private function isAdmin()
    {
        if (\is_multisite()) {
            return \is_super_admin();
        }

        return in_array('administrator', \wp_get_current_user()->roles, true);
    }

    /**
     * Check if scripts should add based on user setting.
     *
     * @return Boolean
     */
    public function isLibraryEnabled()
    {
        // TODO: For now just always show.
        return true;
    }
}
