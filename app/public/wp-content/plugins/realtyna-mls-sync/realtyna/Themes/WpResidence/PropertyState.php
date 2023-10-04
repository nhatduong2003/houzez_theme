<?php

namespace Realtyna\Sync\Themes\WpResidence;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Hanlde New Taxonomy for WpResidence State
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class PropertyState extends Taxonomy {

    /** @var string taxonomy value */    
    const TAXONOMY = 'property_county_state';

}