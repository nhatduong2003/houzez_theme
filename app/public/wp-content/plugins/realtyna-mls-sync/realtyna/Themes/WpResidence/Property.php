<?php

namespace Realtyna\Sync\Themes\WpResidence;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Handle WpResidence Property Post Type
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Property {

    /** @var string custom post type for Houzez Properties */
    const THEME_POST_TYPE = 'estate_property';

    /** @var string houzez field prefix */
    const THEME_FIELD_PREFIX = '';

    /** @var string idx meta mark */
    const REALTYNA_IDX_META_MARK = '_realtyna_idx_item';

    /** @var string property field  */
    const IDX_IDENTITY_FIELD = '_realtyna_mls_key';

    /** @var property status field */
    const IDX_STATUS_FIELD = 'property_status';

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

        $this->addField( self::THEME_FIELD_PREFIX . 'property_price', 'Sale or Rent Price', 'string', null, 'Only digits, example: 557000' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_label_before', 'Before Price label', 'string', null, 'Example: Start From' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_label', 'After Price label', 'string', null, 'Example: Per Month' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_size', 'Area Size', 'string', null, 'Only digits, example: 2500' );     
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_lot_size', 'Land Area', 'string', null, 'Only digits, example: 2500' );
             
        $this->addField( self::THEME_FIELD_PREFIX . 'property_bedrooms', 'Bedrooms', 'string', null, 'Example: 4' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_bathrooms', 'Bathrooms', 'string', null, 'Example: 2' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_rooms', 'Rooms', 'string', null, 'Example: 2' );
        
          
        $this->addField( self::THEME_FIELD_PREFIX . 'owner_notes', 'Private Note', 'string', null, 'Example: 1' );
        
          
        $this->addField( self::THEME_FIELD_PREFIX . 'embed_virtual_tour', '360° Virtual Tour', 'string', null, 'Enter virtual tour embeded code or iframe' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_agency', 'Agency', 'postType' , array( 
            'callBackClass' => '\Realtyna\Sync\Themes\WpResidence\Agency',
            'callBackMethod' => 'insert'
        ), 'Enter agency id. Example: 333');
        
        $this->addField( self::THEME_FIELD_PREFIX . 'agents', 'Agent', 'postType' , array( 
            'callBackClass' => '\Realtyna\Sync\Themes\WpResidence\Agent',
            'callBackMethod' => 'insert'
        ), 'Enter agent id. Example: 333');

        $this->addField( self::THEME_FIELD_PREFIX . 'property_address', 'Address(*only street name and building no)', 'string', null, '' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_zip', 'Zip/Postcode', 'string', null, '' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_country', 'Country', 'string', null, 'Provide country short name. Example US for United States, CA for Canada etc' );
  
        $this->addField( self::THEME_FIELD_PREFIX . 'embed_video_id', 'Virtual Tour Video URL', 'string', null, 'Provide virtual tour video URL. YouTube, Vimeo, SWF File and MOV File are supported.' );

        $this->addField( self::THEME_FIELD_PREFIX . 'property_custom_video', 'Virtual Video Tour Image', 'image', null, 'Will be displayed as a place holder. Required for the video to be displayed. Minimum width of 818px and minimum height 417px. Larger sizes will be cropped.' );
        
        $this->addField( self::THEME_FIELD_PREFIX . 'property_images', 'Images Gallery', 'attachmentList', null, "separate each value with a '|'" );
        $this->addField( self::THEME_FIELD_PREFIX . 'attachments', 'Property Attachments', 'attachmentList', null, "separate each value with a '|'" );

        $this->addField( 'property_latitude', 'Property Latitude', 'string', null, '' );

        $this->addField( 'property_longitude', 'Property Longitude', 'string', null, '' );

        $this->addField( '_thumbnail_id', 'Featured Image', 'image', null, 'image that will be placed as property featured image' );

        //Taxonomies
        $this->addField( 'property_category', 'Property Category', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\WpResidence\PropertyType',
            'callBackMethod' => 'import'
        ) , "separate each value with a '|'" );

        $this->addField( 'property_action_category', 'Property Type', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\WpResidence\PropertyActionType',
            'callBackMethod' => 'import'
        ) , "separate each value with a '|'" );
              
        $this->addField( 'property_status', 'Property Status', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\WpResidence\PropertyStatus',
            'callBackMethod' => 'import'
        ) , "separate each value with a '|'" );

        $this->addField( 'property_features', 'Property Features', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\WpResidence\PropertyFeature',
            'callBackMethod' => 'import'
        ) , "separate each value with a '|'" );
   
        $this->addField( 'property_county_state', 'Property County/State', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\WpResidence\PropertyState',
            'callBackMethod' => 'import',
            'metaKey' => '_wpresidence_property_state'
        ) , "separate each value with a '|'" );
        
        $this->addField( 'property_city', 'Property City', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\WpResidence\PropertyCity',
            'callBackMethod' => 'import',
            'metaKey' => '_wpresidence_property_city'
        ) , "separate each value with a '|'" );

        $this->addField( 'property_area', 'Property Area', 'taxonomy', array(
            'callBackClass' => '\Realtyna\Sync\Themes\WpResidence\PropertyArea',
            'callBackMethod' => 'import',
            'metaKey' => '_wpresidence_property_area',
            'parentKey' => 'parent_city',
            'parentValue' => 'property_city'
        ) , "separate each value with a '|'" );
        
       // Additional Features
        $this->addField( self::THEME_FIELD_PREFIX . 'additional_features_enable', 'Show additional details', 'list', array(
            'disable' => 'Disable',
            'enable' => 'Enable'
        ) , '' , false , 'disable' );

        $this->addField( 'additional_features' , 'additional details items' , 'fieldset' , array(
            self::THEME_FIELD_PREFIX . 'additional_feature_title',
            self::THEME_FIELD_PREFIX . 'additional_feature_value'
         ) , 'Content of this field will be driven from another fields');

        $this->addField( self::THEME_FIELD_PREFIX . 'wpresidence_custom_fields_title', 'Titles', 'wpresidence_custom_fields', null, "separate each value with a '|'" );

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

                if ( !empty( $class ) && !empty( $method ) )

                    if ( method_exists( $class , $method ) ){

                        return call_user_func( $class . '::' . $method ,  $this->getValue( $fieldSlug ) );
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

                            $parentInfo = array();

                            if ( $this->setTaxonomyParentValue( $fieldSlug ) ){

                                $parentInfo = $this->customFields[ $fieldSlug ]['enumValues'] ;
                                
                            }
    
                            if ( call_user_func( array( $classObj , $method ) ,  $value , $postId , $parentInfo ) )
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
            'post_type'   => self::THEME_POST_TYPE,
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
                    'property_listings'     => array( 'width' => 525, 'height' => 328, 'file' => $filename , 'mime-type' => $imgMimeType),
                    'property_full'         => array( 'width' => 980, 'height' => 777, 'file' => $filename , 'mime-type' => $imgMimeType), 
                    'property_featured' => array( 'width' => 940, 'height' => 390, 'file' => $filename , 'mime-type' => $imgMimeType), 
                    'property_full_map' => array( 'width' => 1920, 'height' => 790, 'file' => $filename , 'mime-type' => $imgMimeType), 
                    'widget_thumb' => array( 'width' => 105, 'height' => 70, 'file' => $filename , 'mime-type' => $imgMimeType), 
                    'custom_slider_thumb' => array( 'width' => 36, 'height' => 36, 'file' => $filename , 'mime-type' => $imgMimeType), 
                );
                
                $attachmentId = wp_insert_attachment( $attachment );
    
                if ( $attachmentId !== 0 || !is_wp_error( $attachmentId ) ) {
    
                    wp_update_attachment_metadata( $attachmentId, $attachmentMetadata );
                    
                    $externalImagesMark = ( class_exists('RealtynaMlsSync') ) ? \Realtyna\Sync\Core\App::getExternalImagesMark() : '_REALTYNA_MLS_SYNC_EXTERNAL_IMAGE';

                    update_post_meta( $attachmentId, $externalImagesMark, 1 );
                                            
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
    private function downloadToMedia( $url , $generateThumbnails = true , $postId = null ){

        if ( !empty( $url ) ){

            $uploadDir = wp_upload_dir();

            $context = stream_context_create( array(
                'http' => array( 
                    'timeout' => 300, 
                    'header' => 'Connection: close\r\n' 
                    ) 
                ) 
            );
                    
            $fileData = file_get_contents( $url ,false ,$context );
            
            if ( $fileData !== false ){

                $filename = basename( $url );
            
                if ( wp_mkdir_p( $uploadDir['path'] ) ) 
        
                    $file = $uploadDir['path'] . '/' . $filename;
                
                else
                    $file = $uploadDir['basedir'] . '/' . $filename;
                
                file_put_contents( $file, $fileData );

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
        
                    }
            
                    return $attachId;
        
                }    
    
            }
            
        }

        return false;
        
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

        $virtualTour = self::THEME_FIELD_PREFIX . 'embed_virtual_tour';
    
        if (!empty(  $this->customFields[ $virtualTour ]['idxMappedTo'] ) &&
            is_array( $this->customFields[ $virtualTour ]['idxMappedTo'] ) &&
            isset( $this->customFields[ $virtualTour ]['idxMappedTo']['value'] )){
                
            $virtualTourValue = $this->customFields[ $virtualTour ]['idxMappedTo']['value'] ;

            /*
            if ( $this->isHttps() ){
                
                $virtualTourValue = $this->forceHttps( $virtualTourValue );

            }
            */

            if ( substr( strtolower( $virtualTourValue ) , 0 , 4 ) == 'http' ){
                $this->customFields[ $virtualTour ]['idxMappedTo']['value'] =    '<iframe  width="853" height="480" src="' . $this->checkForYoutubeLink( $virtualTourValue ) . '" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';

            }

        }elseif ( ! empty(  $this->customFields[ $virtualTour ]['defaultText'] ) ){
                
            $virtualTourValue = $this->customFields[ $virtualTour ]['defaultText'] ;

            if ( substr( strtolower( $virtualTourValue ) , 0 , 4 ) == 'http' ){
                $this->customFields[ $virtualTour ]['defaultText'] =    '<iframe  width="853" height="480" src="' . $this->checkForYoutubeLink( $virtualTourValue ) . '" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';
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

            $propertyType = $this->getValue('property_category');

            if ( array_key_exists( $propertyType , $propertyTypes ) ){

                $this->setValue( 'property_category' , $propertyTypes[ $propertyType ] );

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

        if ( empty( $this->getValueFieldset( 'additional_features' ) ) )
            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'additional_features_enable' , 'disable'  );
        else {
            $this->removeEmptyAdditionalFeatures();
        }
          
        $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'property_map' , '1'  );

        if ( !empty( $this->getValue( self::THEME_FIELD_PREFIX . 'property_images' ) ) ){
            
            if ( empty( $this->getValue( '_thumbnail_id' ) ) ){
    
                $gallery_images = $this->getValue( self::THEME_FIELD_PREFIX . 'property_images' );
    
                $gallery_images_array = explode( '|' , $gallery_images );
    
                $this->setDefaultValue( '_thumbnail_id' , $gallery_images_array[0]  );
                    
            }
                
        }

        if ( empty( $this->getValue( '_thumbnail_id' ) ) ){

            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'featured' , '0'  );

        }

        if ( empty( $this->getValue( self::THEME_FIELD_PREFIX . 'agents' ) ) ){

            $this->setDefaultValue( self::THEME_FIELD_PREFIX . 'agent_display_option' , 'none'  );            

        }
            
        if ( !empty( $this->getValue( self::THEME_FIELD_PREFIX . 'embed_virtual_tour' )  ) ){
            
            $this->addIframeToVirtualTour();

        }
       
        if ( $this->getValue( self::IDX_STATUS_FIELD ) == 'A' ){
            
            $this->setValue( self::IDX_STATUS_FIELD , 'Active' );

        }

        $bathRooms = $this->getValue( self::THEME_FIELD_PREFIX . 'property_bathrooms' );
        
        if ( \is_numeric( $bathRooms ) ){

            $this->setValue( self::THEME_FIELD_PREFIX . 'property_bathrooms' , floor( $bathRooms ) );

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
    private function postMeta( $postId , $slug , $value , $voidEmpty = true , $update = true){

        if ( $voidEmpty && empty( $value ) )
            return false;

        if ( $update )
            return update_post_meta( $postId , $slug , $value );
        else
            return add_post_meta( $postId , $slug , $value );

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

        $meta = array();

        $metaKey = $demoOnly ? self::REALTYNA_IDX_META_MARK . "_demo" : self::REALTYNA_IDX_META_MARK ;

        $meta[] = array( "key" => $metaKey , "value" => 1 , "compare" => "=" );

        $deleteArgs = array(
            'numberposts' => -1,
            'posts_per_page' => -1,
            'post_type'   => self::THEME_POST_TYPE,
            'meta_query' => $meta
        );
        
        $selectedPosts = new \WP_Query( $deleteArgs );

        if ( $selectedPosts->have_posts() ) {

            while ( $selectedPosts->have_posts() ){

                $selectedPosts->the_post();

                $this->deletePropertyAttachments( get_the_ID() );
           
                wp_delete_post( get_the_ID() );

            }

            wp_reset_postdata(); 

        }                

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
     * @return void 
     */
    public function removeUnwantedProperties( $excludedProperties ){

        if ( isset( $excludedProperties['listing_ids'] ) && !empty( $excludedProperties['listing_ids'] ) && is_array( $excludedProperties['listing_ids'] ) ){
            
			global $wpdb;
			
            $sql = "DELETE FROM $wpdb->posts WHERE `ID` IN ( SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key` = %s AND `meta_value` NOT IN (" . implode( "," , $excludedProperties['listing_ids'] ) . ") ) LIMIT " . self::REALTYNA_REMOVE_RECORDS_PER_REQUEST;
    
            $removedPosts = $wpdb->query( $wpdb->prepare( $sql , self::IDX_IDENTITY_FIELD ) );

            if ( $removedPosts > 0 ){

                $sql = "DELETE FROM $wpdb->postmeta WHERE `post_id` NOT IN ( SELECT `ID` FROM $wpdb->posts ) ) LIMIT " . self::REALTYNA_REMOVE_RECORDS_PER_REQUEST;
				    
                $removedPostMeta = $wpdb->query( $wpdb->prepare( $sql , self::IDX_IDENTITY_FIELD ) );
    
            }

            return $removedPosts;
/*
            $meta = array();
            $meta[] = array( "key" => self::REALTYNA_IDX_META_MARK , "value" => 1 , "compare" => "=" );
            $meta[] = array( "key" => self::IDX_IDENTITY_FIELD , "value" => $excludedProperties , "compare" => "NOT IN" );
            
            $deleteArgs = array(
                'numberposts' => self::REALTYNA_REMOVE_RECORDS_PER_REQUEST,
                'post_type'   => self::THEME_POST_TYPE,
                'meta_query' => $meta
            );
            
            $selectedPosts = new \WP_Query( $deleteArgs );
    
            if ( $selectedPosts->have_posts() ) {
    
                while ( $selectedPosts->have_posts() ){
    
                    $selectedPosts->the_post();
    
                    $this->deletePropertyAttachments( get_the_ID() );
               
                    wp_delete_post( get_the_ID() );
    
                }                
    
            }

            wp_reset_postdata(); 
*/    
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

            // if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ){

            //     error_log( 'isAllowedProperty -> ' . self::IDX_STATUS_FIELD . ' : ' . $idxStatusFieldValue );
                
            // }
    
            return ( !empty( $idxStatusFieldValue ) && in_array( $idxStatusFieldValue , $this->allowedPropertyStatus ) );

        }

        if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ){

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

        if ( !empty( $idxIdentityFieldValue ) && is_numeric( $idxIdentityFieldValue ) ){
			
			global $wpdb;
			
			if ( !empty( $wpdb ) ){

				$totalsQuery =  "SELECT count(1) FROM `" . $wpdb->prefix . "postmeta` 
										WHERE	`meta_key` = '" . self::IDX_IDENTITY_FIELD . "' AND
												`meta_value` = '" . $idxIdentityFieldValue . "'
								";

				$exists = ( $wpdb->get_var( $totalsQuery ) > 0 );

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

            $query =  "SELECT `post_id` FROM `" . $wpdb->prefix . "postmeta` 
                                    WHERE `meta_key` = '" . self::IDX_IDENTITY_FIELD . "' AND `meta_value` = '" . $idxIdentityFieldValue . "'
                            ";

            $postId = $wpdb->get_var( $query );

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
    private function deletePropertyAttachments( $postId ){

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

                if ( $field['type'] == 'taxonomy' ){
                    
                    $taxonomies[] = $field['slug'];

                }else {
                    
                    delete_metadata( self::THEME_POST_TYPE , $postId , $field['slug'] );

                }

            }

            //remove all taxonomies related to the post
            if ( !empty( $taxonomies ) ){

                wp_delete_object_term_relationships( $postId, $taxonomies );

            }

            return true;

        }

        return false;

    }

    /**
     * Save custom fields on WpResidence
     * 
     * @author Cretu Remus
     *
     * @param int $postId
     * @param string $custom_field_names
     * @param string $custom_fields_values
     * @return void
     */
    public function wpresidence_custom_field_save($postId , $custom_field_names, $custom_fields_values ){
      
        $custom_field_names_array   = explode('|', $custom_field_names);
        $custom_fields_values_array = explode('|',$custom_fields_values);
        
		if ( count ( $custom_field_names_array ) == count ( $custom_fields_values_array ) ){
		
			$all_options = get_option('wpresidence_admin','');
			
			if( is_array($custom_field_names_array) && !empty($custom_field_names_array) ){
				
				$place=99;
				
				foreach ($custom_field_names_array as $key=>$name){
					
					if ( !empty ( $custom_fields_values_array[ $key ] ) ) {
					
						$field_name=$name;
						$field_type="string";
						$slug = sanitize_title(str_replace(' ', '_', $field_name));
						
						$place++;
						
						if( !in_array($name,$all_options['wpestate_custom_fields_list']['add_field_name']) ){
							$all_options['wpestate_custom_fields_list']['add_field_name'][]=$name; // name
							$all_options['wpestate_custom_fields_list']['add_field_label'][]=$name; // Label
							$all_options['wpestate_custom_fields_list']['add_field_order'][]=$place; // place
							$all_options['wpestate_custom_fields_list']['add_field_type'][]='short text'; // type
							$all_options['wpestate_custom_fields_list']['add_dropdown_order'][]=''; // value
						}

						update_post_meta($postId , $slug,  $custom_fields_values_array[$key]);
					
					}else{
						
						error_log("Err: $name skipped due to empty value!");
						
					}
			
				}
				
				update_option('wpresidence_admin',$all_options);
				
			}
		
		}else{
			
			error_log('Err: custom field names did not match with values ');
			error_log('Err: custom field names: ' . var_export( $custom_field_names_array , true ) );
			error_log('Err: custom field values: ' . var_export( $custom_fields_values_array , true ) );
			
		}       
     
    }

    /**
     * Update property postmetas after import
     * 
     * @author Cretu Remus
     *
     * @param int $property_id
     * @return void
     */
    private function update_after_import( $postId )
    {
        update_post_meta( $postId, 'prop_featured', 0 );
        update_post_meta( $postId, 'page_custom_zoom', 16 );
        update_post_meta( $postId, 'property_page_desing_local','' );
        update_post_meta( $postId, 'header_transparent','global' );
        update_post_meta( $postId, 'page_show_adv_search','global' );
        update_post_meta( $postId, 'header_type', 0 );
        update_post_meta( $postId, 'sidebar_agent_option', 'global' );
        update_post_meta( $postId, 'local_pgpr_slider_type', 'global' );
        update_post_meta( $postId, 'local_pgpr_content_type', 'global' );
        update_post_meta( $postId, 'sidebar_select', 'global' );
        update_post_meta( $postId, 'sidebar_option', 'global' );

        if ( function_exists('wpestate_update_hiddent_address_single' ) ){

            wpestate_update_hiddent_address_single( $postId );

        }

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
     * Insert Metas For Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int Post ID
     * @param bool Property is Demo or no? default is False
     * 
     * @return void
     */
    private function insertPropertyMetas( $postId , $demo = false ){

        update_post_meta( $postId , self::REALTYNA_IDX_META_MARK , 1 );
        update_post_meta( $postId , self::REALTYNA_IDX_META_MARK . '_time' , time() );
        update_post_meta( $postId , self::REALTYNA_IDX_META_MARK . '_provider' , $this->mlsProvider );
        if ( $demo )
            update_post_meta( $postId , self::REALTYNA_IDX_META_MARK . "_demo" , 1 );

        $this->fieldsDependencyChecker();        

        foreach ($this->customFields as $key => $value) {
            
            if ( $value['isMainField'] ) continue;
            
            switch ( $value['type'] ) {

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

                    $this->postMeta( $postId , $value['slug'] , $this->getValuePostType( $value['slug'] ) );

                    break;

                case 'fieldset' :

                    $this->postMeta( $postId , $value['slug'] , $this->getValueFieldset( $value['slug'] ) );

                    break;

                case 'image' :
                    
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

                case 'wpresidence_custom_fields':
                    
                    $custom_field_names = $this->getValue( $value['slug'] );
                    $custom_fields_values   =   $this->customFields[ 'wpresidence_custom_fields_title_values' ]['idxMappedTo']['value']; 
                
                    $this->wpresidence_custom_field_save( $postId , $custom_field_names, $custom_fields_values );

                    break;
    

            }            

        }
        
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
			$postSlug = $this->getValue( 'post_name' ) ?? str_replace("," , " " , $postTitle );

			$arrayPost = array(
				'ID' => $postId,
				'post_content' => nl2br( $this->getValue( 'post_content' ) ),
				'post_name'    => $postSlug ,
				'post_title'   => $postTitle,
				'post_type'    => self::THEME_POST_TYPE,
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
    private function insertProperty( $demo = true ){

        set_time_limit( 0 );

		$result = false;
        
        $postTitle = str_replace( ',,' , ', ' , $this->getValue( 'post_title' ) );
        $postSlug = $this->getValue( 'post_name' ) ?? str_replace("," , " " , $postTitle );
        $postAuthor = $this->importOptions['post_author'] ?? 0;

        $arrayPost = array(
            'post_content' => nl2br( $this->getValue( 'post_content' ) ),
            'post_name'    => $postSlug ,
            'post_title'   => $postTitle ,
            'post_type'    => self::THEME_POST_TYPE,
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
    public function import( $slugValues , $demo = true ){

        $importResult = false;
        
        if ( $this->mapValues( $slugValues ) !== false){

            if ( $this->propertyExists() ){

                $importResult = ( $this->updateProperty( $this->getPropertyIdByIdxIdentity() , $demo ) !== false );

                if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ){

                    error_log( 'import -> Exists -> update : ' . $importResult );
                        
                }

            }else{
    
                $importResult = ( $this->insertProperty( $demo ) !== false );

                if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ){

                    error_log( 'import -> Not Exists -> Insert : ' . $importResult );
                    
                }

            }
    
        }

        return $importResult;

    }

    
}