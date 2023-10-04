<?php

namespace Realtyna\Sync\Core;

/**
 * Abstract theme structure
 * 
 * @abstract
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
abstract class Theme
{

    /** 
     * @var string Theme Name 
     * @static
     */
    static public $name;

    
    /** 
     * @var string Theme URL
     * @static
     */
    static public $url;

    /**
     * @var string Theme Requirements 
     * @static
     */
    static public $requirements;

    /**
     * Get Theme Name entered in class
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
     * Get Theme URL entered in class
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
     * Get Theme Name entered in class in lowercase
     * @author Chris A <chris.a@realtyna.net>
     *
     * @static
     * @return string|bool
     */
    static public function strtolowerName()
    {

        return static::getName() ? \strtolower( static::getName() ) : false ;

    }

    /**
     * Get Current Active Theme Name in Wordpress
     * @author Chris A <chris.a@realtyna.net>
     *
     * @static
     * @return string
     */
    static public function getCurrentTheme()
    {

        if ( function_exists( 'wp_get_theme' ) ){

            $wpTheme = wp_get_theme();
                        
            return $wpTheme->get( 'Name' ) ;

        }

        return '';

    }

    /**
     * Get lowercase of Current Active Theme Name in Wordpress
     * @author Chris A <chris.a@realtyna.net>
     *
     * @static
     * @return string|bool
     */
    static public function strtolowerCurrentProductName()
    {

        return static::getCurrentTheme() ? \strtolower( static::getCurrentTheme() ) : false ;

    }

    /**
     * Check if the theme is active
     * @author Chris A <chris.a@realtyna.net>
     *
     * @static
     * @return string
     */
    static public function isActive()
    {

        return \substr( static::strtolowerCurrentProductName() , 0 , \strlen( static::strtolowerName() ) ) == static::strtolowerName()  ;

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