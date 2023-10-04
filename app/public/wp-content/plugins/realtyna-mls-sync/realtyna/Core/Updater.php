<?php

namespace Realtyna\Sync\Core;

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Realtyna Updater Class
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Updater
{

    /** @var string api endpoint url */
    protected $endpoint = 'https://update.realtyna.com/api/v2';

    /** @var string product slug to check update availability */
    protected $slug;

    /** @var string current product version to check update availability */
    protected $version;

    /** @var array response of update inquiry */
    protected $updateResponse = [];

    /**
     * Constructor Method
     * 
     * @param string slug of product
     * @param string version of product
     */
    public function __construct( $slug , $version )
    {

        $this->slug = $slug;
        $this->version = $version;

    }

    /**
     * Update Inquiry
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array response array
     */
    public function updateInquiry()
    {

        if ( !empty( $this->slug ) && !empty( $this->version ) ){
            
            $path = "/{$this->slug}/{$this->version}";

            $this->updateResponse = $this->request( $path );

            return $this->updateResponse;

        }
        
        return [];

    }

    /**
     * Get Latest version from Response
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string|bool latest availbale version or False on fails
     */
    public function getLastVersion()
    {
        
        return $this->updateResponse['latest'] ?? false;
                
    }

    /**
     * Get Downlaod Link of Latest version from Response
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string|bool downlaod link or False on fails
     */
    public function getDownloadLink()
    {
        
        return $this->updateResponse['url'] ?? false ;

    }

    /**
     * Get Status code from Response
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int|bool status code or False on fails
     */
    public function getStatus()
    {
        
        return $this->updateResponse['status'] ?? false ;

    }

    /**
     * CURL Request to the API
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Request Path of endpoint
     * @param string Request Method , Default Value is GET
     * @param object|array|string|null appended data , default is null
     * 
     * @return array returned response with status code as array
     */
    private function request( $path , $method = "GET" , $data = null )
    {

        $requestUrl = $this->endpoint . $path;

        $request = curl_init();

        switch ( $method )
        {
            case "POST":
                curl_setopt( $request, CURLOPT_POST, 1 );
    
                if ( !empty( $data ) )
                    curl_setopt( $request, CURLOPT_POSTFIELDS, $this->serializeKeyValueArray( $data ) );

                break;

            case "PUT":
                curl_setopt( $request, CURLOPT_PUT, 1 );

                break;

            default:
                if ( !empty( $data ) )
                    $requestUrl = sprintf( "%s?%s", $requestUrl, $this->serializeKeyValueArray( $data ) );
        }
        
        curl_setopt( $request, CURLOPT_URL, $requestUrl );
        curl_setopt( $request, CURLOPT_RETURNTRANSFER, 1 );
    
        $responseBody = curl_exec( $request );
        $responseStatus = curl_getinfo( $request, CURLINFO_HTTP_CODE );
    
        curl_close( $request );
    
        return ( !empty( $responseBody ) && $responseStatus == 200 ) ? json_decode( $responseBody , true ) : [] ;
    
    }

    /**
     * Create Serialized Parameters for CURL Request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param object|array|string|null Tthe Parameters that sent to the api
     * 
     * @return string serialized parameters suitable for use with CURL functions
     */
	private function serializeKeyValueArray( $data )
    {

		return  ( !is_array( $data ) || !is_object( $data) ) ? $data : http_build_query( $data ) ;

	}

}