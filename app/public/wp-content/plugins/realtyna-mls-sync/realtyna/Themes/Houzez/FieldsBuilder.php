<?php

namespace Realtyna\Sync\Themes\Houzez;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Handle Houzez Fields Builder
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class FieldsBuilder {

    /**
     * Add New Field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * @param string Field Label , Default Empty
     * @param bool Is Field Searchable ? default is False
     * @param string Field Type , Default is Text
     * @param array|null default is null
     * @param string Field PlaceHolder , default is empty
     * 
     * @return bool
     */
    public static function addField( $slug , $label = '' , $searchable = false , $type = 'text' , $fvalues = null , $placeholder = '' ){            
        
        $slug = trim( $slug );
        $label = trim( $label );

        if ( empty( $slug ) )
            return false;

        if ( self::existsField( $slug ) )
            return true;
        
        global $wpdb;

        $inserted = $wpdb->insert( $wpdb->prefix . 'houzez_fields_builder', array(
            'field_id' => $slug ,
            'label' => empty( $label ) ? $slug : $label ,
            'type' => $type ,
            'is_search' => $searchable ? 'yes' : 'no' ,
            'fvalues' => $fvalues ,
            'placeholder' => $placeholder
            )
        );

        return $inserted ? : false;

    }

    /**
     * Check Field Existance
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * 
     * @return bool
     */
    public static function existsField( $slug ) {
        
        global $wpdb;

        $totals = $wpdb->get_var( "SELECT count(1) FROM " . $wpdb->prefix . "houzez_fields_builder WHERE field_id = '{$slug}'" );

        return ( $totals > 0 );

    }
 
}