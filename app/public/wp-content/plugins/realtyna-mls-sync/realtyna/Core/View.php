<?php

namespace Realtyna\Sync\Core;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/** @var string default root folder for views */
define( "REALTYNA_VIEW_ROOT_FOLDER" , 'views' );

/** @var string template root */
define( "REALTYNA_TEMPLATE_ROOT" , dirname( REALTYNA_MLS_SYNC_PLUGIN_FILE ) . DIRECTORY_SEPARATOR . REALTYNA_VIEW_ROOT_FOLDER . DIRECTORY_SEPARATOR );

/**
 * Render Views files in PHP format
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class View
{

    /**
     * Render PHP Template Views
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string name of template file
     * @param array|null append data to template
     * 
     * @return void
     */
    static public function view( $template , $_REALTYNA = null )
    {

        self::require( $template , $_REALTYNA  );

    }

    /**
     * Get Template file Path
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Template
     * 
     * @return string Template file path
     */
    static public function getTemplateFile( $template )
    {
        
        $templatePath = str_replace('.' , DIRECTORY_SEPARATOR , $template ) . '.php' ;

        return REALTYNA_TEMPLATE_ROOT . $templatePath;

    }

    /**
     * Import Template file
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Template
     * @param array|null append Data to Template
     * 
     * @return void
     */
    static public function require( $template  , $_REALTYNA = null  )
    {

        if ( file_exists( self::getTemplateFile( $template ) ) )
            require_once( self::getTemplateFile( $template ) );        
        
    }

}

