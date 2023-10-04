<?php

namespace Realtyna\Sync\Themes\Houzez;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Houzez Theme Prerequirement Checker
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Core extends \Realtyna\Sync\Core\Theme 
{

    /** 
     * @var string Theme Name 
     * @static
     * @abstract
     */
    static public $name = 'Houzez';
    
    /** 
     * @var string Theme URL
     * @static
     */
    static public $url = 'https://houzez.co/';

    /**
     * Get new object of Agency
     * 
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return Agency
     */
    public function agencies()
    {

        if ( Agency::class ){

            return new Agency();

        }

    }

    /**
     * Get new object of Agent
     * 
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return Agent
     */
    public function agents()
    {

        if ( Agent::class ){

            return new Agent();

        }

    }

    /**
     * Get new object of FieldsBuilder
     * 
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return FieldsBuilder
     */
    public function fieldBuilder()
    {

        if ( FieldsBuilder::class ){

            return new FieldsBuilder();

        }

    }

    /**
     * Get Property Object
     * 
     * @author Chris A <chris.a@realtyna.net>
     *
     * @param bool initialize fields on create class
     * @param string|null dmls Provider , default value is null
     * @param array import options array , default is null
     * 
     * @return Property
     */
    public function property( $initFields = false , $mlsProvider = null , $importOptions = null )
    {

        if ( Property::class ){

            return new Property( $initFields , $mlsProvider , $importOptions );

        }

    }

    /**
     * Purge Listings cron job
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function purgeListings()
    {
		
        if ( Property::class ){

            $houzezProperty = new Property();
			 
			if ( \method_exists(  $houzezProperty , 'purgeListings' ) ){
				
				$houzezProperty->purgeListings();
			 
			}

        }

    }

    /**
     * Purge Attachments cron job
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function purgeAttachments()
    {
		
        if ( Property::class ){

            $houzezProperty = new Property();
			 
			if ( \method_exists(  $houzezProperty , 'purgeAttachments' ) ){
				
				$houzezProperty->purgeAttachments();
			 
			}

        }

    }

    /**
     * Remove Imported Properties
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param bool Remove Only Demo Properties
     * 
     * @return bool
     */
    public function removeProperties( $demoOnly = false )
    {

        if ( Property::class ){

             $houzezProperty = new Property();

             return $houzezProperty->bulkRemoveProperties( $demoOnly );

        }

        return false;

    }

    /**
     * Update Agent display option for Imported Properties in Houzez Theme
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int fave_agent_display_option code defined by Houzez
     * 
     * @return bool
     */
    public function updatePropertiesAgentDisplayOption( $agentOption )
    {

        return $this->updatePropertiesMeta( "fave_agent_display_option" , $agentOption );

    }

    /**
     * Update Agency for Imported Properties in Houzez Theme
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int ID for Selected Agency
     * 
     * @return bool
     */
    public function updatePropertiesAgency( $agency )
    {

        return $this->updatePropertiesMeta( "fave_property_agency" , $agency );

    }

    /**
     * Update Agent for Imported Properties in Houzez Theme
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int ID for Selected Agent
     * 
     * @return bool
     */
    public function updatePropertiesAgents( $agent )
    {

        return $this->updatePropertiesMeta( "fave_agents" , $agent );

    }

    /**
     * Update Post Metas for Imported Properties in Houzez Theme
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Post Meta Key
     * @param string Post Meta Value
     * 
     * @return bool
     */
    private function updatePropertiesMeta( $key , $value )
    {

        if ( Property::class ){

             $property = new Property( true );

             return $property->bulkUpdatePostMeta( $key , $value );

        }
        
        return false;

    }


}