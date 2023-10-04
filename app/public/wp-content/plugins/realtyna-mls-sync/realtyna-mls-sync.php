<?php
/**
 * Plugin Name: Realtyna MLS Sync
 * Plugin URI: https://realtyna.com/
 * Description: Sync MLS listings with third-party themes
 * Author: Realtyna
 * Author URI: https://realtyna.com/
 * Version: 1.5.1
 * Text Domain: realtyna-mls-sync
 */

/** Block direct access to the main plugin file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/** @var string default plugin slug holder */
define( "REALTYNA_MLS_SYNC_SLUG" , "realtyna-mls-sync" );

/** @var string plugin Version */
define( "REALTYNA_MLS_SYNC_VERSION" , "1.5.1" );

/** @var string plugin root path */
define( "REALTYNA_MLS_SYNC_PLUGIN_FILE" , __FILE__ );

require_once dirname( REALTYNA_MLS_SYNC_PLUGIN_FILE ) . "/vendor/autoload.php";

if ( Realtyna\Sync\Core\App::class ) {
    \Realtyna\Sync\Core\App::getInstance();
}

