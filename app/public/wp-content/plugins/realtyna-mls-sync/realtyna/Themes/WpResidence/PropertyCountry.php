<?php

namespace Realtyna\Sync\Themes\WpResidence;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Hanlde New Taxonomy for WpResidence Country
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class PropertyCountry extends Taxonomy {

    /** @var string taxonomy value */
    const TAXONOMY = 'property_country';

}