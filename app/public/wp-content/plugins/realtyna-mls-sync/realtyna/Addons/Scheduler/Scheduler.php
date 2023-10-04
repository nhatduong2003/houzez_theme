<?php

namespace Realtyna\Sync\Addons\Scheduler;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Simulate Scheduler for Wordpress Without using WP-Cron
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Scheduler
{
    /** @var array store allowed scheduler sycles */
    private $schedulerCycles = [ "hourly" , "daily" , "twicedaily" , "weekly" , "monthly" ];
    
    /** @var string default scheduler cycle */
    const DEFAULT_SCHEDULE_CYCLE = "daily";

    /** @var string default scheduler prefix */
    const DEFAULT_SCHEDULE_PREFIX = "_realtyna_scheduler_";

    /** @var string main  complete scheduler prefix */
    private $schedulerPrefix;

    /** @var string scheduler name */
    private $schedulerName;

    
    /**
     * Class constructor
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $schedulerPrefix user defined scheduler prefix , default is empty
     * 
     * @return void
     */
    public function __construct( $schedulerPrefix = '' )
    {

        $this->setSchedulerPrefix( $schedulerPrefix ) ;

    }

    /**
     * Set Scheduler Prefix
     *      concat default prefix with user defined prefix
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $schedulerPrefix
     * 
     * @return void
     */
    private function setSchedulerPrefix( $schedulerPrefix )
    {
        
        $this->schedulerPrefix = !empty( $schedulerPrefix ) ? $schedulerPrefix . self::DEFAULT_SCHEDULE_PREFIX : self::DEFAULT_SCHEDULE_PREFIX ;
        
    }

    /**
     * Get Scheduler Key 
     *      scheduler Key is wordpress metakey that keep the scheduler time
     *      it is combination of scheduler prefix with shceduler name
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string 
     */
    private function getSchedulerKey()
    {
        
        return $this->schedulerPrefix . $this->schedulerName;

    }

    /**
     * Create Scheduler option
     *      since scheduler option stored in wordpress options , so if update_option function does not exists it will return false
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    private function schedulerCreate()
    {

        if ( function_exists('update_option') ){

            return update_option( $this->getSchedulerKey() , time() );

        }

        return false;

    }

    /**
     * Update Scheduler timestamp
     *      it's alias for schedulerCreate since it's using update_option and it's suitable to update timestamp too
     * 
     * @see self::schedulerCreate();
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    private function schedulerUpdate()
    {
        
        return $this->schedulerCreate();

    }

    /**
     * Get current time of Scheduler
     *      it's stored in wordpress option so if wordpress get_option function is not available it will be return zero otherwise return timestamp
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int return zero if failed or timestamp in succeed
     */
    private function schedulerTime()
    {

        if ( function_exists('get_option') ){

            return get_option( $this->getSchedulerKey() );

        }

        return 0;

    }

    /**
     * Check if Scheduler Exists
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool 
     */
    private function schedulerExists()
    {

        return !empty( $this->schedulerTime() );

    }

    /**
     * Generate next timestamp cycle based on current scheduler's timestamp
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $schedulerCycle it should be available in $this->scheduleCycles array , default is empty and empty means daily cycle
     * 
     * @return int Next timestamp cycle
     */
    private function schedulerCycleTime( $schedulerCycle = '' )
    {

        $cycleTime = 0;
        
        $cycle = in_array( $schedulerCycle , $this->schedulerCycles ) ? $schedulerCycle : self::DEFAULT_SCHEDULE_CYCLE ;


        switch ($cycle) {
            case 'hourly':
                $cycleTime = 60 * 60;
                break;
                
            case 'daily':
                $cycleTime = 60 * 60 * 24;
                break;
                
            case 'twicedaily':
                $cycleTime = 60 * 60 * 24 * 2;
                break;
                    
            case 'weekly':
                $cycleTime = 60 * 60 * 24 * 7;
                break;
            
            case 'monthly':
                $cycleTime = 60 * 60 * 24 * 30;
                break;

            default:
                $cycleTime = 60 * 60 * 24;
                break;
        }

        if ( $this->schedulerExists() ){
        
            $cycleTime += $this->schedulerTime() ;

        }

        return $cycleTime;

    }

    /**
     * Check user defined function to run in scheduled time
     *      before run user defined function, it will be check to be exists
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string|array $schedulerFunction user defined function , can be a simple function or an array that pointed to method from specified class
     *      currently supports functions or method withour arguments
     *      Example value when run a simple function => 'funcSample'
     *      Exmple value when run a static function from a class => 'staticClass::funcSample'
     *      Example value when run a method form a class => [ 'sampleClass' , 'funcSample' ]
     * 
     * @return bool
     */
    private function runSchedulerFunction( $schedulerFunction )
    {

        if ( !empty( $schedulerFunction ) ){

            if ( is_array( $schedulerFunction ) && count( $schedulerFunction ) == 2 ){

                if ( class_exists( $schedulerFunction[0] ) && method_exists( $schedulerFunction[0] , $schedulerFunction[1] ) ){

                    try{

                        call_user_func( array( $schedulerFunction[0] , $schedulerFunction[1] ) );

                        return true;
    
                    }catch( Exception $e ){

                        error_log( __FILE__ . " , " .  __LINE__ . " : ( call_user_func issue ) => " . $e->getMessage() );

                    }

                }

            }elseif( is_string( $schedulerFunction ) ){
                
                if ( strpos( $schedulerFunction , "::" ) === false ){

                    $arrayCallable = explode( "::" , $schedulerFunction);

                    if ( class_exists( $arrayCallable[0] ) && method_exists( $arrayCallable[0] , $arrayCallable[1] ) ){

                        try{

                            call_user_func( $schedulerFunction );

                            return true;
            
                        }catch( Exception $e ){
    
                            error_log( __FILE__ . " , " .  __LINE__ . " : ( call_user_func issue ) => " . $e->getMessage() );
    
                        }

                    }

                }else{

                    if ( function_exists( $schedulerFunction ) ){
                        
                        try{

                            call_user_func( $schedulerFunction );

                            return true;
            
                        }catch( Exception $e ){
    
                            error_log( __FILE__ . " , " .  __LINE__ . " : ( call_user_func issue ) => " . $e->getMessage() );
    
                        }

                    }

                }   

            }
        }

        return false;

    }

    /**
     * Schedule a Function to run in specefied Cycles
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $schedulerName will be used to generate scheduler metakey
     *      can't be empty
     * @param string $schedulerFunction will be used to fire in determined cycles
     *      can't be empty
     * @param string $schedulerCycle scheduler cycle , default is empty that  means daily
     *      the value should be available in $this->schedulerCycles
     * 
     * @return bool
     */
    public function schedule( $schedulerName , $schedulerFunction , $schedulerCycle = '' )
    {

        if ( !empty( $schedulerName ) && !empty( $schedulerFunction ) ){

            $this->schedulerName = $schedulerName;
            $this->schedulerFunction = $schedulerFunction;

            if ( $this->schedulerExists() ){

                if ( $this->schedulerTime() >= time() ){

                    $this->runSchedulerFunction( $schedulerFunction );

                    $this->schedulerCycleTime( $schedulerCycle );

                }

                return true;

            }else{

                $this->schedulerCreate();

            }

        }

        return false;
        
    }

}