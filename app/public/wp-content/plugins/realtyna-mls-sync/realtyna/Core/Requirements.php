<?php
namespace Realtyna\Sync\Core;

/**
 * Core Requirements
 * 
 * @abstract
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Requirements
{

    /** @var array $requirements list requirements as array */
    public $requirements = [];

    /**
     * Class Constructor
     * 
     * @author Chris A <chris.a@realtyna.net>
     */
    public function __construct(){

        $this->create( 'php' , [
            'required_value' => '7.1' ,
            'current_value' => $this->getPhpVersion() ,
            'label' => __( 'PHP Version' , REALTYNA_MLS_SYNC_SLUG ) ,
            'hint' => __( 'PHP Version should be >= 7.1' , REALTYNA_MLS_SYNC_SLUG ) ,
            'manual' => 'https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/856/87/',
            'operator' => '>=',
            'callback' => [ __CLASS__ , 'requirementValidator' ]
        ] );

        $this->create( 'mysql' , [
            'required_value' => '5.4' ,
            'current_value' => $this->getMysqlVersion() ,
            'label' => __( 'MySQL Version' , REALTYNA_MLS_SYNC_SLUG ) ,
            'hint' => __( 'MySQL Version should be >= 5.4' , REALTYNA_MLS_SYNC_SLUG ) ,
            'manual' => 'https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/856/87/',
            'operator' => '>=',
            'callback' => [ __CLASS__ , 'requirementValidator' ]
        ] );

        $this->create( 'max_execution_time' , [
            'required_value' => '600' ,
            'current_value' => $this->getMaxExecutionTime() ,
            'label' => __( 'max_execution_time Value', REALTYNA_MLS_SYNC_SLUG ) ,
            'hint' => __( 'max_execution_time Value should be >= 600' , REALTYNA_MLS_SYNC_SLUG ),
            'manual' => 'https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/856/87/',
            'operator' => '>=',
            'callback' => [ __CLASS__ , 'requirementValidator' ]
        ] );

        $this->create( 'memory_limit' , [
            'required_value' => '128M' ,
            'current_value' => $this->getMemoryLimit() ,
            'label' => __( 'memory_limit Value' , REALTYNA_MLS_SYNC_SLUG ) ,
            'hint' => __( 'memory_limit Value should be >= 128M' , REALTYNA_MLS_SYNC_SLUG ),
            'manual' => 'https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/856/87/',
            'operator' => '>=',
            'callback' => [ __CLASS__ , 'requirementIntValidator' ]
        ] );

        $this->create( 'post_max_size' , [
            'required_value' => '48M' ,
            'current_value' => $this->getPostMaxSize() ,
            'label' => __( 'post_max_size Value' , REALTYNA_MLS_SYNC_SLUG ) ,
            'hint' => __( 'post_max_size Value should be >= 48M' , REALTYNA_MLS_SYNC_SLUG ),
            'manual' => 'https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/856/87/',
            'operator' => '>=',
            'callback' => [ __CLASS__ , 'requirementIntValidator' ]
        ] );

        $this->create( 'upload_max_filesize' , [
            'required_value' => '48M' ,
            'current_value' => $this->getUploadMaxFilesize() ,
            'label' => __( 'upload_max_filesize Value' , REALTYNA_MLS_SYNC_SLUG ) ,
            'hint' => __( 'upload_max_filesize Value should be >= 48M' , REALTYNA_MLS_SYNC_SLUG ),
            'manual' => 'https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/856/87/',
            'operator' => '>=',
            'callback' => [ __CLASS__ , 'requirementIntValidator' ]
        ] );

        $this->create( 'wordpress' , [
            'required_value' => '4.6' ,
            'current_value' => $this->getWpVersion() ,
            'label' => __( 'Wordpress Version' , REALTYNA_MLS_SYNC_SLUG ) ,
            'hint' => __( 'Wordpress Version should be >= 4.6' , REALTYNA_MLS_SYNC_SLUG ),
            'manual' => 'https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/856/87/',
            'operator' => '>=',
            'callback' => [ __CLASS__ , 'requirementValidator' ]
        ] );

        $this->create( 'permalinks' , [
            'required_value' => '' ,
            'current_value' => $this->getPermalinksStructure() ,
            'label' => __( 'Wordpress Permalinks Structutre' , REALTYNA_MLS_SYNC_SLUG ) ,
            'hint' => __( 'Wordpress Permalinks Structutre should be active' , REALTYNA_MLS_SYNC_SLUG ),
            'manual' => '',
            'operator' => '!=',
            'callback' => [ __CLASS__ , 'requirementValidator' ]
        ] );


    }

    /**
     * Create New Requiremnt as an array item
     *
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $slug
     * @param array $params
     * @param boolean $updateIfExists
     * @return bool|array
     */
    private function create( $slug , $params , $updateIfExists = false  ){

        if ( !empty( $slug ) && $this->validateParams( $params ) ){

            if ( !$updateIfExists && $this->exists( $slug ) ){

                return false;

            }
            
			$params['result'] = false;
			
            return $this->requirements[ $slug ] =  $params ;

        }

        return false;

    }

    /**
     * Update a Requirement item
     *
     * @param string $slug
     * @param array $params
     * @return bool|array
     */
    private function update( $slug , $params ){

        return $this->create( $slug , $params , true );

    }

    /**
     * Delete a Requirement item By Slug
     *
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $slug
     * @return boolean
     */
    private function delete( $slug ){

        if ( $this->exists( $slug ) ){

            unset( $this->requirements[ $slug ] );

            return ( ! $this->exists( $slug ) ) ;

        }

        return false;

    }

    /**
     * Read a Requirement item by Slug
     *
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $slug
     * @param array|null $index
     * @return bool|string|array
     */
    private function read( $slug , $index = null ){

        if ( $this->exists( $slug , $index ) ){

            return \is_null( $index ) ? $this->requirements[ $slug ] : $this->requirements[ $slug ][ $index ] ;

        }

        return false;

    }

    /**
     * Check Requirement existance by Slug
     *
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $slug
     * @param array|null $index
     * @return bool
     */
    private function exists( $slug , $index = null ){

        if ( ! \is_null( $index ) ){
            return isset( $this->requirements[ $slug ][ $index ] ) ;
        }

        return isset( $this->requirements[ $slug ] ) ;

    }

    /**
     * Validate Params array for requirement item
     * 
     * @author Chris A <chris.a@realtyna.net>
     *
     * @param array $params
     * 
     * @return void
     */
    private function validateParams( $params ){

        $validate = false;

        if ( is_array( $params ) && !empty( $params ) ) {

            if (isset( $params['required_value'] ) && 
                isset( $params['current_value'] ) && 
                !empty( $params['label'] ) && 
                !empty( $params['operator'] ) &&
                !empty( $params['callback'] )  ){

                if ( in_array( $params['operator'] , ['==','>=', '<=', '!='] ) ){

                    if ( is_array( $params['callback'] ) && \method_exists( $params['callback'][0] , $params['callback'][1] ) ){

                        return true;

                    }

                }

            }

        }

        return $validate;

    }

    /**
     * Get Requirements as array
     *
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array
     */
    public function getRequirements(){

        return $this->requirements;

    }

    /**
     * Requirement value validator
     *
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $requirementSlug
     * 
     * @return bool
     */
    public function requirementValidator( $requirementSlug ){

        $requirement = $this->requirements[ $requirementSlug ] ?? '';		

        if ( $this->validateParams( $requirement ) ){

            if ( $requirement['operator'] == '==' ){

                return $requirement['current_value'] == $requirement['required_value'];

            }

            if ( $requirement['operator'] == '!=' ){				

                return $requirement['current_value'] != $requirement['required_value'];

            }

            if ( $requirement['operator'] == '>=' ){

                if ( $requirementSlug == 'max_execution_time' ){
					return ( $requirement['current_value'] == 0 || $requirement['current_value'] == -1 || $requirement['current_value'] >= $requirement['required_value'] );
				}else

                    return $requirement['current_value'] >= $requirement['required_value'];

            }

            if ( $requirement['operator'] == '<=' ){

                return $requirement['current_value'] <= $requirement['required_value'];

            }

        }

        return false;

    }

    /**
     * Requirement Int value validator
     *
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $requirementSlug
     * 
     * @return bool
     */
    public function requirementIntValidator( $requirementSlug ){

        $requirement = $this->requirements[ $requirementSlug ] ?? '';		

        if ( $this->validateParams( $requirement ) ){

            if ( $requirement['operator'] == '==' ){

                return $requirement['current_value'] == $requirement['required_value'];

            }

            if ( $requirement['operator'] == '!=' ){				

                return $requirement['current_value'] != $requirement['required_value'];

            }

            if ( $requirement['operator'] == '>=' ){

                if ( $requirementSlug == 'memory_limit' ){
					return ( intval( $requirement['current_value'] ) == 0 || intval( $requirement['current_value'] ) == -1 ||  intval( $requirement['current_value'] ) >= intval( $requirement['required_value'] ) );
				}else
                    return intval( $requirement['current_value'] ) >= intval( $requirement['required_value'] );

            }

            if ( $requirement['operator'] == '<=' ){

                return intval( $requirement['current_value'] ) <= intval( $requirement['required_value'] );

            }

        }

        return false;

    }


    /**
     * Check All Requirments
     * 
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return bool
     */
    public function check(){

        $invalidRequirement = 0;
        
        foreach ( $this->requirements as $requirementKey => $requirementValue ){

            $callback = $requirementValue['callback'];
            $requirementValue['result'] = \call_user_func( array( $callback[0] , $callback[1] ) , $requirementKey );
            
            $this->requirements [$requirementKey ] [ 'result' ] = $requirementValue['result'];

            if ( ! $requirementValue['result'] ){

                $invalidRequirement++;

            }

        }

        return ( $invalidRequirement == 0 );

    }


    /**
     * Get PHP Version
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return float
     */
    public static function getPhpVersion(){

        return floatval( phpversion() );

    }
    /**
     * Get WordPress Version
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return float Wordpress Version or zero
     */
    public static function getWpVersion(){

        if ( function_exists('get_bloginfo') ){

            return floatval( get_bloginfo( 'version' ) );

        }
        
        return 0;

    }

    /**
     * Get MySQL Version
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return float MySQL version or zero
     */
    public static function getMysqlVersion(){

        global $wpdb;

        if ( isset($wpdb) ){

            return floatval( $wpdb->db_version() );

        }else{

            if ( WPDB::class && \method_exists( 'WPDB' , 'db_version' ) ){

                return floatval( WPDB::db_version() );

            }

        }

        return 0;

    }

    /**
     * Get Max Execution time of PHP Scripts
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int as seconds
     */
    public static function getMaxExecutionTime(){

        return ini_get( 'max_execution_time' );

    }

    /**
     * Get Memory Limit For PHP Scripts
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int
     */
    public static function getMemoryLimit(){

        return ini_get( 'memory_limit' );

    }

    /**
     * Get Post Max Size For PHP 
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int
     */
    public static function getPostMaxSize(){

        return ini_get( 'post_max_size' );

    }

    /**
     * Get Max File Size for upload
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int
     */
    public static function getUploadMaxFilesize(){

        return ini_get( 'upload_max_filesize' );

    }

    /**
     * Get Permalinks Structure
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string
     */
    public static function getPermalinksStructure(){

        if ( function_exists('get_option') ){

            return get_option( 'permalink_structure' );

        }

        return '';

    }

}