<?php

namespace Realtyna\Sync\Addons\Updater;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Realtyna Updater Class For Wordpress Plugins
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class UpdaterWpPlugin extends Updater
{

    /** @var string path ro wordpress core plugin classes */
    const WP_PLUGIN_CORE_FILE = ABSPATH . 'wp-admin/includes/plugin.php';

    /** @var string path to wordpress file class  */
    const WP_FILE_CORE = ABSPATH . 'wp-admin/includes/file.php';    

    /**
     * Get All Wordpress Plugins details
     * 
     * @see https://developer.wordpress.org/reference/functions/get_plugins/
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array array of Plugin Details
     */
    private function getWpPlugins()
    {
        
        if ( file_exists( self::WP_PLUGIN_CORE_FILE ) ){

            require_once self::WP_PLUGIN_CORE_FILE;

        }
        
        if ( function_exists( 'get_plugins' ) ) {

            return get_plugins();

        }
        
        return [];
        
    }

    /**
     * Find Main file of plugin
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string Main file of Plugin
     */
    private function getPluginFile()
    {
        
        $pluginFile = '';

        $plugins = $this->getWpPlugins();

        if ( !empty( $plugins ) && is_array( $plugins ) ){

            foreach ($plugins as $pluginKey => $pluginValue) {
                
                $arrayPluginInfo = explode( "/" , $pluginKey );
                
                if ( count( $arrayPluginInfo ) == 2 && $arrayPluginInfo[0] == $this->slug ){
                    
                    $pluginFile = $pluginKey;

                    break;

                }

            }

        }

        return $pluginFile;

    }

    /**
     * Extract Version of Plugin
     * 
     * @see https://developer.wordpress.org/reference/functions/get_plugin_data/
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string 
     */
    public function getPluginVersion()
    {
        
        $pluginVersion = '';

        $pluginFile = $this->getPluginFile( $this->slug );

        if ( !empty( $pluginFile ) ){

            $pluginFile = ABSPATH . "wp-content" . DIRECTORY_SEPARATOR . "plugins" . DIRECTORY_SEPARATOR . $pluginFile;

            if ( file_exists( self::WP_PLUGIN_CORE_FILE ) ){

                require_once self::WP_PLUGIN_CORE_FILE;
    
            }
    
            if ( function_exists('get_plugin_data') ){

                $arrayPluginInfo = get_plugin_data( $pluginFile );

                if ( isset( $arrayPluginInfo['Version'] ) && !empty( $arrayPluginInfo['Version'] ) ){

                    $pluginVersion = $arrayPluginInfo['Version'];

                }

            }
        
        }

        return $pluginVersion;

    }

    /**
     * Check for availability of Update
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    public function isUpdateAvailable()
    {
        return ( !empty( $this->getLastVersion() ) && ( $this->getLastVersion() <> $this->getPluginVersion() ) );
    }

    /**
     * Update Plugin with Latest version using Built-in Wordpress Updater class
     * 
     * @see https://developer.wordpress.org/reference/classes/plugin_upgrader/
     * 
     * @author Chirs A <chirs.a@realtyna.net>
     * 
     * @return bool
     */
    public function updatePlugin( $shouldBeActive = true )
    {        
        $updateResult = false;

        if ( $this->isUpdateAvailable() && !empty( $this->getDownloadLink() ) && function_exists('download_url') && function_exists('unzip_file') ){
            
            if ( file_exists( self::WP_FILE_CORE ) ){
                
                require_once self::WP_FILE_CORE;

            }

            if ( WP_Filesystem() ) {

                $upgraderDownload = download_url( $this->getDownloadLink() , 300, false );

                if ( ! is_wp_error( $upgraderDownload ) ){
    
                    $unpackResult = unzip_file( $upgraderDownload, WP_PLUGIN_DIR );
    
                    if ( ! is_wp_error( $unpackResult ) ){
    
                        $updateResult = true;
    
                    }
                    
                }
    
                @unlink( $upgraderDownload );

            }
        
        }

        return $updateResult;

    }


}
