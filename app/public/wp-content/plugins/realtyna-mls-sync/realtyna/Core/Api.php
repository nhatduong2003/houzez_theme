<?php

namespace Realtyna\Sync\Core;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Communicate with RealtyFeed API
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Api
{

    /** @var string API Server URL */
    const API_SERVER = 'https://idx.realtyfeed.com/';

    /**
     * Register User in RealtyFeed
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array Needed info for Regiseter User
     * 
     * @return array 
     */
    public function register( $params )
    {

        $response = array( "status" => "ERROR" , "message" => __("Invalid Params!" , REALTYNA_MLS_SYNC_SLUG ) );

        if ( is_array( $params ) && !empty( $params ) ){

            $apiEndpoint = self::API_SERVER . 'api/create-user/';
            
            $params['second_email'] = $params['email'];
            $params['domain'] = $this->getDomain() ;

            $request = wp_remote_post( $apiEndpoint, array(
                'timeout' => 60,
                'body' => $params
            ));

            if ( !is_wp_error( $request ) ){

                $statusCode = wp_remote_retrieve_response_code( $request );

                if ($statusCode != 201) {

                    $response['message'] = __("Invalid Response!" , REALTYNA_MLS_SYNC_SLUG ) ;

                }else{

                    $response['status'] = "OK";
                    $response['message'] = array_merge( $params , json_decode( $request['body'] , true ) );

                }
        
            }else{
                $response['message'] = __("Request Error!" , REALTYNA_MLS_SYNC_SLUG ) ;
            }    

        }
        
        return $response;
    }

    /**
     * Get Providers
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Token
     * 
     * @return array
     */
    public function getProviders( $token )
    {

        $response = array( "status" => "ERROR" , "message" => __("Invalid Token!" , REALTYNA_MLS_SYNC_SLUG ) );

        if ( !empty( $token ) ){

            $apiEndpoint = self::API_SERVER . 'api/providers/';
            
            $cacheNextUpdate = get_option( REALTYNA_MLS_SYNC_SLUG . "-CACHE-NEXT-UPDATE" , '' );
			
			$responseBody = '';
			
			if ( !empty( $cacheNextUpdate ) &&  time() < $cacheNextUpdate ){
				
				$responseBody = get_option( REALTYNA_MLS_SYNC_SLUG . "-PROVIDERS" , '' );
				
			}
            
            if ( empty( $responseBody ) ){

                $request = wp_remote_get( $apiEndpoint, array(
                    'timeout' => 60,
                    'headers' => array(
                        'Authorization' => 'Token ' . $token
                    )
                ));
        
                if ( !is_wp_error( $request ) ){
        
                    $statusCode = wp_remote_retrieve_response_code( $request );
        
                    if ($statusCode != 200) {
        
                        $response['message'] = __("Invalid Response!" , REALTYNA_MLS_SYNC_SLUG ) ;
        
                    }else{
        
                        $responseBody = $request['body'];

                        update_option( REALTYNA_MLS_SYNC_SLUG . "-PROVIDERS" , $responseBody );

                        $response['status'] = "OK";
                        $response['message'] = json_decode( $responseBody , true );
        
                    }
                
                }else{
                    $response['message'] = __("Invalid Request!" , REALTYNA_MLS_SYNC_SLUG ) ;
                }    
    
            }else{
                
                $response['status'] = "OK";
                $response['message'] = json_decode( $responseBody , true );

            }

    
        }
        
        return $response;

    }

    /**
     * Get Provider Mapping data from Local Json file
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Provider short name
     * @param string selected product for mapping
     * 
     * @return string|array
     */
    private function getLocalProviderMapping( $provider, $product )
    {
		
		$content = '';
		
		$localFile = __DIR__ . DIRECTORY_SEPARATOR . "mappings" . DIRECTORY_SEPARATOR . "{$provider}.{$product}.json";
		
		if ( file_exists( $localFile ) ){
			
			$content = file_get_contents( $localFile );
			
		}
		
		return $content;
	}

    /**
     * Get Provider Mapping data from API
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Token
     * @param string Provider short name
     * @param string selected product for mapping
     * 
     * @return array
     */
    private function getProviderMapping( $token, $provider, $product )
    {

        $response = array( "status" => "ERROR" , "message" => __("Invalid Token!" , REALTYNA_MLS_SYNC_SLUG ) );

        if ( !empty( $token ) ){

            $apiEndpoint = self::API_SERVER . "api/mapping/{$provider}/{$product}";
            
			$cacheNextUpdate = get_option( REALTYNA_MLS_SYNC_SLUG . "-CACHE-NEXT-UPDATE" , '' );
			
			$responseBody = $this->getLocalProviderMapping( $provider , $product );
			
			if ( empty( $responseBody ) ){
				
				if ( !empty( $cacheNextUpdate ) &&  time() < $cacheNextUpdate ){
					
					$responseBody = get_option( REALTYNA_MLS_SYNC_SLUG . "-" . $provider . "-" . $product , '' );
					
				}
					
			}
            
            if ( empty( $responseBody ) ){

                $request = wp_remote_get( $apiEndpoint, array(
                    'timeout' => 60,
                    'headers' => array(
                        'Authorization' => 'Token ' . $token
                    )
                ));
        
                if ( !is_wp_error( $request ) ){
        
                    $statusCode = wp_remote_retrieve_response_code( $request );
        
                    if ($statusCode != 200) {
        
                        $response['message'] = __("Invalid Response!" , REALTYNA_MLS_SYNC_SLUG ) ;
        
                    }else{

                        $responseBody = $request['body'];

                        update_option( REALTYNA_MLS_SYNC_SLUG . "-" . $provider . "-" . $product , $responseBody );
                        update_option( REALTYNA_MLS_SYNC_SLUG . "-CACHE-NEXT-UPDATE" , strtotime("+1 day") );
        
                        $response['status'] = "OK";
                        $response['message'] = $responseBody;
        
                    }
                
                }else{
                    $response['message'] = __("Invalid Request!" , REALTYNA_MLS_SYNC_SLUG ) . " : " . $request->get_error_message();
                }    
    
            }else{

                $response['status'] = "OK";
                $response['message'] = $responseBody;

            }
    
        }
        
        return $response;

    }

    /**
     * Get Mapping Structure of selected Provider
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Token
     * @param string Provider short name
     * @param string selected product for mapping
     * 
     * @return string Mapping structure in Json encoded string
     */
    public function getMapping( $token, $provider, $product )
    {

        if ( !empty( $token ) && !empty( $provider ) && !empty( $product ) ){

            $providerMapping = $this->getProviderMapping( $token, $provider, $product );
        
            if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'WP_DEBUG_MLS_SYNC' ) ){
				error_log( "Mapping : " . var_export( $providerMapping , true ) );
			}
			
            if ( is_array( $providerMapping ) && isset( $providerMapping['message'] ) && ( $providerMapping['status'] == "OK"  ) )
                return $providerMapping['message'];
    
        }

        return '';

    }

    /**
     * Request For Custom Provider
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Token
     * @param int User id of registered User in realtyfeed
     * @param string Custom Provider Name
     * @param string Custom Provider State
     * @param string|null Domain URL
     * 
     * @return array
     */
    public function requestProvider( $token , $userId , $provider , $state , $domain = null )
    {

        $response = array( "status" => "ERROR" , "message" => __("Invalid Params!" , REALTYNA_MLS_SYNC_SLUG ) );

        if ( !empty( $token ) && !empty( $userId ) && !empty( $provider ) && !empty( $state ) ){

            $apiEndpoint = self::API_SERVER . 'api/request-provider/';

            $fields = array();
            $fields['user_id'] = $userId;
            $fields['provider'] = $provider;
            $fields['state'] = $state;
            $fields['domain'] = ( !empty( $domain ) ? $domain : $this->getDomain() );

            
            $request = wp_remote_post( $apiEndpoint, array(
                'timeout' => 60,
                'headers' => array(
                    'Authorization' => 'Token ' . $token
                ),
                'body' => $fields
            ));
    
            if ( !is_wp_error( $request ) ){
    
                $statusCode = wp_remote_retrieve_response_code( $request );
    
                if ($statusCode != 201) {
    
                    $response['message'] = __("Invalid Response!" , REALTYNA_MLS_SYNC_SLUG ) ;
    
                }else{
    
                    $responseBody = json_decode( $request['body'] , true );
                    $response['status'] = ( isset( $responseBody['message'] ) && $responseBody['message'] == 'success' ) ? "OK" : "ERROR";
                    $response['message'] = ( isset( $responseBody['message'] ) ? $responseBody['message'] : '' );
    
                }
            
            }else{
                $response['message'] = __("Invalid Request!" , REALTYNA_MLS_SYNC_SLUG ) ;
            }    
    
        }
        
        return $response;


    }

    /**
     * Get User Status
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Token
     * @param int Registered User ID in RealtyFeed
     * 
     * @return array
     */
    public function getStatus( $token , $userId )
    {

        $response = array( "status" => "ERROR" , "message" => __("Invalid Token!" , REALTYNA_MLS_SYNC_SLUG ) );

        if ( !empty( $token ) && !empty( $userId ) && is_numeric( $userId ) ){

            $apiEndpoint = self::API_SERVER . 'api/check-status/' . $userId ;
            
            $request = wp_remote_get( $apiEndpoint, array(
                'timeout' => 60,
                'headers' => array(
                    'Authorization' => 'Token ' . $token
                )
            ));
    
            if ( !is_wp_error( $request ) ){
    
                $statusCode = wp_remote_retrieve_response_code( $request );
    
                if ($statusCode != 200) {
    
                    $response['message'] = __("Invalid Response!" , REALTYNA_MLS_SYNC_SLUG ) ;
    
                }else{
    
                    $responseBody = json_decode( $request['body'] , true );

                    $response['message'] = $responseBody['status'];

                    if ( $responseBody['status'] == 'Active' ){

                        $response['status'] = "OK";

                    }
    
                }
            
            }else{
                $response['message'] = __("Invalid Request!" , REALTYNA_MLS_SYNC_SLUG ) ;
            }    
    
        }
        
        return $response;

    }

    /**
     * Get Chosen Provider
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Token
     * @param int Registered User ID in RealtyFeed
     * 
     * @return array
     */
    public function getChosenProvider( $token , $userId  )
    {

        $response = array( "status" => "ERROR" , "message" => __("Invalid Params!" , REALTYNA_MLS_SYNC_SLUG ) );

        if ( !empty( $token ) && !empty( $userId ) && is_numeric( $userId ) ){

            $apiEndpoint = self::API_SERVER . 'api/selected-provider/';
            
            $request = wp_remote_post( $apiEndpoint, array(
                'timeout' => 60,
                'headers' => array(
                    'Authorization' => 'Token ' . $token
                ),
                'body' => array( 
                    "user_id" => $userId
                )
            ));
    
            if ( !is_wp_error( $request ) ){
    
                $statusCode = wp_remote_retrieve_response_code( $request );
    
                if ($statusCode != 200) {
    
                    $response['message'] = __("Invalid Response!" , REALTYNA_MLS_SYNC_SLUG ) ;
    
                }else{
    
                    $responseBody = json_decode( $request['body'] , true );
                    $response['status'] = "OK";
                    $response['message'] = ( isset( $responseBody['message'] ) ? $responseBody['message'] : '' );
    
                }
            
            }else{
                $response['message'] = __("Invalid Request!" , REALTYNA_MLS_SYNC_SLUG ) ;
            }    
    
        }
        
        return $response;


    }

    /**
     * Generate stripe checkout on API-Side
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $token
     * @param int $userID
     * @param int $providerID provider id to proceed payment
     * 
     * @return array response array
     */
    public function checkout( $token , $userID , $providerID )
    {

        $response = array( "status" => "ERROR" , "message" => __("Invalid Params!" , REALTYNA_MLS_SYNC_SLUG ) );

        if ( !empty( $token ) && !empty( $userID ) && !empty( $providerID ) ){

            $apiEndpoint = self::API_SERVER . 'api/payment-checkout/';
            
            $request = wp_remote_post( $apiEndpoint, array(
                'timeout' => 120,
                'headers' => array(
                    'Authorization' => 'Token ' . $token
                ),
                'body' => array( 
                    "user_id" => $userID,
                    "provider_id" => $providerID,
                    "callback" => base64_encode( $this->getDomain() )
                )
            ));
    
            if ( !is_wp_error( $request ) ){
    
                $statusCode = wp_remote_retrieve_response_code( $request );
    
                if ($statusCode != 200) {
    
                    $response['message'] = __("Invalid Response!" , REALTYNA_MLS_SYNC_SLUG ) ;
    
                }else{
    
                    $responseBody = json_decode( $request['body'] , true );

                    if ( !empty( $responseBody['checkout_url'] ) ){

                        $response['status'] = "OK";
                        $response['message'] = $responseBody['checkout_url'];
    
                    }else{

                        $response['message'] = __("Invalid Checkout Response!" , REALTYNA_MLS_SYNC_SLUG ) ;
                        error_log("payment issue: " . var_export($responseBody , true) );
                    }
    
                }
            
            }else{
                $response['message'] = __("Invalid Request!" , REALTYNA_MLS_SYNC_SLUG ) ;
            }    
    
        }
        
        return $response;

    }

    /**
     * Get Payment Status
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Token
     * @param int Regietered User Id
     * 
     * @return array
     */
    public function getPayment( $token , $userId )
    {

        $response = array( "status" => "ERROR" , "message" => __("Invalid Token!" , REALTYNA_MLS_SYNC_SLUG ) );

        if ( !empty( $token ) && !empty( $userId ) && is_numeric( $userId ) ){

            $apiEndpoint = self::API_SERVER . 'api/check-payment/' . $userId ;
            
            $request = wp_remote_get( $apiEndpoint, array(
                'timeout' => 60,
                'headers' => array(
                    'Authorization' => 'Token ' . $token
                )
            ));
    
            if ( !is_wp_error( $request ) ){
    
                $statusCode = wp_remote_retrieve_response_code( $request );
    
                if ($statusCode != 200) {
    
                    $response['message'] = __("Invalid Response!" , REALTYNA_MLS_SYNC_SLUG ) ;
    
                }else{
    
                    $responseBody = json_decode( $request['response'] , true );
                    $response['status'] = "OK";
                    $response['message'] = $responseBody['message'];
    
                }
            
            }else{
                $response['message'] = __("Invalid Request!" , REALTYNA_MLS_SYNC_SLUG ) ;
            }    
    
        }
        
        return $response;

    }

    /**
     * Cancel Payment Subscriptions
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string token
     * @param int Registered User Id in RealtyFeed
     * 
     * @return array
     */
    public function cancelPayment( $token , $userId )
    {

        $response = array( "status" => "ERROR" , "message" => __("Invalid Token!" , REALTYNA_MLS_SYNC_SLUG ) );

        if ( !empty( $token ) && !empty( $userId ) && is_numeric( $userId ) ){

            $apiEndpoint = self::API_SERVER . 'api/payment/cancel/' . $userId ;
            
            $request = wp_remote_get( $apiEndpoint, array(
                'timeout' => 60,
                'headers' => array(
                    'Authorization' => 'Token ' . $token
                )
            ));
    
            if ( !is_wp_error( $request ) ){
    
                $statusCode = wp_remote_retrieve_response_code( $request );
    
                if ($statusCode != 200) {
    
                    $response['message'] = __("Invalid Response!" , REALTYNA_MLS_SYNC_SLUG ) ;
    
                }else{
    
                    $responseBody = json_decode( $request['body'] , true );
                    $response['status'] = "OK";
                    $response['message'] = ( $responseBody['message'] ) ? $responseBody['message'] : $responseBody ;
    
                }
            
            }else{
                $response['message'] = __("Invalid Request!" , REALTYNA_MLS_SYNC_SLUG ) ;
            }    
    
        }
        
        return $response;

    }

    /**
     * Get Current Domain URL
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string
     */
    private function getDomain()
    {

        if ( function_exists('site_url') ){
            return site_url();
        }

        return isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];

    }

}