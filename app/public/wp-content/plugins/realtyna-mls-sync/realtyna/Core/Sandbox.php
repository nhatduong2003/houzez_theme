<?php

namespace Realtyna\Sync\Core;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Sandbox API Handler
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Sandbox
{

    /** @var string sandbox api pattern url */
    const SANDBOX_API = 'https://idx.realtyfeed.com/api/mls/sandbox/{*}/search?status=Active';

    /** @var string sandbox test token */
    const SANDBOX_TOKEN = 'ead605f1928a7a388e47ea7ebb711e800c39522b';

    /** @var string sandbox provider slug */
    private $provider = '';

    /** @var array|null storage for sandbox results  */
    private $results = null;

    /** @var int sandbox page size */
    private $pageSize = 0;

    /** @var int sandbox total page count */
    private $pageCount = 0;

    /** @var int sandbox count */
    private $count = 0;

    /** @var string|null sandbox current page url  */
    private $currentPage = null;

    /** @var string|null sandbox next page url */
    private $nextPage = null;

    /** @var string|null sandbox previous page url */
    private $prevPage = null;

    /**
     * Class Constructor Method
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function __construct( $provider )
    {

        $this->provider = $provider;

        $this->setNextPage( $this->getProviderApi() );

    }

    /**
     * Preload All Data from pages of current request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function fetchWithPreloadData()
    {

        while ( !empty( $this->nextPage ) ){

            $this->setCurrentPage( $this->nextPage );

            $this->load( $this->currentPage , true );

        }        

    }

    /**
     * Fetch Data
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    public function fetch()
    {

        return $this->next();
        
    }

    /**
     * Load Next Page of Data
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    public function next()
    {

        if ( !empty( $this->nextPage ) ){

            $this->setCurrentPage( $this->nextPage );

            return $this->current();

        }

        return false;
    
    }

    /**
     * Load Previous Page of Data
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    public function previous()
    {

        if ( !empty( $this->prevPage ) ){
            
            $this->setCurrentPage( $this->prevPage );

            return $this->current();

        }

        return false;

    }

    /**
     * Load Previous Page of Data
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    public function current()
    {

        if ( !empty( $this->currentPage ) ){

            return $this->load( $this->currentPage );

        }

        return false;            

    }

    /**
     * Set URL as Current Page
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string current page url
     * 
     * @return void
     */
    private function setCurrentPage( $apiUrl )
    {

        $this->currentPage = $apiUrl;

    }

    /**
     * Set URL as Next Page
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string next page url
     * 
     * @return void
     */
    private function setNextPage( $apiUrl )
    {

        $this->nextPage = $apiUrl;

    }

    /**
     * Generate API Using Pattern and Provider
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string|null
     */
    private function getProviderApi()
    {

        if ( !empty( $this->provider ) )
            return str_replace( "{*}" , $this->provider , self::SANDBOX_API );

        return null;

    }

    /**
     * Load Result from API
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string API URL
     * @param bool Preload all data of current request or no
     * 
     * @return bool
     */
    private function load( $apiUrl , $preloadData = false )
    {
        
        $response = $this->apiRequest( $apiUrl );

        if ( !empty( $response ) ){

            $this->pageSize = $response['page_size'];
            
            $this->pageCount = $response['page_count'];
            
            $this->count = $response['count'];
            
            $this->nextPage = $response['next'];
            
            $this->prevPage = $response['previous'];
            
            if ( $preloadData ){

                if ( !empty( $this->results ) ){

                    $this->results = array_merge( $this->results , $response['results'] );

                }else{
                    
                    $this->results = $response['results'];

                }

            }else {
                $this->results = $response['results'];
            }            

            return ( is_array( $this->results ) && !empty( $this->results ) ) ;

        }

        return false;

    }

    /**
     * Get pageSize Property Value
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int
     */
    public function getPageSize()
    {
        
        return $this->pageSize;

    }

    /**
     * Get pageCount Property Value
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int
     */
    public function getPageCount()
    {
        
        return $this->pageCount;

    }

    /**
     * Get count Property Value
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int
     */
    public function getCount()
    {
        
        return $this->count;

    }

    /**
     * Get result Property Value
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array|null
     */
    public function getResults()
    {
        return $this->results;

    }
    
    /**
     * Send API Request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string API Url
     * 
     * @return array|null
     */
    private function apiRequest( $apiUrl )
    {

        $request = curl_init( $apiUrl );

        curl_setopt( $request, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Token " . self::SANDBOX_TOKEN,
        ));

        curl_setopt( $request, CURLOPT_RETURNTRANSFER, true);
        
        $responseBody = curl_exec( $request );
        $responseStatus = curl_getinfo( $request, CURLINFO_HTTP_CODE);

        curl_close( $request );
                
        return ( $responseStatus == 200 ) ? json_decode( $responseBody , true ) : null;

    }

}