<?php

namespace Realtyna\Sync\Core;

/**
 * Theme Factory
 * 
 * @author Chris A  <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class ThemeFactory
{
    
    /**
     * Create an instance of desired Theme Class
     *
     * @return bool|object
     */
    static public function create ()
    {

        if ( ThemeProviders::class ){

            foreach( ThemeProviders::getProviders() as $key => $theme ){

                if ( $theme['active']  ){

                    return new $theme['object'];

                }

            }

        }

        return false;

    }

    
}
