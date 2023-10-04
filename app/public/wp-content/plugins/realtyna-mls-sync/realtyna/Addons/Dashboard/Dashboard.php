<?php

namespace Realtyna\Sync\Addons\Dashboard;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Handle communication with Realtyna MLS Sync Dashboard
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Dashboard
{

    /** @var string dashboard API ENDPOINT */
    const MLS_SYNC_DASHBOARD_ENDPOINT = "https://sync.realtyna.com/api/v2/clients/";

    /** @var array keep response array  */
    private $response = '';

    /** @var string user token */
    private $token;

    /** @var int registered user id in realtyfeed */
    private $userID;

    /**
     * Class Constructor
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $token default value is empty
     * @param string $userID default user id is zero
     * 
     * @return void
     */
    public function __construct( $token = '' , $userID = 0 )
    {

        $this->userID = $userID;
        $this->token = $token;

    }

    /**
     * Get Client Status in realtyfeed side
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @see RealtynaIdxApi->getStatus();
     * 
     * @return bool
     */
    private function getClientStatus()
    {

        if ( class_exists('RealtynaIdxApi') ){

            $api = new RealtynaIdxApi();
    
            $statusResult = $api->getStatus( $this->token , $this->userID );

            return ( $statusResult['status'] == "OK" );
    
        }
    
        return false;

    }

    /**
     * Get Current Plugin Version
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @see RealtynaMlsSync::getPluginDetails();
     * 
     * @return float version number
     */
    private function getPluginVersion()
    {

        $pluginVersion = 0;

        if ( Realtyna\Sync\Core\App::class ){

            $pluginInfo = \Realtyna\Sync\Core\App::getPluginDetails();
            
            $pluginVersion = $pluginInfo['Version'] ?? 0; 

        }
        
        return $pluginVersion;

    }

    /**
     * Get response of current request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array|null
     */
    private function getResponse()
    {

        return $this->response;

    }

    /**
     * Serialize an array values and encrypt it in sha1 algorithm
     * 
     * @author chris A <chris.a@realtyna.net>
     * 
     * @param array $arrayParams array of data to serialize and encrypt
     * 
     * @return string encrypted string with sha1 algorithm
     */
    private function serializedArrayHash( $arrayParams )
    {
        
        return sha1( serialize( $arrayParams ) ) ;

    }

    /**
     * Add Authorization data to request Header
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array $arrayParams array of data that used to generate authorization token
     * 
     * @return array request header array
     */
    private function apiHeaders( $arrayParams )
    {
       
        $headers = [];

        if ( !empty( $arrayParams ) ){
            
            $hash = $this->serializedArrayHash( $arrayParams );

            $headers = [
                "Authorization" => "Token {$hash}"
            ];
    
        }

        return $headers;

    }

    /**
     * Generate Request to dashboard API
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $action action string
     * @param array $arrayParams key value array data to send in request
     * @param array $arrayHeaders request header array
     *      default is an empty array
     * 
     * @return bool
     */
    private function apiRequest( $action , $arrayParams , $arrayHeaders = [] )
    {
        
        if ( function_exists('wp_remote_post') ){

            $apiEndpoint = self::MLS_SYNC_DASHBOARD_ENDPOINT . $action ;

            $this->response = wp_remote_post( $apiEndpoint, [
                'timeout' => 60,
                'headers' => $arrayHeaders,
                'body' => $arrayParams
            ] );
    
            if ( !is_wp_error( $this->getResponse() ) ){
    
                return ( wp_remote_retrieve_response_code( $this->getResponse() ) == 200 );
    
            }
    
        }

        return false;

    }

    /**
     * Generate apiRequest including autorization header
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $action action string 
     * @param array $arrayParams key value array data to send in request
     * 
     * @return bool
     */
    private function request( $action , $arrayParams )
    {
        
        return $this->apiRequest( $action , $arrayParams , $this->apiHeaders( $arrayParams ) );

    }

    /**
     * Send Request to Dashboard Endpoint
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $action action string 
     * @param array $additionalParams key value array data to send in request
     *      default is an empty array
     * 
     * @return bool
     */
    private function sendRequest( $action , $additionalParams = [] )
    {
        
        if ( !empty( $action ) && function_exists('get_option') ){

            $requestParams = $additionalParams;
            $requestParams['request_action'] = $action;
            $requestParams['request_time'] = time();
            $requestParams['request_token'] = $this->token ?? '';
            $requestParams['request_plugin_ver'] = $this->getPluginVersion();
            $requestParams['site_url'] = get_option('siteurl') ?? 'none';
            $requestParams['site_title'] = get_option('blogname') ?? 'none';
            $requestParams['site_theme'] = get_option('template') ?? 'none';
            $requestParams['houzez_purchase_code'] =  get_option('houzez_purchase_code') ?? 'none';
            $requestParams['houzez_activation_status'] = get_option('houzez_activation') ?? 'none';

            return $this->request( $action , $requestParams );
    
        }

        return false;
        
    }

    /**
     * Send Signal to dashboard
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array $signalParams key value array data to send as signal data
     *      default is an empty array
     * 
     * @return bool
     */
    private function sendSignal( $signalParams = [] )
    {
        
        if ( !empty( $signalParams ) ){

            return $this->sendRequest( 'signal' ,$signalParams );

        }

        return false;

    }

    /**
     * Send activation signal to dashboard
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    public function activationSignal()
    {
        return $this->sendSignal( [ 'signal' => 'plugin_activated'] );
    }

    /**
     * Send deactivation signal to dashboard
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    public function deactivationSignal()
    {
        return $this->sendSignal( [ 'signal' => 'plugin_deactivated'] );
    }

    /**
     * Send status signal to dashboard
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    public function statusSignal()
    {

        $status = $this->getClientStatus() ? 'active' : 'pending';

        return $this->sendSignal( [ 'signal' => "status_{$status}" ] );

    }

    /**
     * Set scheduler to run statusSignal method in twicedaily basis
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    public function setStatusSignalScheduler()
    {

        if ( Realtyna\Sync\Addons\Scheduler\Scheduler::class ){

            $scheduler = new \Realtyna\Sync\Addons\Scheduler\Scheduler( __CLASS__ );

            return $scheduler->schedule( __METHOD__ , [ __CLASS__ , 'statusSignal' ] , 'twicedaily' );

        }

        return false;

    }

}
?>