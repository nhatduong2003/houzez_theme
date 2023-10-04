<?php
/**
 * Handles translations
 */

namespace Extendify;

/**
 * Handles translations
 */
class Translations
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->installLanguagePack(Config::$version, \get_locale());
        \load_plugin_textdomain('extendify');
    }

    /**
     * Install language pack
     *
     * @param string $version The plugin version.
     * @param string $locale  The locale.
     *
     * @return void
     */
    public function installLanguagePack($version, $locale)
    {
        $key = $locale . '-' . $version;

        // Check only once per language and version.
        $langsChecked = (array) get_option('extendify_language_file_preloaded', []);
        if (in_array($key, $langsChecked, true)) {
            return;
        }

        // If the below code fails, retry every 1 hour (transient will expire).
        $lastChecked = get_transient('extendify_language_files_last_checked', false);
        if ($lastChecked) {
            return;
        }

        set_transient('extendify_language_files_last_checked', true, HOUR_IN_SECONDS);

        include_once ABSPATH . 'wp-admin/includes/translation-install.php';
        if (!wp_can_install_language_pack()) {
            return;
        }

        $translations = translations_api('plugins', [
            'slug' => 'extendify',
            'version' => $version,
        ]);

        if (!isset($translations['translations'])) {
            return;
        }

        $data = array_values(array_filter($translations['translations'], function ($translation) use ($locale) {
            return $translation['language'] === $locale;
        }));

        // If no language is found, we should stop checking for this language.
        if (!isset($data[0])) {
            update_option('extendify_language_file_preloaded', array_merge($langsChecked, [$key]));
            return;
        }

        // Since we use hashed file names, we need to wait for an exact match.
        // There's sometimes a delay on w.org's side, so it could take an hour or two.
        if ($data[0]['version'] !== $version) {
            return;
        }

        $skin = new NoopUpgraderSkin();
        $upgrader = new \WP_Upgrader($skin);
        $upgrader->generic_strings();
        $result = $upgrader->run([
            'package' => $data[0]['package'],
            'destination' => WP_LANG_DIR . '/plugins',
            'abort_if_destination_exists' => false,
        ]);

        // Success. Add to checked list.
        update_option('extendify_language_file_preloaded', array_merge($langsChecked, [$key]));
    }
}

/**
 * Overrides the WP output class to prevent any and all output.
 */
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-includes/pluggable.php';
// phpcs:disable
class NoopUpgraderSkin extends \WP_Upgrader_Skin
{
    public function feedback($data, ...$args) {}
    public function header() {}
    public function footer() {}
}
// phpcs:enable
