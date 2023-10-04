<?php

namespace Realtyna\Sync\Core;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Mapper for MLS Sync
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @since 1.1.0
 */
class Mapper {

    /** @var array array of valid separator */
    private $separators = [ "," , "|" ];

    /** @var string concat operator */
    private $concatOperator = '+';

    /** @var string current separator holder */
    private $currentSeparator = '';

    /** @var string|null current provider holder */
    private $provider = null;

    /** @var string|null token */
    private $token = null;

    /** @var string|null target product holder */
    private $targetProduct = null;

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
    public function __construct( $token = null , $provider = null , $addationMapping = null , $propertyImportOptions = null )
    {

        $this->token = $token;
        $this->provider = $provider;
        $this->addationMapping = $addationMapping;
        $this->propertyImportOptions = $propertyImportOptions;

        $app = App::getInstance( false );

        if ( !$app->getTargetProduct() ){
            
            $app->upgradeLegacyFeatures();

        }

        $app->createTargetProductInstance();

        $this->targetProduct = $app->getTargetProduct();

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
    public function run( $demo = true )
    {

        return $this->getProviderData( $demo );

    }

    /**
     * Set Mapping Data for Current Provider
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function setProviderMapping()
    {

        if ( !empty( $this->provider ) && !empty( $this->token ) && $this->targetProduct && !empty( $this->targetProduct->strtolowerName() ) ){

            $api = new Api();
            $mapping = $api->getMapping( $this->token, $this->provider , $this->targetProduct->strtolowerName() );

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
    private function getProviderMapping()
    {

        if ( empty( $mapping ) ){
			
			$this->setProviderMapping();
			
		}

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
    private function getProviderData( $demo )
    {

        $importedProperty = 0;

        if ( empty( $this->provider ) || empty( $this->getProviderMapping() ) )
            return $importedProperty;

        $sandbox = new Sandbox( $this->provider);

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
    public function importProperty( $property , $demo = false )
    {

        if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && defined( 'WP_DEBUG_MLS_SYNC' ) ){

            error_log( 'importProperty > property : ' . var_export( $property , true ) );
            error_log( 'mapper > provider : ' . $this->provider );
            error_log( 'importProperty > getProviderMapping() : ' . var_export( $this->getProviderMapping() , true ) );

        }
        if ( empty( $property ) || empty( $this->token ) )
            return false;

        $this->extractProviderMappingFromPropertyArray( $property );

        if ( empty( $this->provider ) || empty( $this->getProviderMapping() ) )
            return false;

        $product = $this->targetProduct->property( true , $this->provider  , $this->propertyImportOptions  );

        if ( $product ){

            return $product->import( $this->map( $property ) , $demo ) ;

        }

        return false;

    }

	/**
	 * Extrat Provider and Mapping structure from the property array for supporting multi-mls feature
	 *
	 * @author Chris A <chris.a@realtyna.net>
	 *
	 * @param $property the property array data
	 *
	 * return void
	 */
	public function extractProviderMappingFromPropertyArray( $property )
	{
		
		$providerID = $property['extra_data']['server_id'] ?? 0 ;
		
		if ( !empty( $this->token ) && !empty( $providerID ) ){
			
			$api = new Api();
			$response = $api->getProviders( $this->token );
			
			if ( $response['status'] == 'OK' ){
				
				$providers = $response['message'];
				
				if ( is_array( $providers ) && !empty( $providers ) ){
						
					foreach( $providers as $index => $provider ){
							
						if ( $provider['id'] == $providerID && $provider['short_name'] ){
							
							$this->provider = $provider['short_name'];
							$this->setProviderMapping();
							
							break;
								
						}
								
					}
					
				}
				
			}
			
		}
		
	}

    /**
     * Map Property Data with Target Product Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array Property Data
     * 
     * @return array|bool SlugValues as array Or false on fails
     */
    private function map( $property )
    {
        
        if ( !empty( $this->getProviderMapping() ) && is_array( $this->getProviderMapping() ) && !empty( $property) ){

            $this->slugValues = null;
            
            foreach ($this->getProviderMapping() as $key => $value) {
                
                if ( !is_array( $value )  ) continue;

                if ( !empty( $value['extra'] ) ){

                    $this->slugValues[ $key ]['extra'] = $value['extra'];

                }

                if ( !empty( $value['replacements'] ) ){

                    $this->slugValues[ $key ]['replacements'] = $value['replacements'];

                }

                if ( !empty( $value['default'] ) ){

                    $this->slugValues[ $key ]['value'] = $value['default'];

                }elseif ( !empty( $value['mapping'] ) ){

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
							
								$firstValue = $this->value( $firstValue , $property );								
								$secondValue = $this->value( $secondValue , $property );

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
										
										$finalValue = $this->value( $value['conditional']['trueValue'] , $property );										
										
									}else{
										
										$finalValue = $this->value( $value['conditional']['falseValue'] , $property );
										
									}
									
								}

							
							}
							
						}
						
					}
                    $this->slugValues[ $key ]['value'] = $finalValue;
				
                }elseif( !empty( $value['condition'] ) ){
					
					$finalValue = '';
					
					if (	
							isset( $value['condition']['statement'] ) && 
							isset( $value['condition']['falseValue'] )  && 
							isset( $value['condition']['trueValue'] ) 
						)
					{

						$conditionStatements = explode( "," , $value['condition']['statement']  );
						$conditionOperator = $value['condition']['logic'] ?? 'AND';
						$statementResults = [];
						
						foreach ( $conditionStatements as $condition ) {
							
							$statementResults[] = $this->conditionParser( $condition , $property );
							
						}
						
						if ( $this->conditionLogic( $statementResults , $conditionOperator ) ){
										
							$finalValue = $this->value( $value['condition']['trueValue'] , $property );										
										
						}else{
										
							$finalValue = $this->value( $value['condition']['falseValue'] , $property );
							
						}
							
						
					}

                    $this->slugValues[ $key ]['value'] = $finalValue;

                }elseif( !empty( $value['conditions'] ) && is_array( $value['conditions'] ) ){
					
					$finalValue = '';
					
					foreach ( $value['conditions'] as $conditionKey => $conditionValue ){
							
						if ( isset( $conditionValue['statement'] ) && isset( $conditionValue['trueValue'] ) ){

							$conditionStatements = explode( "," , $conditionValue['statement']  );
							$conditionOperator = $conditionValue['logic'] ?? 'AND';
							$statementResults = [];
							
							foreach ( $conditionStatements as $condition ) {
								
								$statementResults[] = $this->conditionParser( $condition , $property );
								
							}
							
							if ( $this->conditionLogic( $statementResults , $conditionOperator ) ){
											
								$finalValue = $this->value( $conditionValue['trueValue'] , $property );										
											
							}elseif ( isset( $conditionValue['falseValue'] ) ) {
											
								$finalValue = $this->value( $conditionValue['falseValue'] , $property );
								
							}
							
						}
						
						if ( $finalValue != '' ){
							break;
						}
						
					}
					
                    $this->slugValues[ $key ]['value'] = $finalValue;

                }
                
            }

            $this->mergeWithAdditionMapping();
            
            return  $this->slugValues ;

        }

        return false;

    }

	/**
	 * do logical operatoration on the conditional statements
	 * 
	 * @author Chris A <chris.a@realtyna.net>
	 *
	 * @param $statementResults array an array conatianing cconditional statements results
	 * @param $operator string logical operator , default is AND operator
	 *
	 * @return boolean
	 */
	private function conditionLogic( $statementResults , $operator = 'AND')
	{
			
		while ( is_array( $statementResults ) && !empty( $statementResults ) ){
			
			$popValue = (bool) array_pop( $statementResults ) ;

			if ( !isset( $result ) ){
				
				$result = $popValue;
				
			}else{
				
				if ( strtolower( $operator ) == 'or' ) {
					
					$result = ( $result or $popValue );
					
				}else{
					
					$result = ( $result and $popValue );
					
				}
				
			}
			
		}
		
		return $result ?? false;	
		
	}

	/**
	 * Determine Static Value or Mapped Value
	 * @author Chris A <chris.a@realtyna.com>
	 *
	 * @param $valueParam string
	 * @param $property mixed
	 *
	 * @return mixed
	 */
	private function value( $valueParam , $property )
	{
		
		$returnValue = '';
		
		$valueArray = explode( ":" , $valueParam );
		
		if ( count( $valueArray ) == 1 ){
			
			$returnValue = $this->propertyMapping( $property , trim( $valueParam ) );

		}elseif ( count( $valueArray ) == 2 ){
			
			$type = strtolower( trim( $valueArray[0] ) );
			$value = $this->propertyMapping( $property , trim( $valueArray[1] ) );
			
			if ( $type == 'static' ){
				
				$returnValue = strval( trim( $valueArray[1] ) );
				
			}
			
			if (  $type == 'string' ){
				
				$returnValue = strval( $value );
				
			}
			
			if ( $type == 'bool' || $type == 'boolean'){
				
				$returnValue = boolval( $value );
				
			}

			if ( $type == 'int' || $type == 'integer'){
				
				$returnValue = intval( $value );
				
			}
			
			if ( $type == 'float'){
				
				$returnValue = floatval( $value );
				
			}			
		
		}
		
		return $returnValue;
	}

	/**
	 * Parse conditional statement with compare operator
	 *
	 * @author Chris A <chris.a@realtyna.net>
	 *
	 * @param $condition string conditional statement
	 * @param $property array
	 *
	 * @return boolean
	 */
	private function conditionParser( $condition , $property )
	{
		
		$conditionResult = false;
		
		$compareOperators = [ "===" , "!==" , "==" , "!=" , "<>" , ">=" , "<=" , ">" , "<" ];
		
		foreach ( $compareOperators as $operator){
			
			if ( strpos( $condition , $operator ) !== false ){
				
				$values = explode( $operator , $condition );
				
				if ( count( $values ) == 2 ){
					
					$firstValue  = $this->value( trim( $values[0] ) , $property );
					$secondValue = $this->value( trim( $values[1] ) , $property );
					
					if ( $operator == '==' ){
												
						$conditionResult = empty( $secondValue ) ? ( $firstValue == $secondValue || empty( $firstValue ) ) : ( $firstValue == $secondValue );
						
					}elseif ( $operator == '===' ){
						
						$conditionResult = ( $firstValue === $secondValue );
						
					}elseif ( $operator == '!==' ){
						
						$conditionResult = ( $firstValue !== $secondValue );
						
					}elseif ( $operator == '!=' ){
						
						$conditionResult = empty( $secondValue ) ? ( $firstValue != $secondValue || !empty( $firstValue ) ) : ( $firstValue != $secondValue );
						
					}elseif ( $operator == '<>' ){
						
						$conditionResult = empty( $secondValue ) ? ( $firstValue <> $secondValue || !empty( $firstValue ) ) : ( $firstValue <> $secondValue );
						
					}elseif ( $operator == '>=' ){
						
						$conditionResult = empty( $secondValue ) ? ( $firstValue >= $secondValue || $firstValue >= 0 ) : ( $firstValue >= $secondValue );
						
					}elseif ( $operator == '<=' ){
						
						$conditionResult = empty( $secondValue ) ? ( $firstValue <= $secondValue || $firstValue <= 0 ) : ( $firstValue <= $secondValue );
						
					}elseif ( $operator == '>' ){
						
						$conditionResult = empty( $secondValue ) ? ( $firstValue > $secondValue || ( !empty( $firstValue ) && $firstValue > 0 ) ) : ( $firstValue > $secondValue );
						
					}elseif ( $operator == '<' ){
						
						$conditionResult = empty( $secondValue ) ? ( $firstValue < $secondValue || ( empty( $firstValue ) || $firstValue < 0 ) ) : ( $firstValue < $secondValue );
						
					}
					
				}
				
				break;
			}
			
		}
			
		return $conditionResult;
		
	}

    /**
     * Merge Property Data With custom Addational Data to Target Product Property
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function mergeWithAdditionMapping()
    {

        if ( !empty( $this->addationMapping ) && is_array( $this->addationMapping ) ){

            foreach ($this->addationMapping as $key => $value) {

                if ( !isset( $this->slugValues[ $key ] ) ){
					
					$this->slugValues[ $key ]['value'] = $value;
					
				}elseif( empty( $this->slugValues[ $key ]['value'] ) ){
					
					$this->slugValues[ $key ]['value'] = $value;
					
				}

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

            $fieldName = str_replace( "\/" , "\\" , $fieldName );
			
            $bypassSlashes = function( $val ){ return str_replace( "\\" , "/" , $val ); };
                    
            if ( strpos( $fieldName , $this->concatOperator ) === false ){

                $index =  array_map( $bypassSlashes ,  explode( "/" , $fieldName ) );

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
    private function checkMappingSeparators( $mapping )
    {

        $returnMapping = array( $mapping );

        foreach ( $this->separators as $separator ){

            if ( strpos( $mapping , $separator ) === false ) continue;

            $returnMapping = explode( $separator , $mapping );

            $this->currentSeparator = $separator;

            break;

        }

        return $returnMapping;

    }

}