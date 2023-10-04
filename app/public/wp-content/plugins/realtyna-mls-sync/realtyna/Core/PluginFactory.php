<?php

namespace Realtyna\Sync\Core;

/**
 * Plugin Factory
 * 
 * @author Chris A  <chris.a@realtyna.net>
 * 
 * @since 1.1.0
 */
class PluginFactory
{
    
    /**
     * Create an instance of desired Plugin Class
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $desiredPlugin
     * @return bool|object
     */
    static public function create ( $desiredPlugin = '' )
    {

        if ( PluginProviders::class ){

            foreach( PluginProviders::getProviders() as $key => $plugin ){

                if ( strtolower( $desiredPlugin ) == $plugin['name'] && $plugin['active']  ){

                    return new $plugin['object'];

                }

            }

        }

        return false;

    }

    
}
