<?php

namespace Realtyna\Sync\Core;

/**
 * Manage Theme Providers
 * 
 * @final
 * @author Chris A  <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
final class ThemeProviders
{

    /** 
     * Any new Theme Provider should be added here in this array
     * 
     * @static
     * @var array $providers contain theme providers classes 
     */
    static public $providers = [
        \Realtyna\Sync\Themes\Houzez\Core::class ,
        \Realtyna\Sync\Themes\WpResidence\Core::class ,
    ];

    /**
     * Get theme providers
     * 
     * @static
     * @author Chirs A <chris.a@realtyna.net>
     * 
     * @return array
     */
    static public function getProviders()
    {

        $providersArray = [];

        foreach( static::$providers as $theme ){

            if ( $theme ){

                $providersArray[] = [ "object" => $theme , 
                                 "name" => $theme::strtolowerName() , 
                                 "url" => $theme::getURL(),
                                 "active" => $theme::isActive() ];

            }

        }        
        
        return $providersArray;

    }
    
}
