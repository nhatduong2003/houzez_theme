<?php

namespace Realtyna\Sync\Core;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * REST API Handle for MLS Sync
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class REST
{

    /** @var string Endpoint Address */
    const ENDPOINT = 'idx_api/v1';

    /** @var string Token Holder */
    private $token;

    /** @var string Provider Holder */
    private $provider;

    /** @var array array of Additional Fields */
    private $additionalFields;

    /** @var array array of import options */
    private $importOptions;

    /** @var object Handle Target Product Object*/
    protected $targetProduct = null;

    /**
     * Class Constructor Method
     * 
     * @param string|null Token , Default is Null
     * @param string|null Provider , Defualt is Null
     * @param array|null Additional Fields Data , Defualt is Null
     * @param array|null Import options array , Defualt is Null
     * 
     * @return void
     */
    public function __construct( $token = null , $provider = null, $additionalFields = null , $importOptions = null )
    {

        $this->token = $token;
        $this->provider = $provider;
        $this->additionalFields = $additionalFields;
        $this->importOptions = $importOptions;

        $app = App::getInstance( false );

        if ( !$app->getTargetProduct() ){
            
            $app->upgradeLegacyFeatures();

        }

        $app->createTargetProductInstance();

        $this->targetProduct = $app->getTargetProduct();

        add_action('rest_api_init', array ( $this , 'realtynaIdxRestInit' ) );

    }

    /**
     * Initialize and Register REST Routes For Wordpress
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function realtynaIdxRestInit()
    {

        register_rest_route( 
            self::ENDPOINT , 
            'import/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'POST',
                'callback' => array( $this , 'import') ,
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route( 
            self::ENDPOINT , 
            'import_json/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'POST',
                'callback' => array( $this , 'importJson') ,
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route( 
            self::ENDPOINT , 
            'purge_demo/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'DELETE',
                'callback' => array( $this , 'purgeDemo') ,
                'permission_callback' => '__return_true',
            )
        );
        
        register_rest_route( 
            self::ENDPOINT , 
            'purge/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'POST',
                'callback' => array( $this , 'purge') ,
                'permission_callback' => '__return_true',
            )
        );
        
        register_rest_route( 
            self::ENDPOINT , 
            'purge_all/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'DELETE',
                'callback' => array( $this , 'purge_all') ,
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route( 
            self::ENDPOINT , 
            'force_purge/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'DELETE',
                'callback' => array( $this , 'force_purge') ,
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route( 
            self::ENDPOINT , 
            'reset_demo/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'DELETE',
                'callback' => array( $this , 'resetDemo') ,
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route( 
            self::ENDPOINT , 
            'reset_client/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'DELETE',
                'callback' => array( $this , 'resetClient') ,
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route( 
            self::ENDPOINT , 
            'product_details/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'GET',
                'callback' => array( $this , 'productDetails') ,
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route( 
            self::ENDPOINT , 
            'product_update/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'GET',
                'callback' => array( $this , 'productUpdate') ,
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route( 
            self::ENDPOINT , 
            'clear_cache/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'GET',
                'callback' => array( $this , 'clearApiCache') ,
                'permission_callback' => '__return_true',
            )
        );

        register_rest_route( 
            self::ENDPOINT , 
            'reset_mls/(?P<token>[a-zA-Z0-9-]+)', 
            array(
                'methods' => 'DELETE',
                'callback' => array( $this , 'resetMLSData') ,
                'permission_callback' => '__return_true',
            )
        );

        
    }

	/**
     * run purge function to remove attachements
     * 
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return void
     */
    public function purgeCron()
	{
		
		if ( $this->targetProduct && is_object( $this->targetProduct ) ){
			
			if ( \method_exists( $this->targetProduct , 'purgeAttachments' ) ){
				$this->targetProduct->purgeAttachments();
				
				wp_send_json_success( array(
					'message' => __( 'done!' , REALTYNA_MLS_SYNC_SLUG )
					),
					201
				);
				
			}
			
		}
		
	}

    /**
     * Import Property handler for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return void
     */
    public function import( $request )
    {
                
        $this->requestAuthentication( $request );
        $this->requestValidation( $request );

        $result = false;

        $mapper = new Mapper( $this->token , $this->provider , $this->additionalFields , $this->importOptions );
        $result = $mapper->importProperty( $request->get_json_params() );

        if ( $result ){

            $this->setUpdateTime();
            $this->incImportedListings( 1 );

            wp_send_json_success( array(
                'message' => __( 'Property Imported!' , REALTYNA_MLS_SYNC_SLUG )
                ),
                201
            );
    
        }else{

            wp_send_json_error( array(
                'message' => __('There was an error. Please contact administrator' ,  REALTYNA_MLS_SYNC_SLUG )
                ), 
                500
            );

        }

    }

    /**
     * Import Property using JSON handler for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return void
     */
    public function importJson( $request )
    {
        
        $this->requestAuthentication( $request );

        $result = $this->importJsonFile( $request );

        if ( $result > 0 ){

            $this->setUpdateTime();
            $this->incImportedListings( $result );

            wp_send_json_success( array(
                    'message' => sprintf( __( 'Total %d Properties Imported!' , REALTYNA_MLS_SYNC_SLUG ) , $result )
                ),
                201
            );
    
        }else{

            wp_send_json_error( array(
                    'message' => __( 'No property Found' , REALTYNA_MLS_SYNC_SLUG )
                ),
                400
            );

        }

    }

    /**
     * Reset Demo Properties handler for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return void
     */
    public function productUpdate( $request )
    {
        
        $this->requestAuthentication( $request );

        if ( App::class ){

            if ( App::updatePlugin() ){

                wp_send_json_success( array(
                    'message' => __("Plugin updated Successfully!" , REALTYNA_MLS_SYNC_SLUG )
                    )
                );
    
            }

        }

        wp_send_json_error( array(
            'message' => __( 'Internal Error!' , REALTYNA_MLS_SYNC_SLUG )
            ), 
            400
        );

    }

    /**
     * Reset Demo Properties handler for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return void
     */
    public function productDetails( $request )
    {
        
        $this->requestAuthentication( $request );

        if ( App::class ){

            $details = App::getPluginDetails();
            if ( is_array( $details ) ){

                wp_send_json_success( array(
                    'message' => $details
                    )
                );
    
            }

        }

        wp_send_json_error( array(
            'message' => __( 'Internal Error!' , REALTYNA_MLS_SYNC_SLUG )
            ), 
            400
        );

    }

    /**
     * Reset Demo Properties handler for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return void
     */
    public function resetDemo( $request )
    {
        
        $this->requestAuthentication( $request );

        if ( App::class ){

            if ( App::deleteIdxImport() ){

                wp_send_json_success( array(
                    'message' => __( 'Demo Import has been reset!' , REALTYNA_MLS_SYNC_SLUG )
                    )
                );
    
            }

        }

        wp_send_json_error( array(
            'message' => __( 'Internal Error!' , REALTYNA_MLS_SYNC_SLUG )
            ), 
            400
        );

    }
 
    /**
     * Reset Client Data for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return void
     */
    public function resetClient( $request )
    {
        
        $this->requestAuthentication( $request );

        if ( App::class ){

            if ( App::resetClient() ){

                wp_send_json_success( array(
                    'message' => __( 'Client data has been reset!' , REALTYNA_MLS_SYNC_SLUG )
                    )
                );
    
            }

        }

        wp_send_json_error( array(
            'message' => __( 'Internal Error!' , REALTYNA_MLS_SYNC_SLUG )
            ), 
            400
        );


    }

    /**
     * Reset MLS Data for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return void
     */
    public function resetMLSData( $request )
    {
        
        $this->requestAuthentication( $request );

        if ( App::class ){

            if ( App::resetMlsData() ){

                wp_send_json_success( array(
                    'message' => __( 'MLS data has been reset!' , REALTYNA_MLS_SYNC_SLUG )
                    )
                );
    
            }

        }

        wp_send_json_error( array(
            'message' => __( 'Internal Error!' , REALTYNA_MLS_SYNC_SLUG )
            ), 
            400
        );


    }

    /**
     * Purge Unwanted Properties handler for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * @param bool purge only demo properties , default is false
     * 
     * @return void
     */
    public function purge( $request , $demoOnly = false ){
        
        $this->requestAuthentication( $request );

        $this->requestValidation( $request );

        $errorMSG = __( 'Internal Error!' , REALTYNA_MLS_SYNC_SLUG );

        if ( $this->targetProduct && \method_exists( $this->targetProduct , 'property' )  ){

            $property = $this->targetProduct->property();

            if ( \method_exists( $property , 'removeUnwantedProperties' ) ){

                $removedRecords = $property->removeUnwantedProperties( $request->get_json_params() );

                if ( $removedRecords > 0 ){
                    $this->setUpdateTime();
                }

                wp_send_json_success( array(
                    'message' => __( $removedRecords . ' Properties Purged!' , REALTYNA_MLS_SYNC_SLUG )
                    )
                );
    
            }else{
                $errorMSG = __( 'Purge function issue' , REALTYNA_MLS_SYNC_SLUG );
            }

        }else{
            $errorMSG = __( 'Target Product issue detected.' , REALTYNA_MLS_SYNC_SLUG );
        }

        wp_send_json_error( array(
            'message' => $errorMSG
            ), 
            400
        );

    }

    /**
     * Purge All Properties handler for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * @param bool purge only demo properties , default is false
     * 
     * @return void
     */
    public function purge_all( $request , $demoOnly = false )
    {
    
        $this->requestAuthentication( $request );

        $errorMSG = __( 'Internal Error!' , REALTYNA_MLS_SYNC_SLUG );

        if ( $this->targetProduct && \method_exists( $this->targetProduct , 'property' )  ){

            $property = $this->targetProduct->property();

            if ( \method_exists( $property , 'bulkRemoveProperties' ) ){

                $property->bulkRemoveProperties( $demoOnly );

                wp_send_json_success( array(
                    'message' => __( 'Properties Purged!' , REALTYNA_MLS_SYNC_SLUG )
                    )
                );
    
            }else{
                $errorMSG = __( 'Purge All function issue' , REALTYNA_MLS_SYNC_SLUG );
            }

        }else{
            $errorMSG = __( 'Target Product issue detected.' , REALTYNA_MLS_SYNC_SLUG );
        }

        wp_send_json_error( array(
            'message' => $errorMSG
            ), 
            400
        );

    }

    /**
     * Force Purge All Properties handler for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * @param bool purge only demo properties , default is false
     * 
     * @return void
     */
    public function force_purge( $request){
        
        $this->requestAuthentication( $request );

        $errorMSG = __( 'Internal Error!' , REALTYNA_MLS_SYNC_SLUG );

        if ( $this->targetProduct  && \method_exists( $this->targetProduct , 'property' ) ){

            $property = $this->targetProduct->property();

            if ( \method_exists( $property , 'forcePurge' ) ){

                $property->forcePurge();

                wp_send_json_success( array(
                    'message' => __( 'Properties Purged!' , REALTYNA_MLS_SYNC_SLUG )
                    )
                );
    
            }else{
                $errorMSG = __( 'Force Purge function issue' , REALTYNA_MLS_SYNC_SLUG );
            }

        }else{
            $errorMSG = __( 'Target Product issue detected.' , REALTYNA_MLS_SYNC_SLUG );
        }

        wp_send_json_error( array(
            'message' => $errorMSG
            ), 
            400
        );

    }


    /**
     * Purge Demo Properties handler for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return void
     */
    public function purgeDemo( $request )
    {
        
        $this->purge( $request , true );

    }

    /**
     * Clear API Cache
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return void
     */
    public function clearApiCache( $request )
    {

        $errorMSG = __( 'Internal Error!' , REALTYNA_MLS_SYNC_SLUG );

        if ( \function_exists( 'delete_option' ) ){

            delete_option( REALTYNA_MLS_SYNC_SLUG . "-PROVIDERS" );
            delete_option( REALTYNA_MLS_SYNC_SLUG . "_UpdateTime" );
			delete_option( REALTYNA_MLS_SYNC_SLUG . "-CACHE-NEXT-UPDATE" );

            if ( $this->targetProduct  && \method_exists( $this->targetProduct , 'strtolowerCurrentProductName' ) ){
                    
                delete_option( REALTYNA_MLS_SYNC_SLUG . "-" . $this->provider . "-" . $this->targetProduct->strtolowerCurrentProductName() );

            }

            wp_send_json_success( array(
                'message' => __( 'API Cache has been Cleared!' , REALTYNA_MLS_SYNC_SLUG )
                )
            );

        }else{
            $errorMSG = __( 'WP functionality issue detected.' , REALTYNA_MLS_SYNC_SLUG );
        }

        wp_send_json_error( array(
            'message' => $errorMSG
            ), 
            400
        );

    }

    /**
     * Map & import Json File handler for REST
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return int Imported Property
     */
    private function importJsonFile( $request )
    {

        $file = $request->get_file_params();

        $jsonProperties = $this->checkJsonFile( $file );

        $imported = 0;

        $mapper = new Mapper( $this->token, $this->provider , $this->additionalFields , $this->importOptions );

        if ( \method_exists( $mapper , 'importProperty' ) ){
                
            foreach ( $jsonProperties as $property ) {
    
                if ( $mapper->importProperty( $property ) )
                    $imported++;
            
            }
    
        }

        return $imported;

    }

    /**
     * Check Json File
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array File Details array
     * 
     * @return string Json Data
     */
    private function checkJsonFile( $file )
    {

        if ( empty( $file ) || !is_array( $file ) ){

            wp_send_json_error( array(
                    'message' => __( 'You should provide Json file to import' , REALTYNA_MLS_SYNC_SLUG )
                ), 
                400
            );

        }

        $fileInfo = $file['jsonfile'];

        if ( empty( $fileInfo ) || !is_array( $fileInfo ) ) {

            wp_send_json_error( array(
                    'message' => __( 'json file not found' , REALTYNA_MLS_SYNC_SLUG )
                ),
                400
            );

        }

        if ( $fileInfo['type'] !== 'application/json' || $fileInfo['error'] != 0 ) {

            wp_send_json_error( array(
                    'message' => __( 'import file must be json' , REALTYNA_MLS_SYNC_SLUG )
                ),
                400
            );

        }

        $actualFile = $file['jsonfile']['tmp_name'];

        if ( !file_exists( $actualFile ) ) {

            wp_send_json_error( array(
                    'message' => __( 'Error during file upload, please contact administrator' , REALTYNA_MLS_SYNC_SLUG )
                ),
                400
            );

        }

        $fileJsonData = json_decode( file_get_contents( $actualFile ), true );

        if ( is_null( $fileJsonData ) || !is_array( $fileJsonData ) ) {

            wp_send_json_error(array(
                    'message' => __( 'Cannot parse json file for update' , REALTYNA_MLS_SYNC_SLUG )
                ),
                400
            );

        }

        return $fileJsonData;

    }

    /**
     * Request Authentication
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return bool 
     */
    private function requestAuthentication( $request )
    {

        if ( $request instanceOf \WP_REST_Request ){

            $requestParams = $request->get_params();

            if ( !empty( trim( $this->token ) ) && $requestParams['token'] == $this->token )
                
                return true;

        }

        wp_send_json_error( array(
            'message' => __( 'Not Authorized!' , REALTYNA_MLS_SYNC_SLUG )
            ), 
            401
        );

        return false;

    }

    /**
     * Request Validation 
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object instance of \WP_REST_Request
     * 
     * @return bool 
     */
    private function requestValidation( $request )
    {

        if ( !is_array( $request->get_json_params() ) || $request->get_header('content-type') != 'application/json' )
                
            wp_send_json_error( array(
                'message' => __( 'Invalid Request!' , REALTYNA_MLS_SYNC_SLUG )
                ), 
                400
            );

        return true;

    }

    /**
     * Set Latest Update Time for MLS Sync
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return void
     */
    private function setUpdateTime()
    {

        if ( APP::class ){

            if ( \function_exists('update_option') ){

                update_option( APP::REALTYNA_MLS_SYNC_UPDATE_TIME , time() );

            }

        }

    }

    /**
     * Increment total count of Imported Listings
     * @author Chris A <chris.a@realtyna.net>
     *
     * @param integer $incrementalValue
     * @return void
     */
    private function incImportedListings( $incrementalValue = 0 )
    {
        
        if ( $incrementalValue > 0 ){

            if ( $this->targetProduct && \method_exists( $this->targetProduct , 'property' ) ){

                $property = $this->targetProduct->property();

                if ( \method_exists( $property , 'countTotalImportedListings' ) ){
    
                    $totalImportedOptionKey = $property::REALTYNA_IDX_META_MARK . '_total_imported' ;
                    
                    $totals = $property->countTotalImportedListings();
    
                    $totals += $incrementalValue ;
    
                    update_option( $totalImportedOptionKey , $totals );
    
                }
    
            }

        }

    }

}
?>