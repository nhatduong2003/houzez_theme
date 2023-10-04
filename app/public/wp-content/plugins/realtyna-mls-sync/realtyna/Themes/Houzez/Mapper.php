<?php

namespace Realtyna\Sync\Themes\Houzez;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Mapper for Houzez Property
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Mapper {

    /** @var string demo data provider */
    const DEMO_PROVIDER = 'mlg_demo';

    /** @var string client theme */
    const CLIENT_THEME = 'houzez';

    /** @var array array of valid separator */
    private $separators = [ "," , "|" , "-"];

    /** @var string concat operator */
    private $concatOperator = '+';

    /** @var string current separator holder */
    private $currentSeparator = '';

    /** @var string|null current provider holder */
    private $provider = null;

    /** @var string|null token */
    private $token = null;

    /** @var array|null array of slug values */
    private $slugValues  = null;

    /** @var array|null array of data mapper for current provider */
    private $providerMapping = null;

    /** @var array|null array of addational data mapper */
    private $addationMapping = null;

    /** @var array|null options for import process  */
    private $propertyImportOptions = null;

    /**
     * Class Constructor Method
     * 
     * @param string|null default Null
     * @param string|null default Null
     * @param array|null default Null
     * @param array|null default Null
     * 
     * @return void
     */
    public function __construct( $token = null , $provider = null , $addationMapping = null , $propertyImportOptions = null ){

        $this->token = $token;
        $this->provider = $provider;
        $this->addationMapping = $addationMapping;
        $this->propertyImportOptions = $propertyImportOptions;

        $this->setProviderMapping();

    }

    /**
     * Run Mapper
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param bool default Null
     * 
     * @return int total imported Properties
     */
    public function run( $demo = true ){

        return $this->getProviderData( $demo );

    }

    /**
     * Set Mapping Data for Current Provider
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function setProviderMapping(){

        if ( !empty( $this->provider && !empty( $this->token ) ) ){

            $api = new \Realtyna\Sync\Core\Api();
            $mapping = $api->getMapping( $this->token, $this->provider , self::CLIENT_THEME );

            $this->providerMapping = ( !empty( $mapping ) ) ?  json_decode( $mapping , true) : null;
            
        }

    }

    /**
     * Get Mapping of Current Provider
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array|null
     */
    private function getProviderMapping(){

        return $this->providerMapping;

    }

    /**
     * Get Sandbox Data Of current Provider
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param bool determine demo data or no
     * 
     * @return int Total imported Properties
     */
    private function getProviderData( $demo ){

        $importedProperty = 0;

        if ( empty( $this->provider ) || empty( $this->getProviderMapping() ) )
            return $importedProperty;

        $sandbox = new \Realtyna\Sync\Core\Sandbox( $this->provider);

        $sandbox->fetchWithPreloadData();

        foreach( $sandbox->getResults() as $property ){

            if (    isset( $this->propertyImportOptions['max_property_import'] ) &&
                    ( $this->propertyImportOptions['max_property_import'] > 0 ) )
            {
            
                if ( $importedProperty >= $this->propertyImportOptions['max_property_import'] )
                        
                    return $importedProperty;
                    
            }
    
            if ( $this->importProperty( $property , $demo ) )
                $importedProperty++;

        }

        return  $importedProperty;

    }

    /**
     * Import single Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array Property Data
     * @param bool Data are Demo or no
     * 
     * @return bool
     */
    public function importProperty( $property , $demo = false ){

        if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'WP_DEBUG_MLS_SYNC' ) ){

            error_log( 'importProperty > property : ' . var_export( $property , true ) );
            error_log( 'mapper > provider : ' . $this->provider );
            error_log( 'importProperty > getProviderMapping() : ' . var_export( $this->getProviderMapping() , true ) );

        }

        if ( empty( $this->provider ) || empty( $this->getProviderMapping() ) )
            return false;

        $houzez = new Property( true , $this->provider  , $this->propertyImportOptions  );

        return $houzez->import( $this->map( $property ) , $demo ) ;

    }

    /**
     * Map Property Data with Houzez Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array Property Data
     * 
     * @return array|bool SlugValues as array Or false on fails
     */
    private function map( $property ){
        
        if ( !empty( $this->getProviderMapping() ) && is_array( $this->getProviderMapping() ) && !empty( $property) ){

            $this->slugValues = null;
            
            foreach ($this->getProviderMapping() as $key => $value) {
                
                if ( !is_array( $value )  ) continue;

                if ( !empty( $value['extra'] ) ){

                    $this->slugValues[ $key ]['extra'] = $value['extra'];

                }

                if ( !empty( $value['default'] ) ){

                    $this->slugValues[ $key ]['value'] = $value['default'];

                }                  
                elseif ( !empty( $value['mapping'] ) ){

                    $this->slugValues[ $key ]['value'] = $this->propertyMapping( $property , $value['mapping'] );

                }elseif( !empty( $value['conditional'] ) ){
					
					$firstValue = '';
					$secondValue = '';
					$finalValue = '';
					
					if (	
							isset( $value['conditional']['conditionValues'] ) && 
							!empty( $value['conditional']['conditionOperator'] ) && 
							isset( $value['conditional']['falseValue'] )  && 
							isset( $value['conditional']['trueValue'] ) 
						)
					{
						
						$conditionalValues = explode( "," , $value['conditional']['conditionValues']  );
						
						if ( count( $conditionalValues ) == 2 ){
							
							$firstValue = trim( $conditionalValues[0] );
							$secondValue = trim( $conditionalValues[1] );
							
							if ( $firstValue != '' || $secondValue != ''  ){
							
								if ( ( substr( $firstValue , 0 , 7 ) == 'static:' ) ) {
									
									$firstValue = str_replace( 'static:' , '' , $firstValue );
									
								}else{
									
									$firstValue = $this->propertyMapping( $property , $firstValue );
								}
							
								if ( ( substr( $secondValue , 0 , 7 ) == 'static:' ) ) {
									
									$secondValue = str_replace( 'static:' , '' , $secondValue );
									
								}else{
									
									$secondValue = $this->propertyMapping( $property , $secondValue );
								}
								

								if ( $value['conditional']['conditionOperator'] == '==' ){
									
									$conditionResult = ( $firstValue == $secondValue );
									
								}elseif ( $value['conditional']['conditionOperator'] == '===' ){
									
									$conditionResult = ( $firstValue === $secondValue );
									
								}elseif ( $value['conditional']['conditionOperator'] == '!==' ){
									
									$conditionResult = ( $firstValue !== $secondValue );
									
								}elseif ( $value['conditional']['conditionOperator'] == '!=' ){
									
									$conditionResult = ( $firstValue != $secondValue );
									
								}elseif ( $value['conditional']['conditionOperator'] == '<>' ){
									
									$conditionResult = ( $firstValue <> $secondValue );
									
								}elseif ( $value['conditional']['conditionOperator'] == '>=' ){
									
									$conditionResult = ( $firstValue >= $secondValue );
									
								}elseif ( $value['conditional']['conditionOperator'] == '<=' ){
									
									$conditionResult = ( $firstValue <= $secondValue );
									
								}elseif ( $value['conditional']['conditionOperator'] == '>' ){
									
									$conditionResult = ( $firstValue > $secondValue );
									
								}elseif ( $value['conditional']['conditionOperator'] == '<' ){
									
									$conditionResult = ( $firstValue < $secondValue );
									
								}
								
								if ( isset( $conditionResult )  ){
									
									if ( $conditionResult ){
										
										if ( ( substr( $value['conditional']['trueValue'] , 0 , 7 ) == 'static:' ) ) {
											
											$finalValue = str_replace( 'static:' , '' , $value['conditional']['trueValue'] );
											
										}else{
											
											$finalValue = $this->propertyMapping( $property , $value['conditional']['trueValue'] );
										}
										
									}else{
										
										if ( ( substr( $value['conditional']['falseValue'] , 0 , 7 ) == 'static:' ) ) {
											
											$finalValue = str_replace( 'static:' , '' , $value['conditional']['falseValue'] );
											
										}else{
											
											$finalValue = $this->propertyMapping( $property , $value['conditional']['falseValue'] );
										}
										
									}
									
								}

							
							}
							
						}
						
					}

                    $this->slugValues[ $key ]['value'] = $finalValue;

                }
                
            }

            $this->mergeWithAddationMapping();
            
            return  $this->slugValues ;

        }

        return false;

    }

    /**
     * Merge Property Data With custom Addational Data to Houzez Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function mergeWithAddationMapping(){

        if ( !empty( $this->addationMapping ) && is_array( $this->addationMapping ) ){

            foreach ($this->addationMapping as $key => $value) {

                $this->slugValues[ $key ]['value'] = $value;

            }

        }

    }

    /**
     * Concat Fields Mappeing
     * 
     * @author Chirs A <chris.a@realtyna.net>
     * 
     * @param array Property Data
     * @param string Mapping Data
     * 
     * @return string
     */
    private function concatFieldsMapping( $property , $mapping )
    {
        
        $mappings = explode( $this->concatOperator , $mapping );

        $values = array();        
        
        foreach ( $mappings as $value ) {

            $fieldName = trim( $value );
            $mappedValue = '';

            if ( empty( $fieldName ) )
                continue;

            $index = explode( "/" , $fieldName );

            if ( count( $index ) == 1 ){

                if ( isset( $property[ $index[0] ] ) ){

                    if ( !is_array( $property[ $index[0] ] ) )
                        $mappedValue =  $property[ $index[0] ];
                    else {
                        
                        $mappedValue = implode( "|" , $property[ $index[0] ] );
                    }

                }else{
					
					$mappedValue = $fieldName;
					
				}

            }elseif ( count( $index ) == 2 ){

                if ( isset( $property[ $index[0] ][ $index[1] ] ) ){

                    if ( !is_array( $property[ $index[0] ][ $index[1] ]) ){
                        $mappedValue =  $property[ $index[0] ][ $index[1] ];
                    }else{
                        $mappedValue =  implode ( "," , $property[ $index[0] ][ $index[1] ] );
                    }

                }elseif ( isset( $property[ $index[0] ][0][ $index[1] ] ) ){

                    if ( !is_array( $property[ $index[0] ][0][ $index[1] ]) ){
                        $mappedValue =  $property[ $index[0] ][0][ $index[1] ];
                    }else{
                        $mappedValue =  implode ( "," , $property[ $index[0] ][0][ $index[1] ] );
                    }

                }

            }elseif ( count( $index ) == 3 ){

                if ( isset( $property[ $index[0] ][ $index[1] ][ $index[2] ] ) ){

                    if ( !is_array( $property[ $index[0] ][ $index[1] ][ $index[2] ] ) ){
                        $mappedValue =  $property[ $index[0] ][ $index[1] ][ $index[2] ];
                    }else{
                        $mappedValue =  implode ( "," , $property[ $index[0] ][ $index[1] ][ $index[2] ] );
                    }

                }

            }else {

                if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'WP_DEBUG_MLS_SYNC' ) ){

                    error_log( 'Mapper -> concatFieldsMapping -> Error in : ' . $fieldName );
                    
                }

            }
            
            $values[] = str_replace( $fieldName , $mappedValue , $value );

        }

        foreach ($values as $indexKey => $indexValue) {
            
            if ( trim( $values [ $indexKey ] ) == '' )
                unset( $values [ $indexKey ] );

        }
        
        return ( !empty( $values ) ) ? implode( ' ' , $values ) : '';

    }

    /**
     * Get Property Data based on Mapping Pattern
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array Property Data
     * @param string Mapping Data
     * 
     * @return string
     */
    private function propertyMapping( $property , $mapping )
    {
        
        $this->currentSeparator = '';

        $mappings = $this->checkMappingSeparators( $mapping );

        $values = array();        
        
        foreach ( $mappings as $value ) {

            $fieldName = trim( $value );

            if ( empty( $fieldName ) )
                continue;
            
            if ( strpos( $fieldName , $this->concatOperator ) === false ){

                $index = explode( "/" , $fieldName );

                if ( count( $index ) == 1 ){
    
                    if ( isset( $property[ $index[0] ] ) ){
    
                        if ( !is_array( $property[ $index[0] ] ) )
                            $values[] =  $property[ $index[0] ];
                        else {
                            
                            $values[] = implode( "|" , $property[ $index[0] ] );
                        }
    
                    }else {
                        
                        $values[] = $value;// to handle static values
    
                    }
    
                }elseif ( count( $index ) == 2 ){
    
                    if ( isset( $property[ $index[0] ][ $index[1] ] ) ){
    
                        if ( !is_array( $property[ $index[0] ][ $index[1] ]) ){
                            $values[] =  $property[ $index[0] ][ $index[1] ];
                        }else{
                            $values[] =  implode ( "," , $property[ $index[0] ][ $index[1] ] );
                        }
    
                    }elseif ( isset( $property[ $index[0] ][0][ $index[1] ] ) ){
    
                        if ( !is_array( $property[ $index[0] ][0][ $index[1] ]) ){
                            $values[] =  $property[ $index[0] ][0][ $index[1] ];
                        }else{
                            $values[] =  implode ( "," , $property[ $index[0] ][0][ $index[1] ] );
                        }
    
                    }else {
                        
                        $values[] = '';
    
                    }
    
                }elseif ( count( $index ) == 3 ){
    
                    if ( isset( $property[ $index[0] ][ $index[1] ][ $index[2] ] ) ){
    
                        if ( !is_array( $property[ $index[0] ][ $index[1] ][ $index[2] ] ) ){
                            $values[] =  $property[ $index[0] ][ $index[1] ][ $index[2] ];
                        }else{
                            $values[] =  implode ( "," , $property[ $index[0] ][ $index[1] ][ $index[2] ] );
                        }
    
                    }else {
                        $values[] = '';
                    }
    
                }else {
    
                    if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'WP_DEBUG_MLS_SYNC' ) ){
    
                        error_log( 'Mapper -> propertyMapping -> Error in : ' . $fieldName );
                        
                    }
    
                }
                    
            }else {

                $values[] = $this->concatFieldsMapping( $property , $fieldName );

            }
        
        }
        
        return ( !empty( $values ) ) ? implode( $this->currentSeparator , $values ) : '';

    }

    /**
     * Detect Separator Character of Mapping and Separate Mapping data
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Mapping Data
     * 
     * @return array
     */
    private function checkMappingSeparators( $mapping ){

        $returnMapping = array( $mapping );

        foreach ( $this->separators as $separator ){

            if ( strpos( $mapping , $separator ) === false ) continue;

            $returnMapping = explode( $separator , $mapping );

            $this->currentSeparator = $separator;

            break;

        }

        return $returnMapping;

    }

    /**
     * Return Current Client Theme
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string
     */
    public static function getClientTheme(){

        return self::CLIENT_THEME;

    }

}