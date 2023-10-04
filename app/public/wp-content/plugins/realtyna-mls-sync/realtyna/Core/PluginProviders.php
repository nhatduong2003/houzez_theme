<?php

namespace Realtyna\Sync\Core;

/**
 * Manage Plugin Providers
 * 
 * @final
 * @author Chris A  <chris.a@realtyna.net>
 * 
 * @since 1.1.0
 */
final class PluginProviders
{

    /** 
     * Any new Plugin Provider should be added here in this array
     * 
     * @static
     * @var array $providers contain plugin providers classes 
     */
    static public $providers = [];

    /**
     * Get plugin providers
     * 
     * @static
     * @author Chirs A <chris.a@realtyna.net>
     * 
     * @return array
     */
    static public function getProviders()
    {

        $providersArray = [];

        foreach( static::$providers as $plugin ){

            if ( $plugin ){

                $providersArray[] = [ "object" => $plugin , 
                                 "name" => $plugin::strtolowerName() , 
                                 "url" => $plugin::getURL(),
                                 "active" => $plugin::isActive() ];

            }

        }        
        
        return $providersArray;

    }
    
}
