<?php

namespace Realtyna\Sync\Themes\Houzez;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Handle Realtyna Property Post Type
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Property {

    /** @var string custom post type for Houzez Properties */
    const PRODUCT_POST_TYPE = 'property';

    /** @var string houzez field prefix */
    const THEME_FIELD_PREFIX = 'fave_';

    /** @var string idx meta mark */
    const REALTYNA_IDX_META_MARK = '_realtyna_idx_item';

    /** @var string property field  */
    const IDX_IDENTITY_FIELD = '_realtyna_mls_key';

    /** @var property status field */
    const IDX_STATUS_FIELD = 'fave_mlsstatus';

    /** @var int number of records to be removed per request */
    const REALTYNA_REMOVE_RECORDS_PER_REQUEST = 500;

    /** @var array array of allowed status for import */
    protected $allowedPropertyStatus = [ "Active" , "A" ];

    /** @var array array of custom fields */
    private $customFields = [];

    /** @var string MLs Provider */
    private $mlsProvider = '';

    /** @var int imported properties counter */
    private $importedProperty = 0 ;

    /** @var array import options */
    private $importOptions = [
        "generate_thumbs_images" => false,
        "max_images_import" => 50 ,
        "max_property_import" => -1,
        "use_external_images" => true,
        "use_external_thumbnail" => true
    ];

    /**
     * Class Constructor Method
     * 
     * @param bool initialize fields on create class
     * @param string|null dmls Provider , default value is null
     * @param array import options array , default is null
     * 
     * @return void
     */
    public function __construct( $initFields = false , $mlsProvider = null , $importOptions = null ){

        set_time_limit( 0 );
        
        if ( !empty( $mlsProvider ) )
            $this->mlsProvider = $mlsProvider;
        
        if ( !empty( $importOptions ) && is_array( $importOptions ) )
            $this->importOptions = $importOptions;

        if ( $initFields )
            $this->initFields();
        
        $this->checkIdentityPostMeta();

    }

    /**
     * Initialize Fields for properties in houzez theme
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function initFields(){

        //Main WP Fields
        $this->addField( 'post_title', 'Property Title', 'string', null ,'' ,false , '' , true);

        $this->addField( 'post_name', 'Property Slug', 'string', null ,'' , false , '' , true);

        $this->addField( 'post_content', 'Property Desciptions', 'string', null , '' , true , '' , true);

        $this->addField( 'post_excerpt', 'Property Excerpt', 'string', null , ''  , false , '' , true);
        
        $this->addField( 'post_status', 'Property Status', 'string', array(
            'publish' => "Published" ,
            'pending' => "Pending Review" ,
            'draft' => "Draft"
        ) , '' , true , 'publish' , true );

        //MLS_KEY
        $this->addField( self::IDX_IDENTITY_FIELD , 'MLS Key', 'string', null, '' );

        //Main houzez fields
        $this->addField( self::THEME_FIELD_PREFIX . 'currency', 'Select Currency', 'string', null, 'example: USD' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_price', 'Sale or Rent Price', 'string', null, 'Only digits, example: 557000' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_sec_price', 'Second Price ( Display optional price for rental or square feet )', 'string', null, 'Only digits, example: 700' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_price_prefix', 'Before Price label', 'string', null, 'Example: Start From' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_price_postfix', 'After Price label', 'string', null, 'Example: Per Month' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_size', 'Area Size', 'string', null, 'Only digits, example: 2500' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_size_prefix', 'Area Size Postfix', 'string', null, 'Example: Sq Ft' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_land', 'Land Area', 'string', null, 'Only digits, example: 2500' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_land_postfix', 'Land Area Postfix', 'string', null, 'Example: Sq Ft' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_bedrooms', 'Bedrooms', 'string', null, 'Example: 4' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_bathrooms', 'Bathrooms', 'string', null, 'Example: 2' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_rooms', 'Rooms', 'string', null, 'Example: 2' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_garage', 'Garages', 'string', null, 'Example: 1' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'private_note', 'Private Note', 'string', null, 'Example: 1' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_garage_size', 'Garages Size', 'string', null, 'Example: 100 sq ft' );

        $this->addField( self::THEME_FIELD_PREFIX . 'virtual_tour', '360° Virtual Tour', 'string', null, 'Enter virtual tour embeded code or iframe' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_agency', 'Agency', 'postType' , array( 
            'callBackClass' => '\Realtyna\Sync\Themes\Houzez\Agency',
            'callBackMethod' => 'insert'
        ), 'Enter agency id. Example: 333');
        
        $this->addField( self::THEME_FIELD_PREFIX . 'agents', 'Agent', 'postType' , array( 
            'callBackClass' => '\Realtyna\Sync\Themes\Houzez\Agent',
            'callBackMethod' => 'insert'
        ), 'Enter agent id. Example: 333');
        
        $this->addField( self::THEME_FIELD_PREFIX . 'loggedintoview', 'The user must be logged in to view this property?', 'list', array(
            '1' => 'Yes',
            '0' => 'No'
        ) , '' , false , '0' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_map', 'Show Map', 'list', array(
            '1' => 'Yes',
            '0' => 'No'
        ) , '' , false , '1' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_id', 'Property ID', 'string', null, 'To help search directly for a property. Example: HZ01' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_year', 'Year Built', 'string', null, '' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_address', 'Address(*only street name and building no)', 'string', null, '' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_zip', 'Zip/Postcode', 'string', null, '' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_country', 'Country', 'string', null, 'Provide country short name. Example US for United States, CA for Canada etc' );
    
        $this->addField( self::THEME_FIELD_PREFIX . 'featured', 'Featured Property?', 'list', array(
            '0' => 'No',
            '1' => 'Yes',
        ) , '' , false , '0');
    
        $displayAgentsOptions = array( 'none' => "None" );
        if ( Agent::class ){

            $displayAgentsOptions = Agent::getDisplayOptions();

        }
            
        $this->addField( self::THEME_FIELD_PREFIX . 'agent_display_option', 'What to display in agent information box?', 'list', $displayAgentsOptions , '' , false , 'none' ) ;
        
        $this->addField( self::THEME_FIELD_PREFIX . 'prop_homeslider', 'Add this property to Homepage Slider?', 'list', array(
            'no'  => 'No',
            'yes' => 'Yes',
        ) , '' , false , 'no' );
    
        $this->addField( self::THEME_FIELD_PREFIX . 'prop_slider_image', 'Slider Image', 'image', null, 'Recommended image size is 2000px by 700px. May use bigger or smaller image but keep the same height to width ratio and use the exact same size for all images in slider.' );
    
        $this->addField( self::THEME_FIELD_PREFIX . 'video_url', 'Virtual Tour Video URL', 'string', null, 'Provide virtual tour video URL. YouTube, Vimeo, SWF File and MOV File are supported.' );

        $this->addField( self::THEME_FIELD_PREFIX . 'video_image', 'Virtual Video Tour Image', 'image', null, 'Will be displayed as a place holder. Required for the video to be displayed. Minimum width of 818px and minimum height 417px. Larger sizes will be cropped.' );
    
        $this->addField( self::THEME_FIELD_PREFIX . 'property_images', 'Images Gallery', 'attachmentList', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'attachments', 'Property Attachments', 'attachmentList', null, "separate each value with a '|'" );

        $this->addField( 'houzez_geolocation_lat', 'Property Latitude', 'string', null, '' );

        $this->addField( 'houzez_geolocation_long', 'Property Longitude', 'string', null, '' );

        $this->addField( '_thumbnail_id', 'Featured Image', 'image', null, 'image that will be placed as property featured image' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_location' , 'Property Geo Location' , 'commaFieldset' , array(
            'houzez_geolocation_lat' ,
            'houzez_geolocation_long'
         ) , 'Content of this field will be driven from another fields');

        $this->addField( self::THEME_FIELD_PREFIX . 'property_map_address', 'Property Map Address', 'string', null, 'Formated Map Address' );

        //Taxonomies

        $this->addField( 'property_type', 'Property Types', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\Houzez\PropertyType',
            'callBackMethod' => 'import'
        ) , "separate each value with a '|'" );

        $this->addField( 'property_status', 'Property Status', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\Houzez\PropertyStatus',
            'callBackMethod' => 'import'
        ) , "separate each value with a '|'" );

        $this->addField( 'property_feature', 'Property Features', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\Houzez\PropertyFeature',
            'callBackMethod' => 'import'
        ) , "separate each value with a '|'" );

        $this->addField( 'property_label', 'Property Labels', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\Houzez\PropertyLabel',
            'callBackMethod' => 'import'
        ) , "separate each value with a '|'" );

        $this->addField( 'property_country', 'Property Country', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\Houzez\PropertyCountry',
            'callBackMethod' => 'import'
        ) , "separate each value with a '|'" );

        $this->addField( 'property_state', 'Property State', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\Houzez\PropertyState',
            'callBackMethod' => 'import',
            'metaKey' => '_houzez_property_state',
            'parentKey' => 'parent_country',
            'parentValue' => 'property_country'
        ) , "separate each value with a '|'" );

        $this->addField( 'property_city', 'Property City', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\Houzez\PropertyCity',
            'callBackMethod' => 'import',
            'metaKey' => '_houzez_property_city',
            'parentKey' => 'parent_state',
            'parentValue' => 'property_state'
        ) , "separate each value with a '|'" );

        $this->addField( 'property_area', 'Property Area', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\Houzez\PropertyArea',
            'callBackMethod' => 'import',
            'metaKey' => '_houzez_property_area',
            'parentKey' => 'parent_city',
            'parentValue' => 'property_city'
        ) , "separate each value with a '|'" );

        // Floor Plans
        $this->addField( self::THEME_FIELD_PREFIX . 'floor_plans_enable', 'Show floor Plans', 'list', array(
            'disable' => 'Disable',
            'enable' => 'Enable'
        ) , '' , false , 'disable' );

        $this->addField( 'floor_plans' , 'Floor plans items' , 'fieldset' , array(
            self::THEME_FIELD_PREFIX . 'plan_title' ,
            self::THEME_FIELD_PREFIX . 'plan_size',
            self::THEME_FIELD_PREFIX . 'plan_rooms',
            self::THEME_FIELD_PREFIX . 'plan_bathrooms',
            self::THEME_FIELD_PREFIX . 'plan_price',
            self::THEME_FIELD_PREFIX . 'plan_description',
            self::THEME_FIELD_PREFIX . 'plan_image'
         ) , 'Content of this field will be driven from another fields');

        $this->addField( self::THEME_FIELD_PREFIX . 'plan_title', 'Floor Plan Titles', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'plan_size', 'Floor Plan Sizes', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'plan_rooms', 'Floor Plan Bedrooms', 'stringSubField', null, "Numeric - separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'plan_bathrooms', 'Floor Plan Bathrooms', 'stringSubField', null, "Numeric - separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'plan_price', 'Floor Plan Prices', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'plan_description', 'Floor Plan Descriptions', 'stringSubField', null, "separate each value with a '|'" );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'plan_image', 'Floor Plan Image', 'stringSubField', null, "separate each value with a '|'" );

        // Additional Features
        $this->addField( self::THEME_FIELD_PREFIX . 'additional_features_enable', 'Show additional details', 'list', array(
            'disable' => 'Disable',
            'enable' => 'Enable'
        ) , '' , false , 'disable' );

        $this->addField( 'additional_features' , 'additional details items' , 'fieldset' , array(
            self::THEME_FIELD_PREFIX . 'additional_feature_title',
            self::THEME_FIELD_PREFIX . 'additional_feature_value'
         ) , 'Content of this field will be driven from another fields');

        $this->addField( self::THEME_FIELD_PREFIX . 'additional_feature_title', 'Titles', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'additional_feature_value', 'Values', 'stringSubField', null, "separate each value with a '|'" );

        // Multi Units / Sub Listings
        $this->addField( self::THEME_FIELD_PREFIX . 'multiunit_plans_enable', 'Enable/Disable Multi Units / Sub Properties', 'list', array(
            'disable' => 'Disable',
            'enable' => 'Enable'
        ) , '' , false , 'disable' );

        $this->addField( 'fave_multi_units' , 'Multi Units items' , 'fieldset' , array(
            self::THEME_FIELD_PREFIX . 'mu_title',
            self::THEME_FIELD_PREFIX . 'mu_type',
            self::THEME_FIELD_PREFIX . 'mu_price',
            self::THEME_FIELD_PREFIX . 'mu_beds',
            self::THEME_FIELD_PREFIX . 'mu_baths',
            self::THEME_FIELD_PREFIX . 'mu_size',
            self::THEME_FIELD_PREFIX . 'mu_size_postfix',
            self::THEME_FIELD_PREFIX . 'mu_availability_date'
         ) , 'Content of this field will be driven from another fields');
        
        //$this->addField( self::THEME_FIELD_PREFIX . 'multi_units_ids', 'Listing IDs', 'string', null, 'Enter listing IDs with comma separater(eg: 4,5,6)' );
            
        $this->addField( self::THEME_FIELD_PREFIX . 'mu_title', 'Titles', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'mu_type', 'Property Type', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'mu_price', 'Prices', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'mu_beds', 'Bedrooms', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'mu_baths', 'Bathrooms', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'mu_size', 'Property Sizes', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'mu_size_postfix', 'Sizes Postfix', 'stringSubField', null, "separate each value with a '|'" );

        $this->addField( self::THEME_FIELD_PREFIX . 'mu_availability_date', 'Availability Date', 'stringSubField', null, "separate each value with a '|'" );

    }

    /**
     * Remove a field from custom fields
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string field slug
     * 
     * @return void
     */
    private function removeField( $fieldSlug )
    {

        if ( isset( $this->customFields[ $fieldSlug ] ) ){

            unset( $this->customFields[ $fieldSlug ] );

        }

    }

    /**
     * Define New field for properties
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * @param string Field Name
     * @param string Field Type
     * @param array|null array of enum values , default is null
     * @param string Field Tooltip , default is blank
     * @param bool Field contains HTML or no , default is true
     * @param string Field Default Text
     * @param bool Field is Main Field of WordPress Post ,  default is false
     * 
     * @return array array of Field details
     */
    private function addField( $fieldSlug, $fieldName, $fieldType, $enumValues = null, $tooltip = "", $isHtml = true, $defaultText = '' , $mainField = false ){

        $field =  array(    "name" => $fieldName , 
                            "type" => $fieldType , 
                            "enumValues" => $enumValues , 
                            "tooltip" => $tooltip , 
                            "is_sub_field" => false , 
                            "isMainField" => $mainField , 
                            "slug" => $fieldSlug , 
                            "isHtml" => $isHtml , 
                            "defaultText" => $defaultText , 
                            "idxMappedTo" => '');

        $this->customFields[$fieldSlug] = $field;

        if ( ! empty( $enumValues ) ){

            foreach ( $enumValues as $key => $value) {

                if ( is_array( $value ) ){

                    foreach ($value as $n => $param) {	

                        if (is_array($param) and ! empty($this->customFields[$param['slug']]))

                            $this->customFields[$param['slug']]['is_sub_field'] = true;								

                    }
                    
                }

            }

        }

        return $field;
    
    }

    /**
     * Add String field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * 
     * @return array array of Field Details
     */
    private function addFieldCustom( $slug ){

        return $this->addField( $slug , 'Custom field' , 'string', null, '' );

    }

    /**
     * Add FieldsBuilder field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * 
     * @return array array of Field Details
     */
    private function addFieldHouzezFieldsBuilder( $slug ){
                
        return $this->addField( $slug , 'Custom field' , 'fieldBuilder', null, '' );

    }

    /**
     * Add Undefined Fielda as String field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * 
     * @return array array of Field Details
     */
    private function addUndefinedFields( $slug ){
            
        if ( !isset( $this->customFields[ $slug ] ) ){

            if ( substr( $slug , 0 , strlen( self::THEME_FIELD_PREFIX )  ) ==  self::THEME_FIELD_PREFIX )

                return $this->addFieldHouzezFieldsBuilder( $slug );

            return $this->addFieldCustom( $slug );
    
        }

    }

    /**
     * Map a Value to a Custom Field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field slug
     * @param string|array|object Value
     * 
     * @return void
     */
    private function mapValue( $slug , $value ){

        if ( isset( $this->customFields[ $slug ] ) ){

            $this->customFields[ $slug ]['idxMappedTo'] = $value;
            $this->customFields[ $slug ]['defaultText'] = '';

        }            

    }

    /**
     * Map Array of Values to array of Cusotm Fields
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param void|bool False on fails
     */
    private function mapValues( $slugValues ){

        if ( !is_array( $slugValues ) )
            return false;        
        
        foreach ($slugValues as $slug => $value) {

            $this->addUndefinedFields( $slug );
            
            $this->mapValue( $slug , $value );

        }

    }
    
    /**
     * Get Value of Specefic Custom Field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * 
     * @return string|array|object
     */
    private function getValue( $slug ){

        $value = '';

        if ( isset( $this->customFields[ $slug ] ) ){

            if ( !empty(  $this->customFields[ $slug ]['idxMappedTo'] ) &&
                 is_array( $this->customFields[ $slug ]['idxMappedTo'] ) &&
                 isset( $this->customFields[ $slug ]['idxMappedTo']['value'] )
                 ){

                $value = $this->customFields[ $slug ]['idxMappedTo']['value'];

            }elseif ( $this->customFields[ $slug ]['defaultText'] != null || trim($this->customFields[ $slug ]['defaultText']) != ''  )

                $value = $this->customFields[ $slug ]['defaultText'];
        
        }
        
        return $value;

    }

    /**
     * Set Value for Specefic Custom Field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * @param string|array Field Value
     * 
     * @return bool
     */
    private function setValue( $slug , $value ){

        $result = false;
        
		if ( isset( $this->customFields[ $slug ] ) ){

			if ( !isset( $this->customFields[ $slug ]['idxMappedTo'] ) ){
				
				 $this->customFields[ $slug ] = array ( "idxMappedTo" => array( "value" => $value )  ) ;
				
				$result = true;
				
			}elseif ( !isset( $this->customFields[ $slug ]['idxMappedTo']['value'] ) ){
				
				 $this->customFields[ $slug ]['idxMappedTo'] = array( "value" => $value ) ;
				
				$result = true;
				
			}else{
            
				$this->customFields[ $slug ]['idxMappedTo']['value'] = $value;
				
				$result = true;
				
			}

        }

        return $result;
        
    }

    /**
     * Get Extra Data of a Custom Field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * 
     * @return string|array|object
     */
    private function getExtra( $slug ){

        $value = '';

        if ( isset( $this->customFields[ $slug ] ) ){

            if ( !empty(  $this->customFields[ $slug ]['idxMappedTo'] ) &&
                 is_array( $this->customFields[ $slug ]['idxMappedTo'] ) &&
                 isset( $this->customFields[ $slug ]['idxMappedTo']['extra'] )
                 ){

                $value = $this->customFields[ $slug ]['idxMappedTo']['extra'];

            }
        
        }
        
        return $value;

    }

    /**
     * Get Replacement Values of a Custom Field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param $slug string Field Slug
     * 
     * @return array
     */
    private function getReplacements( $slug )
    {

        $value = [];

        if ( isset( $this->customFields[ $slug ] ) ){

            if ( !empty(  $this->customFields[ $slug ]['idxMappedTo'] ) &&
                 is_array( $this->customFields[ $slug ]['idxMappedTo'] ) &&
                 isset( $this->customFields[ $slug ]['idxMappedTo']['replacements'] )
                 ){

                $value = $this->customFields[ $slug ]['idxMappedTo']['replacements'];

            }
        
        }
        
        return $value;

    }	

    /**
     * Get Value Of PostType Fields
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * 
     * @return mixed
     */
    private function getValuePostType( $fieldSlug ){

        if ( $this->customFields[ $fieldSlug ]['type'] == 'postType' )

            if ( is_array( $this->customFields[ $fieldSlug ]['enumValues'] ) & !empty( $this->getValue( $fieldSlug ) )){

                $class = ( $this->customFields[ $fieldSlug ]['enumValues'] ['callBackClass'] ) ? $this->customFields[ $fieldSlug ]['enumValues'] ['callBackClass'] : '' ;

                $method = ( $this->customFields[ $fieldSlug ]['enumValues'] ['callBackMethod'] ) ? $this->customFields[ $fieldSlug ]['enumValues'] ['callBackMethod'] : '' ;

                if ( !empty( $class ) && !empty( $method ) ){

                    if ( method_exists( $class , $method ) ){
						
						$finalValue = $this->getValue( $fieldSlug );
						
						$replacements = $this->getReplacements( $fieldSlug );
						
						if ( is_array( $replacements ) && !empty( $replacements ) && ( isset( $replacements[ $finalValue ] ) || isset( $replacements['*'] ) ) ){
							
							$finalValue = $replacements[ $finalValue ] ?? $replacements[ '*' ];
							
						}
						
                        return call_user_func( $class . '::' . $method ,  $finalValue );
						
                    }

                }

            }

        return false;

    }

    /**
     * Get Value of FieldSet field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * 
     * @return array
     */
    private function getValueFieldset( $fieldSlug ){

        $fieldset = array();

        if ( $this->customFields[ $fieldSlug ]['type'] == 'fieldset' )

            if ( is_array( $this->customFields[ $fieldSlug ]['enumValues'] )){

                foreach ( $this->customFields[ $fieldSlug ]['enumValues'] as $fieldId => $fieldSlug ){
                    
                    //Extract Values 
                    $subField = $this->getValue( $fieldSlug ) ; 

                    if ( !empty( $subField ) )

                        foreach ( explode( "|", $subField ) as $key => $value ) {
                            
                            $fieldset[$key][$fieldSlug] = trim( $value );
                            
                        }

                }


            }

        return $fieldset;

    }

    /**
     * Get Value of Comma separated Fieldset Field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * 
     * @return string
     */
    private function getValueCommaFieldset( $fieldSlug ){

        $fieldset = '';

        if ( $this->customFields[ $fieldSlug ]['type'] == 'commaFieldset' )

            if ( is_array( $this->customFields[ $fieldSlug ]['enumValues'] )){

                foreach ( $this->customFields[ $fieldSlug ]['enumValues'] as $fieldId => $enumFieldSlug ){
                    
                    //Extract Values 
                    $subField = $this->getValue( $enumFieldSlug ) ; 

                    if ( !empty( $subField ) ){

                        if ( $fieldset != '' )
                            $fieldset .= ',' ;

                        $fieldset .= $subField ;

                    }

                }


            }

        return $fieldset;

    }

    /**
     * Validate List Field
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * 
     * @return bool
     */
    private function validateListField( $fieldSlug ){

        if ( $this->customFields[ $fieldSlug ]['type'] == 'list' )
            return key_exists( $this->getValue( $fieldSlug ) , $this->customFields[ $fieldSlug ]['enumValues'] )  ; 

        return false;

    }

    /**
     * Insert AttachmentList Field to Property Post
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * @param int Post ID
     * 
     * @return bool
     */
    private function insertPropertyAttachmentListField( $fieldSlug , $postId ){

        if ( $this->customFields[ $fieldSlug ]['type'] == 'attachmentList' ){
            
            $fieldValue = $this->getValue( $fieldSlug );

            if ( !empty( $fieldValue ) ){

                $list = explode( '|' , $fieldValue );

                $attachments = 0;                

                foreach ( $list as $key => $value ){

                    if (    isset( $this->importOptions['max_images_import'] ) &&
                            ( $this->importOptions['max_images_import'] > 0 ) ) {

                        if ( $attachments >= $this->importOptions['max_images_import'] )

                            break;

                    }

                    $generateThumbnails = ( isset( $this->importOptions['generate_thumbs_images'] ) && $this->importOptions['generate_thumbs_images'] );
                    
                    if (    isset( $this->importOptions['use_external_images'] ) &&
                            $this->importOptions['use_external_images'] ) {
                        
                        $imgId = $this->attachImageWithoutDownloadToMedia( $value , $postId );

                    }else{

                        $imgId = $this->downloadToMedia( $value , $generateThumbnails , $postId );
                        
                    }                    
    
                    if ( $imgId !== false || !is_wp_error( $imgId ) ){                        

                        add_post_meta( $postId , $fieldSlug , $imgId );

                        $attachments++;

                    }
                            
                }

                return ( $attachments == count( $list ) );
                    
            }

        }

        return false;

    }

    /**
     * Insert Taxonomy field to Property Post
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * @param int Post ID
     * 
     * @return bool|int integer if not fully inserted
     */
    private function insertPropertyTaxonomyField( $fieldSlug , $postId ){

        if ( $this->customFields[ $fieldSlug ]['type'] == 'taxonomy' ){
            
            $class = ( $this->customFields[ $fieldSlug ]['enumValues'] ['callBackClass'] ) ?  : '' ;

            $method = ( $this->customFields[ $fieldSlug ]['enumValues'] ['callBackMethod'] ) ?  : '' ;

            $fieldValue = $this->getValue( $fieldSlug );

            if ( !empty( $fieldValue ) ){

                $list = explode( ',' , $fieldValue );

                $passedTaxonomy = 0;

                if ( method_exists( $class , $method ) ) {
                    
                    $classObj = new $class();

                    foreach ( $list as $key => $value ){
                            
                        if ( !empty( $value ) ){

							$finalValue = $value;
							
							$replacements = $this->getReplacements( $fieldSlug );
							
							if ( is_array( $replacements ) && !empty( $replacements ) && isset( $replacements[ $finalValue ] ) ){
								
								$finalValue = $replacements[ $finalValue ];
								
							}

                            $parentInfo = array();

                            if ( $this->setTaxonomyParentValue( $fieldSlug ) ){

                                $parentInfo = $this->customFields[ $fieldSlug ]['enumValues'] ;
                                
                            }
    
                            if ( call_user_func( array( $classObj , $method ) ,  $finalValue , $postId , $parentInfo ) )
                                $passedTaxonomy ++;
    
                        }                    
    
                    }

                }
        
                return ( $passedTaxonomy == count( $list ) ) ? true : $passedTaxonomy;
                
            }

        }

        return false;

    }

    /**
     * set parentValue for some Taxonomy Field that have parentValue
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Taxonomy Field Slug
     * 
     * @return bool
     */
    private function setTaxonomyParentValue( $fieldSlug ){

        if ( $this->customFields[ $fieldSlug ]['type'] == 'taxonomy' ){

            if ( isset( $this->customFields[ $fieldSlug ]['enumValues'] ['parentValue'] ) ){

                $fieldOfParentValue =  $this->customFields[ $fieldSlug ]['enumValues'] ['parentValue'] ;

                if ( !empty( $fieldOfParentValue ) ){
                    
                    $this->customFields[ $fieldSlug ]['enumValues'] ['parentValue'] = $this->getValue( $fieldOfParentValue );

                    return ( !empty( $this->customFields[ $fieldSlug ]['enumValues'] ['parentValue'] ) );

                }

            }

        }

        return false;

    }

    /**
     * Insert FieldsBuilder Field to Property Post
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * @param int Post ID
     * 
     * @return void
     */
    private function insertFieldsBuilder( $fieldSlug , $postId ){

        if ( $this->customFields[ $fieldSlug ]['type'] == 'fieldBuilder' ){
            
            if ( FieldsBuilder::class ){

                $fieldBuilderExtra = $this->getExtra( $fieldSlug );
            

                $extraLabel = $extraPlaceholder = '';
                
                $extraSearchable = false;
    
                if ( !empty( $fieldBuilderExtra ) && is_array( $fieldBuilderExtra ) ){
                    
                    $extraSearchable = ( isset( $fieldBuilderExtra['searchable'] ) && ( $fieldBuilderExtra['searchable'] == 'yes' ) ) ? true : false;
                    $extraLabel = ( $fieldBuilderExtra['label'] ) ? : '';
                    $extraPlaceholder = ( $fieldBuilderExtra['placeholder'] ) ? : '';
    
                }
                    
                $fieldBuilderSlug = substr( $fieldSlug , strlen( self::THEME_FIELD_PREFIX )  );

                FieldsBuilder::addField( $fieldBuilderSlug , $extraLabel , $extraSearchable , 'text' , null , $extraPlaceholder );

            }
            
            $fieldValue = $this->getValue( $fieldSlug );

            $this->postMeta( $postId , $fieldSlug , $fieldValue );

        }

    }

    /**
     * Count Imported Properties
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @static
     * 
     * @param bool $demoOnly count only demo listings or all of imported
     * 
     * @return int total imported listings
     */
    static public function countImportedProperties( $demoOnly = false )
    {
        
        $totalProperties = 0;

        $meta = array();

        $metaKey = $demoOnly ? self::REALTYNA_IDX_META_MARK . "_demo" : self::REALTYNA_IDX_META_MARK ;

        $meta[] = array( "key" => $metaKey , "value" => 1 , "compare" => "=" );

        $searchArgs = array(
            'numberposts' => -1,
            'posts_per_page' => -1,
            'post_type'   => self::PRODUCT_POST_TYPE,
            'meta_query' => $meta
        );
        
        $Properties = new \WP_Query( $searchArgs );

        $totalProperties = $Properties->found_posts;

        wp_reset_postdata(); 

        return $totalProperties;

    }

    /**
     * Downlaod Files and attach to Post
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string File Url
     * @param int Post ID
     * 
     * @return int|bool attahcment ID or False on fails
     */
    private function downloadFile( $url , $postId ) {
        
        if ( $url != "" && $postId > 0 ) {

            if ( ! function_exists( 'download_url' ) )
                include_once( ABSPATH . 'wp-admin/includes/file.php' );
                     
            $file = array();
            $file['name'] = $url;
            $file['tmp_name'] = download_url( $url );
     
            if ( is_wp_error( $file['tmp_name'] ) ) {

                @unlink( $file['tmp_name'] );
                
                //var_dump( $file['tmp_name']->get_error_messages( ) );

            }else{

                if ( !function_exists( 'media_handle_sideload' ) )
                    include_once( ABSPATH . 'wp-admin/includes/admin.php' );

                $attachmentId = media_handle_sideload( $file , $postId );
                 
                if ( is_wp_error( $attachmentId ) ) {

                    @unlink( $file['tmp_name'] );

                    //var_dump( $attachmentId->get_error_messages( ) );

                } else

                    return $attachmentId;

            }
        }

        return false;
    }

    /**
     * Add External Image to Property Post
     * 
     * @author Chris A <chris.a@realtyna.net> 
     * 
     * @param string Image URL
     * @param int|null Post ID , default value is null
     * 
     * @return int|bool Attahcment ID or False on fails
     */
    private function attachImageWithoutDownloadToMedia( $url , $postId = null ){

        $allowedExtensions = array( "jpg" , "jpeg" , "png" );
        $explodedUrl = explode( "." , $url );
        $urlExt = end( $explodedUrl ) ;

        if ( in_array( strtolower(  $urlExt  ), $allowedExtensions ) ){

            //$info = @getimagesize( $url );
            $info = array( "0" => "1024" , "1" => "682" , "mime" => "image/jpeg");

            if ( !empty( $info ) && is_array( $info ) ) {
    
                $imgWidth = $info[0];
                $imgHeight = $info[1];
                $imgMimeType = $info['mime'];
    
    
                $filename = wp_basename( $url );
                
                $postId = ( !empty( $postId ) && is_numeric( $postId ) ) ? $postId : 0 ;
                $postAuthor = $this->importOptions['post_author'] ?? 0;
                $attachment = array(
                    'guid' => $url,
                    'post_parent'   => $postId,
                    'post_mime_type' => $imgMimeType,
                    'post_title' => preg_replace( '/\.[^.]+$/', '', $filename ),
                    'post_author' => $postAuthor
                );
    
                $attachmentMetadata = array(
                    'width' => (int) $imgWidth,
                    'height' => (int) $imgHeight,
                    'file' => $filename );
    
                $attachmentMetadata['sizes'] = array( 
                    'full' => array('width' => (int) $imgWidth, 'height' => (int) $imgHeight, 'file' => $filename , 'mime-type' => $imgMimeType) ,
                    'thumbnail' => array( 'width' => 150, 'height' => 150, 'file' => $filename , 'mime-type' => $imgMimeType), 
                    'medium' => array( 'width' => 300, 'height' => 300, 'file' => $filename , 'mime-type' => $imgMimeType),
                    'medium_large' => array( 'width' => 768, 'height' => 0, 'file' => $filename , 'mime-type' => $imgMimeType),
                    'large' => array( 'width' => 1024, 'height' => 1024, 'file' => $filename , 'mime-type' => $imgMimeType),
                    'houzez-gallery' => array( 'width' => 1170, 'height' => 785, 'file' => $filename , 'mime-type' => $imgMimeType),
                    'houzez-item-image-1' => array( 'width' => 592, 'height' => 444, 'file' => $filename , 'mime-type' => $imgMimeType), 
                    'houzez-item-image-4' => array( 'width' => 758, 'height' => 564, 'file' => $filename , 'mime-type' => $imgMimeType), 
                    'houzez-item-image-6' => array( 'width' => 584, 'height' => 438, 'file' => $filename , 'mime-type' => $imgMimeType), 
                    'houzez-variable-gallery' => array( 'width' => 0, 'height' => 600, 'file' => $filename , 'mime-type' => $imgMimeType), 
                    'houzez-map-info' => array( 'width' => 120, 'height' => 90, 'file' => $filename , 'mime-type' => $imgMimeType), 
                    'houzez-image_masonry' => array( 'width' => 496, 'height' => 9999, 'file' => $filename , 'mime-type' => $imgMimeType) 
                );
                
                $attachmentId = wp_insert_attachment( $attachment );
    
                if ( $attachmentId !== 0 || !is_wp_error( $attachmentId ) ) {
    
                    wp_update_attachment_metadata( $attachmentId, $attachmentMetadata );
                    
                    $externalImagesMark = ( class_exists('RealtynaMlsSync') ) ? \Realtyna\Sync\Core\App::getExternalImagesMark() : '_REALTYNA_MLS_SYNC_EXTERNAL_IMAGE';

                    update_post_meta( $attachmentId, $externalImagesMark, 1 );
                    update_post_meta( $attachmentId, '_ADDED_BY_REALTYNA_MLS_SYNC', 1 );

                    return $attachmentId;
    
                }    
    
            }
    
        }else{
            return $this->downloadToMedia( $url , false , $postId );
        }

        return false;
        
    }

    /**
     * Download Media and generate thumbnails and attach to Property Post
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Media URL
     * @param bool Generate thumbnails or no , default is True
     * @param int|null Post Id , default value is null
     * 
     * @return int|bool Attahcment ID or False on fails
     */
    private function downloadToMedia( $url , $generateThumbnails = true , $postId = null )
    {
        
        $fileStored = false;

		if ( !empty( $url ) ){
			
            $uploadDir = wp_upload_dir();

            $context = stream_context_create( array(
                'http' => array( 
                    'timeout' => 600, 
                    'header' => 'Connection: close\r\n' 
                    ) 
                ) 
            );
                    
            $filename = basename( $url );
            
            if ( wp_mkdir_p( $uploadDir['path'] ) )
        
                $file = $uploadDir['path'] . '/' . $filename;
                
            else
				
                $file = $uploadDir['basedir'] . '/' . $filename;
                
            $fileData = file_get_contents( $url ,false ,$context );
            
            if ( $fileData !== false ){
				
                $fileStored = file_put_contents( $file, $fileData );
				
			}else{
				
				if ( function_exists('fopen') && function_exists('curl_init') ){
					
					if ( $file ) {
							
						$localFile = fopen( $file, 'w' );
							
						if ( $localFile !== false ) {

							$curl = curl_init( $url );
							curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true);
						
							curl_setopt( $curl, CURLOPT_FILE, $localFile);
								
							$output = curl_exec( $curl );

							if ( !curl_errno($curl) ) {
							
								curl_close($curl);
								
								fclose( $localFile );

								if( file_exists( $file ) ) {
									
									$fileStored = true;
									
								}

							}
					
						}
					}
						
				}
				
			}
			
			if ( $fileStored ){

                $fileURL = $uploadDir['url'] . '/' . $filename;

                $wpFileType = wp_check_filetype( $filename , null );
                
                $postAuthor = $this->importOptions['post_author'] ?? 0;

                $attachment = array(
                  'guid'  => $fileURL,
                  'post_mime_type' => $wpFileType['type'],
                  'post_title' => sanitize_file_name( $filename ),
                  'post_content' => '',
                  'post_status' => 'inherit',
                  'post_author' => $postAuthor
                );
                
                if ( !empty( $postId ) && is_numeric( $postId ) )
                    $attachId = wp_insert_attachment( $attachment, $file , $postId );
                else
                    $attachId = wp_insert_attachment( $attachment, $file );
        
                if ( !is_wp_error( $attachId ) ) {
    
                    if ( $generateThumbnails ) {
    
                        include_once( ABSPATH . 'wp-admin/includes/image.php' );
        
                        $attachData = wp_generate_attachment_metadata( $attachId, $file );
            
                        wp_update_attachment_metadata( $attachId, $attachData );
	                    update_post_meta( $attachId, '_ADDED_BY_REALTYNA_MLS_SYNC', 1 );
        
                    }
            
                    return $attachId;
        
                }    
    
            }
            
        }

        return $fileStored;

    }

    /**
     * Determine if wordpress installed on https
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    private function isHttps()
    {

        if ( function_exists('site_url') ){

            $arrayURL = explode( "://" , site_url() );

            if ( !empty( $arrayURL ) ){

                return ( strtolower( $arrayURL[0] ) == 'https' );

            }

        }else {

            return ( !empty( $_SERVER['HTTPS'] ) || ( isset( $_SERVER['SERVER_PORT'] ) && ( '443' == $_SERVER['SERVER_PORT'] ) ) );
            
        }
        
        return false;

    }

    /**
     * Convert a non-secure url to secure url
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $url
     * 
     * @return string secured url
     */
    private function forceHttps( $url )
    {

        $arrayURL = explode( "://" , $url );

        if ( !empty( $arrayURL ) ){

            if ( strtolower( $arrayURL[0] ) == 'http' ){

                $arrayURL[0] = 'https';

            }

        }

        return implode( "://" , $arrayURL );

    }

    /**
     * Unset P2P Empty Indexes from Peer to Peer Key Value arrays
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array Keys Array
     * @param array Values Array
     * 
     * @return void
     */
    private function unsetP2pEmptyIndexes( &$arrayKeys , &$arrayValues ){

        foreach( $arrayValues as $key => $value){
            if ( empty( $value ) ){
                unset( $arrayKeys[ $key ] );
                unset( $arrayValues[ $key ] );		
            }
        }   

    }

    /**
     * Remove Empty Addiational Features
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function removeEmptyAdditionalFeatures(){

        if ( empty( $this->getValue(  self::THEME_FIELD_PREFIX . 'additional_feature_value' ) ) ){

            $this->customFields[ self::THEME_FIELD_PREFIX . 'additional_feature_title' ]['idxMappedTo'] = '';
            $this->customFields[ self::THEME_FIELD_PREFIX . 'additional_feature_title' ]['defaultText'] = '';

        }else{

            $featuresKey = explode( "|" ,  $this->getValue(  self::THEME_FIELD_PREFIX . 'additional_feature_title' ) );
            $featuresValue = explode( "|" , $this->getValue(  self::THEME_FIELD_PREFIX . 'additional_feature_value' ) );
    
            $this->unsetP2pEmptyIndexes( $featuresKey , $featuresValue );
    
            if ( !empty( $featuresKey ) && !empty( $featuresValue ) ){
    
                $this->customFields[ self::THEME_FIELD_PREFIX . 'additional_feature_title' ]['idxMappedTo']['value'] = implode( "|" , $featuresKey );
                $this->customFields[ self::THEME_FIELD_PREFIX . 'additional_feature_title' ]['defaultText'] = '';
                $this->customFields[ self::THEME_FIELD_PREFIX . 'additional_feature_value' ]['idxMappedTo']['value'] = implode( "|" , $featuresValue );
                $this->customFields[ self::THEME_FIELD_PREFIX . 'additional_feature_value' ]['defaultText'] = '';
    
            }else{
    
                $this->customFields[ self::THEME_FIELD_PREFIX . 'additional_feature_title' ]['idxMappedTo'] = '';
                $this->customFields[ self::THEME_FIELD_PREFIX . 'additional_feature_title' ]['defaultText'] = '';
                $this->customFields[ self::THEME_FIELD_PREFIX . 'additional_feature_value' ]['idxMappedTo'] = '';
                $this->customFields[ self::THEME_FIELD_PREFIX . 'additional_feature_value' ]['defaultText'] = '';
    
            }
    
        }        

    }

    /**
     * Check for Youtubes link and convert them to Embeded Link
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Link
     * 
     * @return string
     */
    private function checkForYoutubeLink( $link ){
        
        if ( strpos( $link , 'youtu.be' ) !== false || strpos( $link , 'youtube.com/watch' ) !== false ){

            //https://youtu.be/HzqA2OC9_8g => sparator is /
            //https://www.youtube.com/watch?v=HzqA2OC9_8g => separator is =
            $separator = ( strpos( $link , 'youtu.be' ) !== false ) ? '/' : '=';
            $arrayLink = explode( $separator , $link );

            $link = 'https://www.youtube.com/embed/' . end( $arrayLink ) ;            

        }

        return $link;

    }    

    /**
     * Add Iframe tag to Virtual Tour Links
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function addIframeToVirtualTour(){

        $virtualTour = self::THEME_FIELD_PREFIX . 'virtual_tour';
    
        if (!empty(  $this->customFields[ $virtualTour ]['idxMappedTo'] ) &&
            is_array( $this->customFields[ $virtualTour ]['idxMappedTo'] ) &&
            isset( $this->customFields[ $virtualTour ]['idxMappedTo']['value'] )){
                
            $virtualTourValue = $this->customFields[ $virtualTour ]['idxMappedTo']['value'] ;

            if ( $this->isHttps() ){
                
                $virtualTourValue = $this->forceHttps( $virtualTourValue );

            }

            if ( substr( strtolower( $virtualTourValue ) , 0 , 4 ) == 'http' ){
                $this->customFields[ $virtualTour ]['idxMappedTo']['value'] =    '<iframe  src="' . $this->checkForYoutubeLink( $virtualTourValue ) . '" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';

            }

        }elseif ( ! empty(  $this->customFields[ $virtualTour ]['defaultText'] ) ){
                
            $virtualTourValue = $this->customFields[ $virtualTour ]['defaultText'] ;

            if ( substr( strtolower( $virtualTourValue ) , 0 , 4 ) == 'http' ){
                $this->customFields[ $virtualTour ]['defaultText'] =    '<iframe  src="' . $this->checkForYoutubeLink( $virtualTourValue ) . '" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';
            }

        }

    }

    /**
     * Applu Customization on desired MLss
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function applyMLSCustomizations(){

        if ( strtolower( $this->mlsProvider ) == 'ampimls' ){

            $propertyTypes = array(
                "A" => "Condos",
                "B" => "Houses",
                "E" => "Land",
                "F" => "Commercial",
                "G" => "Business",
                "H" => "Fractional ",
                "I" => "Multi-Family",
            );

            $propertyType = $this->getValue('property_type');

            if ( array_key_exists( $propertyType , $propertyTypes ) ){

                $this->setValue( 'property_type' , $propertyTypes[ $propertyType ] );

            }

        }

    }

    /**
     * Field Dpendency Checker , trigger functions before insert data to DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function fieldsDependencyChecker(){
        
        $this->setDefaultValue( '_thumbnail_id' , ''  );

        if ( empty( $this->getValueFieldset( 'floor_plans' ) ) )
            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'floor_plans_enable' , 'disable'  );

        if ( empty( $this->getValueFieldset( 'additional_features' ) ) )
            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'additional_features_enable' , 'disable'  );
        else {
            $this->removeEmptyAdditionalFeatures();
        }

        if ( empty( $this->getValueFieldset( 'fave_multi_units' ) ) )
            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'multiunit_plans_enable' , 'disable'  );

        if ( empty( $this->getValue( 'houzez_geolocation_lat' ) ) && empty( $this->getValue( 'houzez_geolocation_long' ) ) ){
            
            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'property_map' , '0'  );
            
        }else {
            
            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'property_map' , '1'  );
            
        }

		//fix map issue in houzez on Canadian MLSs
		$mapAddress = $this->getValue( self::THEME_FIELD_PREFIX . 'property_map_address' );
		
		if ( !empty( $mapAddress ) ){
			
			if ( substr( strtolower( $mapAddress ) , -4  ) == ', ca' ){
				
				$mapAddress = substr( $mapAddress , 0 , strlen( $mapAddress ) - 4 ) . ', Canada';
				
				$this->setValue( self::THEME_FIELD_PREFIX . 'property_map_address' , $mapAddress );
				
			}
			
		}

        if ( !empty( $this->getValue( self::THEME_FIELD_PREFIX . 'property_images' ) ) ){
            
            if ( empty( $this->getValue( '_thumbnail_id' ) ) ){
    
                $gallery_images = $this->getValue( self::THEME_FIELD_PREFIX . 'property_images' );
    
                $gallery_images_array = explode( '|' , $gallery_images );

                if ( !empty( $gallery_images_array )  && is_array( $gallery_images_array ) ){
                    
                    $this->setDefaultValue( '_thumbnail_id' , $gallery_images_array[0]  );

                }
                    
            }
                
        }

        if ( empty( $this->getValue( '_thumbnail_id' ) ) ){

            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'featured' , '0'  );

            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'prop_homeslider' , 'no'  );

        }

        $defaultDisplayOption = $this->getValue( self::THEME_FIELD_PREFIX . 'agent_display_option' );
        if ( empty( $this->getValue( self::THEME_FIELD_PREFIX . 'agents' ) ) && $defaultDisplayOption == 'agent_info' ){
            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'agent_display_option' , 'none'  );
        }
        if ( empty( $this->getValue( self::THEME_FIELD_PREFIX . 'property_agency' ) ) && $defaultDisplayOption == 'agency_info' ){
            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'agent_display_option' , 'none'  );
        }
            
            
        if ( !empty( $this->getValue( self::THEME_FIELD_PREFIX . 'virtual_tour' )  ) ){
            
            $this->addIframeToVirtualTour();

        }
        /*
        if ( !empty( $this->getValue( self::THEME_FIELD_PREFIX . 'property_price' ) ) ){
            
            $propertyPrice = sprintf('%.2f', $this->getValue( self::THEME_FIELD_PREFIX . 'property_price' ) ) ;
            $this->setValue( self::THEME_FIELD_PREFIX . 'property_price' , $propertyPrice );

        }
        */
        if ( !empty( $this->getValue( self::THEME_FIELD_PREFIX . 'property_sec_price' ) ) ){
            
            $propertySecPrice = sprintf('%.2f', $this->getValue( self::THEME_FIELD_PREFIX . 'property_sec_price' ) ) ;
            $this->setValue( self::THEME_FIELD_PREFIX . 'property_sec_price' , $propertySecPrice );

        }
        
        if ( empty( $this->getValue( self::THEME_FIELD_PREFIX . 'livingsize' ) ) ){
            
            $this->removeField( self::THEME_FIELD_PREFIX . 'livingsizeprefix' );
        }

        if ( !empty( $this->getValue( self::THEME_FIELD_PREFIX . 'officename' ) ) ){
            
            $value = strtoupper( $this->getValue( self::THEME_FIELD_PREFIX . 'officename' ) );

            $this->setValue( self::THEME_FIELD_PREFIX . 'officename' , $value );
            
        }

        if ( $this->getValue( self::IDX_STATUS_FIELD ) == 'A' ){
            
            $this->setValue( self::IDX_STATUS_FIELD , 'Active' );

        }

        $bathRooms = $this->getValue( self::THEME_FIELD_PREFIX . 'property_bathrooms' );
        
        if ( \is_numeric( $bathRooms ) ){

            $this->setValue( self::THEME_FIELD_PREFIX . 'property_bathrooms' , floor( $bathRooms ) );

        }
        
        //trick to bypass titles and map address that have no unit#
		$postTitle = $this->getValue( 'post_title' );
		$newPostTitle = str_replace( ' Unit#,' , ',' , $postTitle );
		if ( $newPostTitle != $postTitle ){
			$this->setValue( 'post_title' , $newPostTitle );
		}
		
		$propertyMapAddress = $this->getValue( self::THEME_FIELD_PREFIX . 'property_map_address' );
		$newPropertyMapAddress = str_replace( ' Unit#,' , ',' , $propertyMapAddress );		
		if ( $newPropertyMapAddress != $propertyMapAddress ){
			$newPropertyMapAddress = str_replace( '  ' , ' ' , $newPropertyMapAddress );
			$this->setValue( self::THEME_FIELD_PREFIX . 'property_map_address' , $newPropertyMapAddress );
		}
		
		$propertyAddress = $this->getValue( self::THEME_FIELD_PREFIX . 'property_address' );		
		if ( !empty( $propertyAddress ) && substr( trim( $propertyAddress ) , -5 ) == 'Unit#'  ){
			$newPropertyAddress = str_replace( ' Unit#' , '' , $propertyAddress );
			$newPropertyAddress = str_replace( '  ' , ' ' , $newPropertyAddress );
			$this->setValue( self::THEME_FIELD_PREFIX . 'property_address' , $newPropertyAddress );
		}

        $this->applyMLSCustomizations();

    }

    /**
     * Set default value for Custom Fields
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Field Slug
     * @param string Field Value
     * 
     * @return void
     */
    private function setDefaultValue( $slug , $value ){

        if ( isset( $this->customFields[ $slug ] ) )
            $this->customFields[ $slug ]['defaultText'] = $value;

    }

    /**
     * Add / Update WordPress Post Meta
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int Post ID
     * @param string Meta Key
     * @param string Meta Value
     * @param bool void empty Values ,  default is True
     * @param bool is it update or no , default is True
     * 
     * @return bool
     */
    private function postMeta( $postId , $slug , $value , $voidEmpty = true , $update = true , $checkReplacements = true)
    {

        if ( $voidEmpty && empty( $value ) )
            return false;

        $finalValue = $value;
		
        if ( $checkReplacements ){

            $replacements = $this->getReplacements( $slug );
    
            if ( is_array( $replacements ) && !empty( $replacements ) && ( isset( $replacements[ $value ] ) || isset( $replacements[ '*' ] ) ) ){
                    
                $finalValue = $replacements[ $value ] ?? $replacements[ '*' ];
                    
            }
    
        }
    
        if ( $update )
            return update_post_meta( $postId , $slug , $finalValue );
        else
            return add_post_meta( $postId , $slug , $finalValue );
    
    }

    /**
     * Bulk Update Post Metas ,  Change Meta Value of a Meta Key for All Properties
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Meta Key
     * @param string Meta Value
     * 
     * @return int Total Updated Records
     */
    public function bulkUpdatePostMeta( $key , $value ){

        global $wpdb;

        $idxMetaMark = self::REALTYNA_IDX_META_MARK;

        return $wpdb->query(
                    $wpdb->prepare( 
                        "update {$wpdb->prefix}postmeta set meta_value = %s where meta_key = %s and `post_id` in ( select `post_id` from {$wpdb->prefix}postmeta where `meta_key` = %s ) ",
                        $value ,
                        $key ,
                        $idxMetaMark
                    )
                );

    }

    /**
     * Buik Remove Properties
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param bool , remove demo properties or all imported ,  default id False
     * 
     * @return void 
     */
    public function bulkRemoveProperties( $demoOnly = false ){

		global $wpdb;
		
		$metaKey = $demoOnly ? self::REALTYNA_IDX_META_MARK . "_demo" : self::REALTYNA_IDX_META_MARK ;
					
		$sql = "update {$wpdb->prefix}posts set `post_status` = 'trash' WHERE `post_type` = '" . self::PRODUCT_POST_TYPE . "' AND `ID` IN ( SELECT `post_id` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = '" . $metaKey . "' )";
    
        $removedPosts = $wpdb->query( $sql );

    }

    /**
     * Force Remove Properties
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void 
     */
    public function forcePurge(){

        global $wpdb;

        $sql = "DELETE FROM $wpdb->posts WHERE `ID` IN ( SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key` = %s )";

        $wpdb->query( $wpdb->prepare( $sql , self::REALTYNA_IDX_META_MARK ) );

    }

    /**
     * Buik Remove Properties
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array , excluded properties array ids from delete
     * 
     * @return int
     */
    public function removeUnwantedProperties( $excludedProperties )
    {

        global $wpdb;

        $removedPosts = 0;

        if ( isset( $excludedProperties['listing_ids'] ) && !empty( $excludedProperties['listing_ids'] ) && is_array( $excludedProperties['listing_ids'] ) ){
            			
            $excludedIDs = "'" . implode( "','" , $excludedProperties['listing_ids'] ) . "'";
			
            $sql = "UPDATE {$wpdb->prefix}posts SET `post_status` = 'trash' WHERE `post_type` = '" . self::PRODUCT_POST_TYPE . "' AND `ID` IN ( SELECT `post_id` FROM {$wpdb->prefix}postmeta WHERE `meta_key` = '" . self::IDX_IDENTITY_FIELD . "' AND `meta_value` NOT IN (" . $excludedIDs . ") )";
    
            $removedPosts = $wpdb->query( $sql );

        }

        return $removedPosts;

    }

	/**
	* Purge Trashed Listings
	* @author Chris A <chris.a@realtyna.net>
	*
	* @return void
	*/
	public function purgeListings()
	{
		
        global $wpdb;

		$purgeLimit = ( defined( 'REALTYNA_MLS_SYNC_PURGE_LIMIT' ) && !empty( REALTYNA_MLS_SYNC_PURGE_LIMIT ) && is_numeric( REALTYNA_MLS_SYNC_PURGE_LIMIT ) )  ? REALTYNA_MLS_SYNC_PURGE_LIMIT : 50;

        $listings = $wpdb->get_results(
							"SELECT `ID` FROM {$wpdb->prefix}posts WHERE `post_type` = '". self::PRODUCT_POST_TYPE ."' AND `post_parent` = 0 AND `post_status` = 'trash' LIMIT {$purgeLimit}"
					);
		
		foreach( $listings as $listing ){
			
			if ( function_exists( 'has_post_thumbnail' ) ) {
				
				if( has_post_thumbnail( $listing->ID ) ){
					
					$thumbnail = get_post_thumbnail_id( $listing->ID );
					wp_delete_attachment( $thumbnail , true );
					
				}
				
			}

			wp_delete_post( $listing->ID , true );
			
		}
		
	}

	/**
	* Purge all attachments of Trashed Listings
	* @author Chris A <chris.a@realtyna.net>
	*
	* @return void
	*/
	public function purgeAttachments()
	{
		
        global $wpdb;
		
		$purgeLimit = ( defined( 'REALTYNA_MLS_SYNC_PURGE_LIMIT' ) && !empty( REALTYNA_MLS_SYNC_PURGE_LIMIT ) && is_numeric( REALTYNA_MLS_SYNC_PURGE_LIMIT ) )  ? REALTYNA_MLS_SYNC_PURGE_LIMIT : 100;

		$attachments = $wpdb->get_results(
			"SELECT p.`ID`
				FROM {$wpdb->prefix}posts AS p
				INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.`ID` = pm.`post_id`
				WHERE p.`post_type` = 'attachment'
				AND p.`post_parent` > 0
			    AND pm.`meta_key` = '_ADDED_BY_REALTYNA_MLS_SYNC'
				AND p.`post_parent` NOT IN (
					SELECT `ID`
					FROM {$wpdb->prefix}posts
				) LIMIT {$purgeLimit}");

        if ( empty( $attachments ) ){

            $attachments = $wpdb->get_results(						
                               "SELECT `ID` FROM {$wpdb->prefix}posts WHERE `post_parent` = 0 AND `post_type` = 'attachment' AND `guid` LIKE 'https://idxmedia.realtyfeed.com%' LIMIT {$purgeLimit}"
                        );
                            
        }
                        
		foreach( $attachments as $attachment ){
			
			wp_delete_attachment( $attachment->ID , true );
			
		}
		
	}

	/**
	 * Add new post metas for all of the listings
	 *
	 * @author Chris A <chris.a@realtyna.net>
	 *
	 * @return void
	 */
	private function checkIdentityPostMeta()
	{
		
		$identityPostMeta = get_option( "REALTYNA_IDENTITY_POSTMETA" , 0 );
		
		if ( empty( $identityPostMeta ) ) {
			
			
			global $wpdb;
						
            $sql = "INSERT INTO `{$wpdb->prefix}postmeta` ( `post_id`, `meta_key` , `meta_value` )
						SELECT `post_id` , concat( `meta_key` , '_' , `meta_value` ) , '1' from `{$wpdb->prefix}postmeta` WHERE `meta_key` = '" . self::IDX_IDENTITY_FIELD . "' ";
    
            $insertedRecords = $wpdb->query( $sql );
			
			update_option( "REALTYNA_IDENTITY_POSTMETA" , 1  , true );
			
		}
		
	}

    /**
     * Is Propertiy Active (Allowed to import) or no
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    private function isAllowedProperty(){

        if ( is_array( $this->customFields ) && isset ( $this->customFields[ self::IDX_STATUS_FIELD ] ) ){

            $idxStatusFieldValue = $this->getValue( self::IDX_STATUS_FIELD );

            if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG  && defined( 'WP_DEBUG_MLS_SYNC' ) ){

                error_log( 'isAllowedProperty -> ' . self::IDX_STATUS_FIELD . ' : ' . $idxStatusFieldValue );
                
            }
    
            return ( !empty( $idxStatusFieldValue ) && in_array( $idxStatusFieldValue , $this->allowedPropertyStatus ) );

        }

        if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'WP_DEBUG_MLS_SYNC' ) ){

            error_log( 'isAllowedProperty -> ERR');
            
        }

        return false;

    }

    /**
     * Check Property Existance
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    private function propertyExists(){

        $idxIdentityFieldValue = $this->getValue( self::IDX_IDENTITY_FIELD );

		$exists = false;

        if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'WP_DEBUG_MLS_SYNC' ) ){

            error_log( 'propertyExists -> ' . self::IDX_IDENTITY_FIELD . ' : ' . $idxIdentityFieldValue );
            
        }

        if ( !empty( $idxIdentityFieldValue ) ){
			
			global $wpdb;
			
			if ( !empty( $wpdb ) ){

				$totalsQuery =  "SELECT COUNT(1) FROM `" . $wpdb->prefix . "posts` 
										WHERE	`post_status` <> 'trash' AND 
												`post_type` =  '" . self::PRODUCT_POST_TYPE . "' AND
												`ID` = ( SELECT `post_id` FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = '". self::IDX_IDENTITY_FIELD . "_" . $idxIdentityFieldValue  ."' LIMIT 1 )";
				
				$result = $wpdb->get_var( $totalsQuery );
				
				$exists = ( (int) $result > 0 );

			}
    
        }

        return $exists;

    }

    /**
     * Get Property By IDX Identity
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int WordPress Post ID
     */
    private function getPropertyIdByIdxIdentity()
    {

        global $wpdb;

        $idxIdentityFieldValue = $this->getValue( self::IDX_IDENTITY_FIELD );
		
        $postId = 0;

        if ( !empty( $wpdb ) ){

			$query = "SELECT `ID` FROM `" . $wpdb->prefix . "posts` 
							WHERE 	`post_status` <> 'trash' AND 
									`post_type` = '". self::PRODUCT_POST_TYPE ."' AND 
									`ID` = ( SELECT `post_id` FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = '". self::IDX_IDENTITY_FIELD . "_" . $idxIdentityFieldValue  ."' LIMIT 1 )";
    
            $result = $wpdb->get_var( $query );
			
			if ( $result > 0 ) {
				$postId = $result;
			}

        }		

        return $postId;

    }

    /**
     * Delete Attahcments of a Specefic Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int Property ID
     * 
     * @return void
     */
    private function deletePropertyAttachments( $postId )
    {

        if ( function_exists( 'has_post_thumbnail' ) ) {
			
			if( has_post_thumbnail( $postId ) ){
				
				$thumbnail = get_post_thumbnail_id( $postId );
				wp_delete_attachment( $thumbnail , true );
				
			}
			
		}
        
        $attachments = get_posts( array( 
                'post_type' => 'attachment' , 
                'posts_per_page' => -1, 
                'post_parent' => $postId 
            )
        );

        foreach ($attachments as $attachment) {

            wp_delete_attachment( $attachment->ID , true );

        }

    }

    /**
     * Delete Property By Post ID
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int Post ID
     * 
     * @return bool
     */
    private function deleteProperty( $postId ){

        if ( is_numeric( $postId ) && $postId > 0 ){

            $this->deletePropertyAttachments( $postId );

            return !empty( wp_delete_post( $postId , true ) ) ;
            
        }

        return false;

    }

    /**
     * Delete Property By IDX Identity
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int Property IDX Identity Field value
     * 
     * @return bool
     */
    private function deletePropertyByIdxIdentity( $IdxIdentityField ){

        if ( is_numeric( $IdxIdentityField ) && $IdxIdentityField > 0 ){

            return $this->deleteProperty( $this->getPropertyIdByIdxIdentity() ) ;
            
        }

        return false;

    }

    /**
     * Delete All Meta of a specefic Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int Post ID
     * 
     * @return bool
     */
    private function deletePropertyMetas( $postId ){

        if (    is_numeric( $postId ) && 
                $postId > 0 && 
                is_array( $this->customFields ) && 
                !empty( $this->customFields ) 
            )
        {
            //remove all related attachemnts
            $this->deletePropertyAttachments( $postId );

            $taxonomies = array();

            //remove all custom metaposts
            foreach( $this->customFields as $field ){

                if ( !empty( $field['slug'] ) && $field['type'] == 'taxonomy' && \taxonomy_exists( $field['slug'] ) ){
                    
                    $taxonomies[] = $field['slug'];

                }else {
                    
                    if ( !in_array( $field['slug'] , array( self::THEME_FIELD_PREFIX . 'featured' ) ) ){
						
						delete_metadata( self::PRODUCT_POST_TYPE , $postId , $field['slug'] );
						
					}

                }

            }

            //remove all taxonomies related to the post
            if ( !empty( $taxonomies ) && is_array( $taxonomies ) ){

                wp_delete_object_term_relationships( $postId, $taxonomies );

            }

            return true;

        }

        return false;

    }

    /**
     * Count Current Available Listings tha Imported via MLS Sync
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return int
     */
    Public function countCurrentImportedListings()
    {

        global $wpdb;

        $totals = 0;

        if ( !empty( $wpdb ) ){

            $totalsQuery =  "SELECT count(1) FROM `" . $wpdb->prefix . "postmeta` 
                                    WHERE `meta_key` = '_realtyna_idx_item' 
                            ";

            $totals = $wpdb->get_var( $totalsQuery );

        }

        return $totals;

    }

    /**
     * Count Total Imported Listings
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return int
     */
    public function countTotalImportedListings()
    {
        
        $totals = get_option( self::REALTYNA_IDX_META_MARK . '_total_imported' ) ?? 0;

        if ( empty( $totals ) || $totals <= 0  ){

            $totals = $this->countCurrentImportedListings();

            update_option( self::REALTYNA_IDX_META_MARK . '_total_imported' , $totals );

        }

        return $totals;

    }
    
    /**
	 * Count Total Available Listings
	 *
	 * @return int
	 */
	public function countAvailableListings()
	{

		global $wpdb;

		$totals = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM $wpdb->posts WHERE post_type = '%s' AND post_parent = 0 AND post_status IN ( 'publish' , 'pending' , 'draft' )", self::PRODUCT_POST_TYPE ) );

		return $totals;

	}

	/**
	 * Count Today Imported Listings
	 *
	 * @return int
	 */
	public function countTodayImportedListings()
	{

		global $wpdb;

		$totals = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM $wpdb->posts WHERE post_type = '%s' AND post_parent = 0 AND post_date > DATE_SUB(CURDATE(), INTERVAL 1 DAY)", self::PRODUCT_POST_TYPE ) );

		return $totals;

	}

	/**
	 * Make sure post id exists
	 *
	 * @param $postId
	 * @return bool
	 */
	private function postIdExists( $postId )
	{

		global $wpdb;

		$totals = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM $wpdb->posts WHERE `post_status` In ( 'publish' , 'draft' , 'pending' ) AND `ID` = %s ", $postId ) );
		$result = ( (int) $totals > 0 );

		return $result;

	}

    /**
     * Insert Metas For Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int Post ID
     * @param bool Property is Demo or no? default is False
     * 
     * @return void
     */
    private function insertPropertyMetas( $postId , $demo = false )
    {

        $idxIdentityFieldValue = $this->getValue( self::IDX_IDENTITY_FIELD );
		update_post_meta( $postId , self::IDX_IDENTITY_FIELD . '_' . $idxIdentityFieldValue , 1 );

        update_post_meta( $postId , self::REALTYNA_IDX_META_MARK , 1 );
        update_post_meta( $postId , self::REALTYNA_IDX_META_MARK . '_time' , time() );
        update_post_meta( $postId , self::REALTYNA_IDX_META_MARK . '_provider' , $this->mlsProvider );
        if ( $demo )
            update_post_meta( $postId , self::REALTYNA_IDX_META_MARK . "_demo" , 1 );

        $this->fieldsDependencyChecker();        

        foreach ($this->customFields as $key => $value) {
            
            if ( $value['isMainField'] ) continue;

			if ( in_array( $value['slug'] , array( self::THEME_FIELD_PREFIX . 'featured' ) ) ){
				$metaValue = get_option( $value['slug'] );
				if ( $metaValue ) continue;
			}
            
            switch ( $value['type'] ) {

                case 'fieldBuilder' :

                    $this->insertFieldsBuilder( $value['slug'] , $postId );

                    break;

                case 'taxonomy' :

                    $this->insertPropertyTaxonomyField( $value['slug'] , $postId );

                    break;

                case 'commaFieldset' :

                    $this->postMeta( $postId , $value['slug'] , $this->getValueCommaFieldset( $value['slug'] ) );

                    break;

                case 'attachmentList' :

                    $this->insertPropertyAttachmentListField( $value['slug'] , $postId );

                    break;

                case 'postType' :

					$postTypeValue = $this->getValuePostType( $value['slug'] ) ;
					
					if ( $postTypeValue )
						$this->postMeta( $postId , $value['slug'] , $postTypeValue  , true , true , false );

                    break;

                case 'fieldset' :

                    $this->postMeta( $postId , $value['slug'] , $this->getValueFieldset( $value['slug'] ) );

                    break;

                case 'image' :
                    
                    $imgId = false;

                    if (isset( $this->importOptions['use_external_thumbnail'] ) &&
                        $this->importOptions['use_external_thumbnail'] ) {
                        
                        $imgId = $this->attachImageWithoutDownloadToMedia( $this->getValue( $value['slug'] ) , $postId );

                    }else{

                        $imgId = $this->downloadToMedia( $this->getValue( $value['slug'] ) , true , $postId );
                        
                    }

                    if ( $imgId !== false )
                        $this->postMeta( $postId , $value['slug'] , $imgId );

                    break;

                case 'list' :

                    if ( $this->validateListField( $value['slug'] ) )
                        $this->postMeta( $postId , $value['slug'] , $this->getValue( $value['slug'] ) , false );

                    break;

                case 'string':
                    
                    $this->postMeta( $postId , $value['slug'] , $this->getValue( $value['slug'] ) );

                    break;

            }            

        }
        //add fave_property_id
        update_post_meta( $postId , self::THEME_FIELD_PREFIX . 'property_id' , $postId );
        
    }

    /**
     * Update Property Data
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int Post Id
     * @param bool Property is demo or no , default is True
     * 
     * @return int|bool Post Id or False on fails
     */
    private function updateProperty( $postId , $demo = true ){

        set_time_limit( 0 );
        
        $result = false;
		
		if ( !empty( $postId ) && is_numeric( $postId ) ){
			
			$postTitle = str_replace( ',,' , ', ' , $this->getValue( 'post_title' ) );
			$postTitle = str_replace( ' Unit#,' , ',' , $postTitle );
			$postTitle = str_replace( '  ' , ' ' , $postTitle );
			$postTitle = str_replace( '  ' , ' ' , $postTitle );
			$postSlug = $this->getValue( 'post_name' ) ?? str_replace("," , " " , $postTitle );

			$arrayPost = array(
				'ID' => $postId,
				'post_content' => nl2br( $this->getValue( 'post_content' ) ),
				'post_name'    => $postSlug ,
				'post_title'   => $postTitle,
				'post_type'    => self::PRODUCT_POST_TYPE,
				'post_status'  => $this->getValue( 'post_status' ),
				'post_excerpt' => $this->getValue( 'post_excerpt' )
			);

			$updateResult = wp_update_post( $arrayPost , true );

			if ( is_wp_error( $updateResult ) ){
				
				error_log("Update WP Error:" . var_export( $updateResult->get_error_message() , true ) );
				
			}elseif( empty( $updateResult ) ){
				
				error_log("Update Error:" . var_export( $updateResult , true ) );
				
			}elseif( is_numeric( $updateResult ) ){

				$result = true;
				//remove old metas from the property
				$this->deletePropertyMetas( $postId );
				
				$this->insertPropertyMetas( $postId , $demo );

				$this->importedProperty++;
			
			}
		
		}

        return $result;

    }

    /**
     * Insert Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param bool Property is demo or no ,  default value is True
     * 
     * @return int|bool Propert ID or False on fails
     */
    private function insertProperty( $demo = true )
    {

        set_time_limit( 0 );
        
		$result = false;

		$images = $this->getValue( self::THEME_FIELD_PREFIX . 'property_images' );
		
		if ( empty( $images ) && defined('MLS_SYNC_SKIP_LISTINGS_WITHOUT_IMAGE') && !empty( MLS_SYNC_SKIP_LISTINGS_WITHOUT_IMAGE )  ){
			//skip listing to import again with images
			return true;
			
		}
        
        $postTitle = str_replace( ',,' , ', ' , $this->getValue( 'post_title' ) );
		$postTitle = str_replace( ' Unit#,' , ',' , $postTitle );
		$postTitle = str_replace( '  ' , ' ' , $postTitle );
		$postTitle = str_replace( '  ' , ' ' , $postTitle );		
        $postSlug = $this->getValue( 'post_name' ) ?? str_replace("," , " " , $postTitle );
        $postAuthor = $this->importOptions['post_author'] ?? 0;

        $arrayPost = array(
            'post_content' => nl2br( $this->getValue( 'post_content' ) ),
            'post_name'    => $postSlug ,
            'post_title'   => $postTitle ,
            'post_type'    => self::PRODUCT_POST_TYPE,
            'post_status'  => $this->getValue( 'post_status' ),
            'post_excerpt' => $this->getValue( 'post_excerpt' ),
            'post_author'  => $postAuthor
        );

        $postId = wp_insert_post( $arrayPost );


		if ( is_wp_error( $postId ) ){
				
			error_log("Insert WP Error:" . var_export( $postId->get_error_message() , true ) );
				
		}elseif( empty( $postId ) ){
				
			error_log("Insert Error:" . var_export( $postId , true ) );
				
		}elseif( is_numeric( $postId ) ){

			$result = $postId;
				
			$this->insertPropertyMetas( $postId , $demo );

			$this->importedProperty++;
			
		}

        return $result;

    }

    /**
     * Import Slug Values as Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array SlugValues Array
     * @param bool Import demo or no, default value is True
     * 
     * @return bool
     */
    public function import( $slugValues , $demo = true )
    {

        $importResult = false;
        
        if ( $this->mapValues( $slugValues ) !== false){
			
			if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'WP_DEBUG_MLS_SYNC' ) ){
				error_log("Mapped Data:" . var_export( $this->customFields , true ) );
			}

            if ( $this->propertyExists() ){

                $importResult = ( $this->updateProperty( $this->getPropertyIdByIdxIdentity() , $demo ) !== false );

                if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'WP_DEBUG_MLS_SYNC' ) ){

                    error_log( 'import -> Exists -> update : ' . $importResult );
                        
                }

            }else{
    
                $importResult = ( $this->insertProperty( $demo ) !== false );

                if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'WP_DEBUG_MLS_SYNC' ) ){

                    error_log( 'import -> Not Exists -> Insert : ' . $importResult );
                    
                }

            }
    
        }
        
        return $importResult;

    }

    
}