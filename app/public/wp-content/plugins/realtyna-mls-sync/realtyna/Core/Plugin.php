<?php

namespace Realtyna\Sync\Core;

/**
 * Abstract plugin structure
 * 
 * @abstract
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @since 1.1.0
 */
abstract class Plugin
{

    /** 
     * @var string Plugin Name 
     * @static
     */
    static public $name;

    /** 
     * This property should point to  folder and filename of the plugin
     * Example: "realtyna-mls-sync/realtyna-mls-sync.php"
     * @var string Plugin Path
     * @static
     */
    static public $path;
    
    /** 
     * @var string Plugin URL
     * @static
     */
    static public $url;

    /**
     * @var string Plugin Requirements 
     * @static
     */
    static public $requirements;

    /**
     * Get Plugin Name entered in the class
     * @author Chris A <chris.a@realtyna.net>
     *
     * @static
     * @return string
     */
    static public function getName()
    {
        
        return static::$name;

    }

    /**
     * Get Plugin Path entered in the class
     * @author Chris A <chris.a@realtyna.net>
     *
     * @static
     * @return string
     */
    static public function getPath()
    {
        
        return static::$path;

    }

    /**
     * Get Plugin URL entered in the class
     * @author Chris A <chris.a@realtyna.net>
     *
     * @static
     * @return string
     */
    static public function getURL()
    {
        
        return static::$url;

    }

    /**
     * Get Lowercase of Plugin Name entered in the class
     * @author Chris A <chris.a@realtyna.net>
     *
     * @static
     * @return bool|string
     */
    static public function strtolowerName()
    {

        return static::getName() ? \strtolower( static::getName() ) : false ;

    }

    /**
     * Check if the plugin is active or no
     * @author Chris A <chris.a@realtyna.net>
     *
     * @static
     * @return boolean
     */
    static public function isActive()
    {

        $result = false;

        if ( function_exists('get_option') && !empty( static::$path ) ){

            $plugins = get_option('active_plugins');

            $result = \in_array( static::$path , $plugins );

        }

        return $result;

    }

    /**
     * Get new object of Agency
     * @author Chris A <chris.a@realtyna.net>
     * @abstract
     *
     * @return object
     */
    abstract public function agencies();

    /**
     * Get Agent Object
     * @author Chris A <chris.a@realtyna.net>
     * @abstract
     *
     * @return object
     */
    abstract public function agents();

    /**
     * Return Property Object
     * @author Chris A <chris.a@realtyna.net>
     * @abstract
     *
     * @param bool initialize fields on create class
     * @param string|null mls Provider
     * @param array import options array
     * 
     * @return object
     */
    abstract public function property( $initFields , $mlsProvider , $importOptions );

    /**
     * Bulk Remove Imported Properties
     * @author Chris A <chris.a@realtyna.net>
     * @abstract
     * 
     * @param bool Remove Only Demo Properties
     * 
     * @return bool
     */
    abstract public function removeProperties( $demoOnly );

    /**
     * Update Agent display option for Imported Properties
     * it may return false/true in case of unused
     * @author Chris A <chris.a@realtyna.net>
     * @abstract
     * 
     * @param int code defined by Product
     * 
     * @return bool
     */
    abstract public function updatePropertiesAgentDisplayOption( $agentOption );

    /**
     * Update Agency for Imported Properties
     * @author Chris A <chris.a@realtyna.net>
     * @abstract
     * 
     * @param int ID for Selected Agency
     * 
     * @return bool
     */
    abstract public function updatePropertiesAgency( $agency );
    
    /**
     * Update Agent for Imported Properties
     * @author Chris A <chris.a@realtyna.net>
     * @abstract
     * 
     * @param int ID for Selected Agent
     * 
     * @return bool
     */
    abstract public function updatePropertiesAgents( $agent );

}