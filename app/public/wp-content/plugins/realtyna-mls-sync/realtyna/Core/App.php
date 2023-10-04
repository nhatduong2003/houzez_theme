<?php
	
namespace Realtyna\Sync\Core;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * MLS Sync Wordpress based App
 * 
 * @final
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 2.0
 */
final class App 
{

	/** 
     * @var object singleton object 
     * @static
     */
    static public $instance = false;

    /** @var object Handle Target Object*/
    protected $targetProduct = null;

    /** @var array Store App Requirements*/
    protected $requirements = [];
    
    /** @var array  available notice types */
    protected $noticeTypes = array( "info" , "error" , "success" , "warning" );

    /** @var string default plugin icon holder */
    private const REALTYNA_MLS_SYNC_ICON = "dashicons-database-import" ;

    /** @var string credential Key */
    public const REALTYNA_IDX_CREDENTIAL = "REALTYNA_IDX_CREDENTIAL";
    
    /** @var string options Key */
    public const REALTYNA_IDX_OPTIONS = "REALTYNA_IDX_OPTIONS";

    /** @var string import Key */
    public const REALTYNA_IDX_IMPORT = "REALTYNA_IDX_IMPORT";

    /** @var string mls data Key */
    public const REALTYNA_MLS_DATA = "REALTYNA_MLS_DATA";

    /** @var string latest update time */
    public const REALTYNA_MLS_SYNC_UPDATE_TIME = "REALTYNA_MLS_SYNC_UPDATE_TIME";

    /** @var string external images mark */
    public const EXTERNAL_IMAGES_MARK = "_REALTYNA_MLS_SYNC_EXTERNAL_IMAGE";

    /** @var int minimum time needed for execute scripts in seconds */
    public const MINIMUM_EXECUTION_TIME_FOR_DATA_IMPORT = 3000;

    /** @var string demo data provider */
    private const DEMO_PROVIDER = 'mlg_demo';

    /**
     * Class Constructor Method
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param bool $initializeForWP
     * @return void
     */
    public function __construct( $initializeForWP = true )
    {
        
        if ( $initializeForWP ){

            $this->init();

        }        
        
    }

    /**
     * Plugin Initialize
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function init()
    {

        if ( $this->isSupportedProduct() ){      
            
            $this->createTargetProductInstance();

            register_activation_hook( REALTYNA_MLS_SYNC_PLUGIN_FILE , array( $this, 'activatePlugin'));
            register_deactivation_hook( REALTYNA_MLS_SYNC_PLUGIN_FILE , array( $this, 'deactivatePlugin' ) );
    
            add_action( 'admin_enqueue_scripts', array( $this , 'loadPluginStyles' ) );    
            add_action( 'admin_enqueue_scripts', array( $this , 'loadPluginScripts' ) ); 
    
            add_action( 'admin_menu' , array ( $this , 'drawMenu' ) );
            
            add_action( 'wp_ajax_realtynaidx' , array ( $this , 'ajaxResponse' ) );
    
            add_filter( 'get_attached_file', array( $this , 'handleExternalMedia') , 100, 2 );        
            add_filter( 'post_thumbnail_html', array( $this , 'handleExternalThumbnail' ), 100, 5);
            add_filter( 'wp_get_attachment_image', array( $this , 'fixExternalThumbnailsInEditPage' ), 100, 5);
    
			add_action('admin_notices', array( $this, 'checkCronJob' ) ); 
    
			add_filter( 'cron_schedules', array( $this , 'cronSchedule' ) );
			
			add_action( 'realtyna_mls_sync_update_plugin',  array( $this , 'updatePlugin' ) );
			add_action( 'realtyna_mls_sync_purge_listings',  array( $this , 'purgeListings' ) );
			add_action( 'realtyna_mls_sync_purge_attachments',  array( $this , 'purgeAttachments' ) );            
    
            $this->upgradeLegacyFeatures();
            $this->initRestRoutes();
            $this->setScheduler();
    
        }else{

            add_action('admin_notices', array( $this, 'noticeNotSupportedProduct' ) ); 

        }

    }

    /**
     * Get singlton instance of current class
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param bool $initializeForWP
     * @return object
     */
	static public function getInstance( $initializeForWP = true )
    {
		
		if ( !self::$instance ){
            self::$instance = new self( $initializeForWP );
        }
		
		return self::$instance;
		
	}

    /**
     * Check Active theme is in the providers list
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool|string
     */
    private function isSupportedTheme()
    {

        $themes = $this->getSupportedThemes();

        if ( !empty( $themes ) ){

            foreach( $themes as $theme ){

                if ( $theme::isActive() ){

                    return $theme::strtolowerName();

                }

            }

        }

        return false;

    }

    /**
     * Check if there is a supported product or no
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool 
     */
    private function isSupportedProduct()
    {

        if ( $this->isSupportedTheme() ){

            return true;

        }

        return false;

    }

    /**
     * Get Supported Themes Array
     *@author Chris A <chris.a@realtyna.net>
     * 
     * @return array
     */
    private function getSupportedThemes()
    {
        
        $themes = array();

        if ( ThemeProviders::class ){

            $themes = ThemeProviders::$providers;

        }

        return $themes;

    }

    /**
     * Update Legacy Features
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return void
     */
    public function upgradeLegacyFeatures()
    {

        $this->storeActiveThemeAsTargetProduct();

    }

    /**
     * Store Active Theme as TargetProduct
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return void
     */
    private function storeActiveThemeAsTargetProduct()
    {

        $themeAsTarget = $this->isSupportedTheme() ;

        if ( !$this->getTargetProductOption() ){

            if ( !empty( $this->getCredentials() ) && !empty( $themeAsTarget ) ){

                $this->setTargetProductOption( $themeAsTarget , true );

            }

        }

    }

    /**
     * Get Stored Target Product from Options
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return false|null|string
     */
    public function getTargetProductOption()
    {
        
        $storedTargetProductKey = REALTYNA_MLS_SYNC_SLUG . '-target-product';
        return get_option( $storedTargetProductKey );

    }

    /**
     * Set Target Product in Options
     *@author Chris A <chris.a@realtyna.net>
     * 
     * @param string $targetProductName
     * @param bool $createInstance
     * @return bool
     */
    private function setTargetProductOption( $targetProductName , $createInstance = false )
    {

        $storedTargetProductKey = REALTYNA_MLS_SYNC_SLUG . '-target-product';
        $result = update_option( $storedTargetProductKey , $targetProductName );

        if ( $createInstance ){

            $result = $this->createTargetProductInstance();

        }

        return $result;

    }

    /**
     * check Target Product is set or no and try to set it , if there is an active supported theme
     * 
     * @auther Chris A <chris.a@realtyna.net>
     *
     * @return bool
     */
    private function checkTargetProduct()
    {
        
        if ( ! $this->getTargetProduct() &&  empty(  $this->getTargetProductOption() ) ){

            add_action('admin_notices', array( $this, 'noticeFailedTargetProduct' ) ); 

            return false;

        }

        return true;

    }

    /**
     * Create an instance of target product and assign it to targetProduct
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return bool
     */
    public function createTargetProductInstance()
    {

        if ( function_exists('get_option') ){

            $targetProductOption = $this->getTargetProductOption();

            if ( !empty( $targetProductOption ) && empty( $this->getTargetProduct() ) ){

                if ( $targetProductOption == $this->isSupportedTheme() ){

                    if ( ThemeFactory::class ){

                        return $this->setTargetProduct( ThemeFactory::create() );

                    }

                }

                add_action('admin_notices', array( $this, 'noticeTargetProductIsNotActive' ) ); 

            }

        }        

        return false;

    }

    /**
     * Get target product instance
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return null|object
     */
    public function getTargetProduct()
    {
        
        return $this->targetProduct;

    }

    /**
     * Set instance of target product
     * @author Chris A <chris.a@realtyna.net>
     *
     * @param Object $productInstance
     * @return bool
     */
    private function setTargetProduct( $productInstance )
    {

        if ( $productInstance ){

            $this->targetProduct = $productInstance ;

            return ( \is_object( $this->targetProduct ) );

        }

        return false;

    }
    
    /**
     * Draw Admin Menus , this show plugin menu only for Administrator roles
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
	public function drawMenu()
    {
		
        $targetProductIsAvailable = $this->checkTargetProduct();
		
        if ( is_super_admin() || current_user_can( 'administrator' ) ){

            add_menu_page( __('Realtyna MLS Sync' , REALTYNA_MLS_SYNC_SLUG ) , __('Realtyna MLS Sync' , REALTYNA_MLS_SYNC_SLUG ) , 'manage_options', REALTYNA_MLS_SYNC_SLUG , array ( $this , 'screenMain' ) , self::REALTYNA_MLS_SYNC_ICON );

			//add_submenu_page( REALTYNA_MLS_SYNC_SLUG, __('Hosting Benchmark' , REALTYNA_MLS_SYNC_SLUG ) , __('Hosting Benchmark' , REALTYNA_MLS_SYNC_SLUG) , 'manage_options', 'realtyna-hosting-benchmark' , array ( $this , 'screenBenchmark' ) );	

            if ( $targetProductIsAvailable ){

                add_submenu_page( REALTYNA_MLS_SYNC_SLUG, __('Settings' , REALTYNA_MLS_SYNC_SLUG ) , __('Settings' , REALTYNA_MLS_SYNC_SLUG) , 'manage_options', 'realtyna-mls-sync-settings' , array ( $this , 'screenSettings' ) );

            }

        }

	}

    /**
     * Display Settings screen
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function screenSettings()
    {

        $this->showHeader();

        if ( $this->targetProduct ){

            $agencies = $this->targetProduct->agencies()->get();
            $agents = $this->targetProduct->agents()->get();
            $agentsDisplayOptions = $this->targetProduct->agents()->getDisplayOptions();
            $realtyna['agencies'] = $agencies;
            $realtyna['agency_post_type'] = $this->targetProduct->agencies()->getPostType();
            $realtyna['agents'] = $agents;
            $realtyna['agent_post_type'] = $this->targetProduct->agents()->getPostType();
            $realtyna['agents_display_options'] = $agentsDisplayOptions;

        }

        if ( View::class ){

            $realtyna['idx_options'] = $this->getIdxOptions();
            $realtyna['idx_import'] = $this->getIdxImport();

            View::view('settings' , $realtyna );

        }

        $this->showFooter();

    }

    /**
     * Display Benchmarker screen
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     *  @return void
     */
    public function screenBenchmark()
    {

        //include_once( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'addon' . DIRECTORY_SEPARATOR . 'benchmarker.php'  );
        // $benchmarker = new \Realtyna\Sync\Addons\Benchmarker\Benchmarker();
        // $benchmarker->load();        

    }

    /**
     * Display Main screen
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function screenMain()
    {

        if ( isset( $_GET["payment"] ) && in_array( $_GET['payment'] , ["success" , "cancel"] ) ) {

            if ( $this->isStripeCallBack() ){

                $this->showPayment( $_GET["payment"] );

            }else{

                $this->gotoStep( $this->determineCurrentStep() );

            }


        }else{

            if ( isset( $_GET['purge_attachments'] ) ){
				
                $this->purgeAttachments();
                
            }			

            if ( isset( $_GET['purge_listings'] ) ){
            
                $this->purgeListings();
                
            }			

            if ( isset( $_GET['reset_mls'] ) ){
            
                self::resetMlsData();
                
            }
    
            $step = ( isset( $_GET['step'] ) && is_numeric( $_GET['step'] ) ) ? $_GET['step'] : $this->determineCurrentStep() ;

            if ( $step > 1 && !isset( $_GET['step'] ) ){

                $this->gotoStep( $step );

            }else {

                $this->stepsWizard( $step );
            }
    
        }
        
    }

    /**
     * Display selected step wizard
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function stepsWizard( $step = 1 )
    {
        
        if ( is_numeric( $step ) ){

            $this->showHeader();

            $this->showStepWizard( $step );

            switch ( $step ) {
                case 2:
                    $this->secondStep();
                    break;
                
                case 3:
                    $this->thirdStep();
                    break;
    
                case 4:
                    $this->fourthStep();
                    break;
                
                case 5:
                    $this->fifthStep();
                    break;
    
                default:
                    $this->firstStep();
                    break;
            }

            $this->showFooter();
    
        }

    }

    /**
     * Display first step wizard
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function firstStep()
    {

        if ( View::class ){

            $requirements = new Requirements();
            $realtyna['requirements-are-met'] = $requirements->check();
            $realtyna['requirements-list'] = $requirements->getRequirements();
            $realtyna['targetProductSelected'] = !empty(  $this->targetProduct ) ? true : false;
            $realtyna['targetProductOption'] = $this->getTargetProductOption();
            $realtyna['supported-themes'] = $this->getSupportedThemes();
            
            View::view( 'steps.first' , $realtyna );

        }

    }

    /**
     * validate first step wizard
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    static public function validateFirstStep()
    {

        $requirements = new Requirements();
        return $requirements->check();

    }

    /**
     * Display second step wizard
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function secondStep()
    {
        
        if ( $this->validateFirstStep() && ( !empty( $_GET['realtyna_idx_target_product'] ) || !empty( $this->getTargetProductOption() ) ) ){

            if ( !empty( $_GET['realtyna_idx_target_product'] ) ){

                $this->setTargetProductOption( $_GET['realtyna_idx_target_product'] , true );

            }

            if ( View::class ){

                $realtyna['credentials'] = $this->getCredentials();

                View::view('steps.second' , $realtyna );

                return;

            }

        }

        $this->gotoStep( 1 );

    }

    /**
     * validate second step wizard
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    static public function validateSecondStep()
    {

        $credentials = static::getCredentials();

        return ( is_array( $credentials ) && !empty( $credentials ) ) ;

    }

    /**
     * Display third step wizard
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function thirdStep()
    {
        
        if ( $this->validateSecondStep() ){

            if ( $this->targetProduct ){

                $agencies = $this->targetProduct->agencies()->get();
                $agents = $this->targetProduct->agents()->get();
                $agentsDisplayOptions = $this->targetProduct->agents()->getDisplayOptions();
                $realtyna['agencies'] = $agencies;
                $realtyna['agency_post_type'] = $this->targetProduct->agencies()->getPostType();
                $realtyna['agents'] = $agents;
                $realtyna['agent_post_type'] = $this->targetProduct->agents()->getPostType();
                $realtyna['agents_display_options'] = $agentsDisplayOptions;

            }
                            
            if ( View::class ){
                    
                $realtyna['idx_options'] = $this->getIdxOptions();
                $realtyna['idx_import'] = $this->getIdxImport();
    
                View::view( 'steps.third' , $realtyna );
    
                return;
            }
    
        }

        $this->gotoStep( 2 );

    }

    /**
     * validate third step wizard
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    static public function validateThirdStep()
    {

        $instance = new static();
        $idxOptions = $instance->getIdxOptions();

        return ( is_array( $idxOptions ) && !empty( $idxOptions ) ) ;

    }

    /**
     * Display fourth step wizard
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function fourthStep()
    {

            if ( !$this->validateThirdStep() ){

                if ( $this->targetProduct && $this->targetProduct->strtolowerName() == 'houzez' ) {

                    if (isset( $_GET['realtyna_idx_selected_agent'] ) && 
                        isset( $_GET['realtyna_idx_selected_agency'] ) && 
                        isset( $_GET['realtyna_idx_selected_agent_option'] ) && 
                        isset( $_GET['realtyna_idx_images_option'] ) && 
                        isset( $_GET['third_step_act'] ) && 
                        wp_verify_nonce( $_GET['third_step_act'], 'third_step_nonce' ) ){

                        $wpUserID = is_user_logged_in() ? get_current_user_id() : 0;
                        $params = array(
                            "agent" => $_GET['realtyna_idx_selected_agent'],
                            "agency" => $_GET['realtyna_idx_selected_agency'],
                            "agent_option" => $_GET['realtyna_idx_selected_agent_option'],
                            "image_option" => $_GET['realtyna_idx_images_option'],
                            "post_author" => $wpUserID
                        );

                        $this->setIdxOptions( $params );

                    }

                }

            }
    
            if ( $this->validateThirdStep() ){
                
                $credentials = $this->getCredentials();

                if ( !empty( $credentials['token'] ) ){

                    if ( View::class ){

                        $realtyna['mlsData'] = $this->getMlsData();
                        $realtyna['idxData'] = $this->getCredentials();
                        $realtyna['latestSync'] = get_option( self::REALTYNA_MLS_SYNC_UPDATE_TIME ) ?? '';

                        $api = new Api();
                
                        if ( !empty( $realtyna['mlsData'] ) && is_array( $realtyna['mlsData'] ) ){

                            $idxStatus = $api->getStatus( $credentials['token'] , $credentials['user_id']  );

                            if ( $idxStatus['status'] == 'OK' ){

                                $realtyna['mlsData']['status'] = 'Active';

                                $this->updateMlsData( $realtyna['mlsData'] );

                            }

	                        if ( isset($realtyna['mlsData']['repayment_url_save_timestamp']) &&
		                        $realtyna['mlsData']['status'] === 'pending' &&
		                        strpos($realtyna['mlsData']['checkout'], 'checkout.stripe.com') !== false &&
		                        ( ( time() - $realtyna['mlsData']['repayment_url_save_timestamp'] ) >= 82800 ) ){

		                        self::resetMlsData();

		                        $this->gotoStep( 4 );

	                        }

                            if ( $this->targetProduct ){

                                $property = $this->targetProduct->property();
    
                                if ( \method_exists( $property , 'countTotalImportedListings' ) ){
    
                                    $realtyna['totalImportedListings'] = $property->countTotalImportedListings();
    
                                }
								
                                if ( \method_exists( $property , 'countAvailableListings' ) ){
    
                                    $realtyna['totalAvailableListings'] = $property->countAvailableListings();
    
                                }
								
                                if ( \method_exists( $property , 'countTodayImportedListings' ) ){
    
                                    $realtyna['totalTodayImportedListings'] = $property->countTodayImportedListings();
    
                                }                                
    
                            }

                            View::view( 'dashboard' , $realtyna );

                            return true;

                        }else{

                            $apiProviders = $api->getProviders( $credentials['token'] );
        
                            $realtyna['providers'] = $apiProviders['message'];

                            if ( $apiProviders['status'] == 'OK' ){

                                View::view( 'steps.fourth' , $realtyna );

                                return true;

                            }

                        }
        
                    }

                }
                        
            }


        $this->gotoStep( 3 );

    }

    /**
     * validate fourth step wizard
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    static public function validateFourthStep()
    {

        $mlsData = static::getMlsData();

        return ( is_array( $mlsData ) && !empty( $mlsData ) ) ;

    }

    /**
     * Display fifth step wizard
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function fifthStep()
    {

        $this->gotoStep( 4 );

    }

    /**
     * Determine current steps to continue from there
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return int step number
     */
    private function determineCurrentStep()
    {

        if ( $this->validateThirdStep() )
            return  4;

        if ( $this->validateSecondStep() )
            return  3;

        return 1;

    }

    /**
     * Display payments views
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string payment view
     * 
     * @return void
     */
    private function showPayment( $view )
    {

        if ( $view == 'success' ){

            $mlsData = $this->getMlsData();

            if ( is_array( $mlsData ) && !empty( $mlsData ) && $mlsData['status'] == 'pending' ){

                $this->updateMlsData( [ "status" => "paid" ] );

            }

        }
        
        $this->showHeader();

        $realtyna['payment'] = $view;
        $realtyna['mlsData'] = $this->getMlsData();
        $realtyna['idxData'] = $this->getCredentials();

        if ( View::class ){

            View::view( 'dashboard' , $realtyna );

        }

        $this->showFooter();            

    }

    /**
     * Display step wizard view
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int Step number
     * 
     * @return void
     */
    private function showStepWizard( $step )
    {

        $data = [ "step" => $step ];

        if ( View::class ){

            View::view( 'steps.steps' , $data );

        }


    }

    /**
     * Display header view
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function showHeader()
    {

        $realtyna['plugin'] = self::getPluginDetails();
        $realtyna['isUpdateAvailable'] = false;
        $realtyna['updateLastVersion'] = $realtyna['plugin']['Version'];

        $now = time();
        $yesterday = strtotime( '-1 day', $now );
        $lastUpdateTime = get_option( REALTYNA_MLS_SYNC_SLUG . "_UpdateTime" ) ?: 0 ;

        if ( $lastUpdateTime < $yesterday && !defined('DISABLE_MLS_SYNC_UPDATE') ){
            
            if ( Updater::class && UpdaterWpPlugin::class ){

                $pluginSlug = $realtyna['plugin']['TextDomain'];
                $pluginVersion = $realtyna['plugin']['Version'];
    
                if ( !empty( $pluginSlug ) || !empty( $pluginVersion ) ){
    
                    $pluginUpdater = new  UpdaterWpPlugin( $pluginSlug , $pluginVersion );
                    $pluginUpdater->updateInquiry();
    
                    $realtyna['isUpdateAvailable'] = $pluginUpdater->isUpdateAvailable() ;
                    $realtyna['updateLastVersion'] = $pluginUpdater->getLastVersion() ;
        
                }
    
            }    

        }


        if ( View::class ){
            
            View::view( 'steps.header' , $realtyna );

        }

    }

    /**
     * Display footer view
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function showFooter()
    {

        if ( View::class ) {

            View::view( 'steps.footer' );

        }

    }

    /**
     * Response to ajax requests
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
	public function ajaxResponse()
    {
		
		$response = [ 'status' => 'ERROR' , 'message' => __('Invalid Data' , REALTYNA_MLS_SYNC_SLUG) ];
        
        if ( isset( $_POST['method'] ) && 
             !empty( $_POST['method'] ) &&
             isset( $_POST['nonce'] ) &&
			 wp_verify_nonce( $_POST['nonce'], 'realtyna_houzez_secret_nonce' ) 
			 )
        {
            
            switch ($_POST['method']) {

                case 'demo':
                    
                    if ( isset( $_POST['agent'] ) && 
                         isset( $_POST['agency'] ) &&
                         isset( $_POST['agent_option'] ) && 
                         isset( $_POST['image_option'] ) )
                    {
                        $wpUserID = is_user_logged_in() ? get_current_user_id() : 0;
                        $params = array(
                            "agent" => $_POST['agent'],
                            "agency" => $_POST['agency'],
                            "agent_option" => $_POST['agent_option'],
                            "image_option" => $_POST['image_option'],
                            "post_author" => $wpUserID
                        );

                        $response = $this->ajaxResponseDemoImport( $params );
                    }

                    break;

                case 'demo-progress':

                    $response = $this->ajaxResponseDemoProgress();
    
                    break;    
                
                case 'client-info':

                    if ( isset( $_POST['client_name'] ) && 
                         isset( $_POST['client_email'] ) && 
                         isset( $_POST['client_phone'] ) && 
                         isset( $_POST['client_role'] ) )
                    {
                        $params = array(
                            "name" => $_POST['client_name'],
                            "email" => $_POST['client_email'],
                            "phone_number" => $_POST['client_phone'],
                            "role" => $_POST['client_role']
                        );

                        $response = $this->ajaxResponseClientInfo( $params );
                    }

                    break;

                case 'request-mls':

                    if ( isset( $_POST['provider'] ) && 
                         isset( $_POST['state'] ) )
                    {

                        $params = array(
                            "provider" => $_POST['provider'],
                            "state" => $_POST['state']
                        );
        
                        $response = $this->ajaxResponseRequestMLS( $params );    

                    }
    
                    break;    

                case 'select-mls':

                    if ( isset( $_POST['mls_id'] ) && 
                         isset( $_POST['mls_name'] ) &&
                         isset( $_POST['mls_slug'] ) )
                    {
    
                        $params = array(
                            "id" => $_POST['mls_id'],
                            "name" => $_POST['mls_name'],
                            "slug" => $_POST['mls_slug'],
                            "status" => "none",
                            "checkout" => ""
                        );
                                    
                        $response = $this->ajaxResponseSelectMLS( $params );    
    
                    }
        
                    break;

                case 'settings':

                    if ( isset( $_POST['agency'] ) && 
                        isset( $_POST['apply_agency_to_all'] ) &&
                        isset( $_POST['agent'] ) &&
                        isset( $_POST['apply_agent_to_all'] ) &&
                        isset( $_POST['agent_option'] ) &&
                        isset( $_POST['apply_agent_display_option_to_all'] ) &&
                        isset( $_POST['image_option'] ) )
                    {
        
                        $params = array(
                            "agency" => $_POST['agency'],
                            "apply_agency_to_all" => $_POST['apply_agency_to_all'],
                            "agent" => $_POST['agent'],
                            "apply_agent_to_all" => $_POST['apply_agent_to_all'],
                            "agent_option" => $_POST['agent_option'],
                            "apply_agent_display_option_to_all" => $_POST['apply_agent_display_option_to_all'],
                            "image_option" => $_POST['image_option']
                        );
                                        
                        $response = $this->ajaxResponseSettings( $params );    
        
                    }
            
                    break;    

                case 'remove-demo':
            
                    if ( $this->ajaxResponseRemoveDemo() ) {

                        $response["status"] = "OK";
                        $response["message"] = __("Requested Action has been done!" , REALTYNA_MLS_SYNC_SLUG );
                            
                    }

                    break;    

                case 'update-plugin':
            
                    if ( $this->ajaxResponseUpdatePlugin() ) {
    
                        $response["status"] = "OK";
                        $response["message"] = __("Plugin updated Successfully!" , REALTYNA_MLS_SYNC_SLUG );
                                
                    }
    
                    break;    
                            
                default:
                    
                    $response["message"] = __( "Unknown Method!" , REALTYNA_MLS_SYNC_SLUG );

                    break;                
            }    
                
        }
        
        die( json_encode( $response ) );

    }

    /**
     * Check Demo import is in progress or failed
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int $waitfor wait for seconds before check
     * 
     * @return array response array
     */
    private function ajaxResponseDemoProgress( $waitFor = 0 )
    {

        $response = [ "status" => "ERROR" , "message" => __("Demo Import Error!" , REALTYNA_MLS_SYNC_SLUG) ];

        if ( $this->targetProduct ){
            
            if ( $waitFor > 0 ){

                sleep( $waitFor );
                
            }

            $countImportedDemo = $this->targetProduct->property()->countImportedProperties( true );

            if ( $countImportedDemo > 0 ){

                $response['status'] = "OK";
                $response['message'] = __( "Importing listings is in progress and will be completed in a few minutes!<br>You can proceed with next step or check imported Listings!" , REALTYNA_MLS_SYNC_SLUG );
    
            }
    
        }
        
        return $response;

    }

    /**
     * Process ajax response to update-plugin Request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array
     */
    private function ajaxResponseUpdatePlugin()
    {

        $response = [ "status" => "ERROR" , "message" => __("Update Error: Contact With Tehnical support" , REALTYNA_MLS_SYNC_SLUG) ];

        if ( self::updatePlugin() ){
            
            $response['status'] = "OK";
            $response['message'] = __( "Plugin Updated Successfully!" , REALTYNA_MLS_SYNC_SLUG );
    
        }
        
        return $response;

    }

    /**
     * Process ajax response to remove-demo Request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    private function ajaxResponseRemoveDemo()
    {

        if ( $this->targetProduct ){

            $this->targetProduct->removeProperties( true );
        
            return $this->deleteIdxImport();
    
        }

        return false;

    }

    /**
     * Process ajax response to settings Request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array array of needed data
     * 
     * @return array
     */
    private function ajaxResponseSettings( $params )
    {

        $response = [ "status" => "ERROR" , "message" => __("Unknown Error!" , REALTYNA_MLS_SYNC_SLUG) ];

        if (!empty( $params['agency'] ) &&
            !empty( $params['agent'] ) &&
            !empty( $params['agent_option'] ) &&
            isset( $params['image_option'] ) )
        {
                        
            $newOptions = array();
            $newOptions["agency"] = $params['agency'];
            $newOptions["agent"] = $params['agent'];
            $newOptions["agent_option"] = $params['agent_option'];
            $newOptions["image_option"] = $params['image_option'];

            $updateResult = $this->updateIdxOptions( $newOptions ) ;

            $response['status'] = ( !empty( $updateResult ) ) ? "OK" : "ERROR";
            $response['message'] = ( $response['status'] == "OK" ) ? __("Settings Updated Successfully!" , REALTYNA_MLS_SYNC_SLUG ) : __("Settings Update Error!" , REALTYNA_MLS_SYNC_SLUG );

            if ( $this->targetProduct ){

                if ( \method_exists( $this->targetProduct , 'updatePropertiesAgency' ) ) {

                    if ( $params['apply_agency_to_all'] == "true" ){

                        $this->targetProduct->updatePropertiesAgency( $params['agency'] );

                    }
    
                }

                if ( \method_exists( $this->targetProduct , 'updatePropertiesAgents' ) ) {

                    if ( $params['apply_agent_to_all'] == "true" ){

                        $this->targetProduct->updatePropertiesAgents( $params['agent'] );

                    }

                }

                if ( \method_exists( $this->targetProduct , 'updatePropertiesAgentDisplayOption' ) ) {

                    if ( $params['apply_agent_display_option_to_all'] == "true" ){

                        $this->targetProduct->updatePropertiesAgentDisplayOption( $params['agent_option'] );

                    }

                }            

            }

        }else{
            $response["message"] = __("Invalid Params!" , REALTYNA_MLS_SYNC_SLUG) ;
        }

        return $response ;

    }

    /**
     * Process ajax response to select-mls Request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array array of needed data
     * 
     * @return array
     */
    private function ajaxResponseSelectMLS( $params )
    {

        $response = [ "status" => "ERROR" , "message" => __("Unknown Error!" , REALTYNA_MLS_SYNC_SLUG) ];

        if (!empty( $params['id'] ) &&
            !empty( $params['name'] ) &&
            !empty( $params['slug'] ) )
        {  

            $credentials = $this->getCredentials();

            if ( $credentials !== false ){                

                if ( Api::class ){
                    
                    $mlsData = $this->getMlsData();

                    $api = new Api();

                    $checkout = $api->checkout( $credentials['token'] , $credentials['user_id'] , (int) $params['id'] );
                    
                    if ( is_array( $checkout ) && $checkout['status'] == "OK" && !empty( $checkout['message'] ) ){

                        $params['status'] = "pending";
                        $params['checkout'] = $checkout['message'];
                        $params['repayment_url_save_timestamp'] = time();

                        $this->setMlsData( $params );

                        $response['status'] = 'OK';                        
                        $response['message'] = '';
                        $response['payment_link'] = $checkout['message'];

                    }else{

                        $response['message'] = __('It seams there is some issues with Payment system.<br>Please call us to resolve the issue' ,REALTYNA_MLS_SYNC_SLUG )  ;

                    }

                }else{

                    $response['message'] = __("Missed Functionality!" , REALTYNA_MLS_SYNC_SLUG ) ;

                }
            
            }else {
                $response["message"] = __("Invalid Credentials!" , REALTYNA_MLS_SYNC_SLUG) ;
            }
    

        }else{
            $response["message"] = __("Invalid Params!" , REALTYNA_MLS_SYNC_SLUG) ;
        }

        return $response ;

    }

    /**
     * Process ajax response to request-mls Request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array array of needed data
     * 
     * @return array
     */
    private function ajaxResponseRequestMLS( $params )
    {

        $response = [ "status" => "ERROR" , "message" => __("Unknown Error!" , REALTYNA_MLS_SYNC_SLUG) ];

        if (!empty( $params['provider'] ) &&
            !empty( $params['state'] ) )
        {

            $credentials = $this->getCredentials();

            if ( !empty( $credentials['token'] ) && is_numeric( $credentials['user_id'] ) ){

                $api = new Api();

                $result = $api->requestProvider( $credentials['token'] , $credentials['user_id'] , $params['provider'] , $params['state'] );
    
                if ( $result['status'] == 'OK' ){
                    
                    $mlsData = array();
                    $mlsData['id'] = 0;
                    $mlsData['name'] = $params['provider'];
                    $mlsData['slug'] = '';
                    $mlsData['status'] = 'none';
                    $mlsData['checkout'] = '';

                    $this->setMlsData( $mlsData );

                    $response['status'] = 'OK';
                    $response['message'] = __( 'Your Request for MLS has been sent successfully!<br>Our team will contact you<br>If you need more information you can contact us: sync@realtyna.net' , REALTYNA_MLS_SYNC_SLUG );
    
                }elseif ( isset( $result['message'] ) ) {
    
                    $response['message'] = $result['message'];
    
                }    
    
            }else{
                $response['message'] = __( 'Credentials Error!' , REALTYNA_MLS_SYNC_SLUG );
            }

        }else{
            $response["message"] = __("Invalid Params!" , REALTYNA_MLS_SYNC_SLUG) ;
        }

        return $response ;

    }

    /**
     * Process ajax response to client-info Request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array array of needed data
     * 
     * @return array
     */
    private function ajaxResponseClientInfo( $params )
    {

        $response = [ "status" => "ERROR" , "message" => __("Unknown Error!" , REALTYNA_MLS_SYNC_SLUG) ];

        if (!empty( $params['name'] ) &&
            !empty( $params['email'] ) &&
            !empty( $params['phone_number'] ) &&
            !empty( $params['role'] ) )
        {

            if ( $this->getCredentials() === false ){

                $params['source'] = $this->getTargetProductOption();
                
                if ( !empty( $params['source'] ) ){

                    $api = new Api();

                    $result = $api->register( $params );
    
                    if ( $result['status'] == 'OK' && is_array( $result['message'] ) ){
    
                        $this->setCredentials( $result['message'] );
    
                        $response = (array) $result['message'];
                        $response['status'] = 'OK';
    
                    }elseif ( isset( $result['message'] ) ) {
                        $response['message'] = $result['message'];
                    }
    
                }else{

                    $response['message'] = __( "Target Product not detected!" , REALTYNA_MLS_SYNC_SLUG );

                }
    
            }else{
                $response['status'] = "OK";
                $response['message'] = __("Client Info already exists!", REALTYNA_MLS_SYNC_SLUG ) ;
            }

        }else{
            $response["message"] = __("Invalid Params!" , REALTYNA_MLS_SYNC_SLUG) ;
        }

        return $response ;

    }

    /**
     * Process ajax response to demo Request
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array array of needed data
     * 
     * @return array
     */
    private function ajaxResponseDemoImport( $params )
    {

        $response = [ "status" => "ERROR" , "message" => __("Unknown Error!" , REALTYNA_MLS_SYNC_SLUG) ];

        if (!empty( $params['agent'] ) &&
            !empty( $params['agency'] ) &&
            !empty( $params['agent_option'] ) &&
            isset( $params['image_option'] ) )
        {
            if ( $this->getIdxImport() !== false ){

                $response['message'] = __("Demo Listing Already Imported!" , REALTYNA_MLS_SYNC_SLUG);
                
            }elseif( $this->getTargetProduct() ){
                error_log("demo import started");

                $this->setIdxOptions( $params );

                $this->setMaxExecutionTime();
    
                $additionalFields = [];
             
                if ( $this->targetProduct->strtolowerName() == 'houzez' ){
   
                   $additionalFields = [ 
                       "fave_property_agency" => $params['agency'] ,
                       "fave_agents" => $params['agent'] ,
                       "fave_agent_display_option" => $params['agent_option'] 
                   ];
      
                }else{
   
                   $additionalFields = [ 
                       "property_agency" => $params['agency'] ,
                       "agents" => $params['agent'] ,
                   ];
   
                }
           
                $importOptions = [
                    "generate_thumbs_images" => false,
                    "max_images_import" => ( $params['image_option'] > 0 ) ? 50 : 20 ,
                    "max_property_import" => ( $params['image_option'] > 0 ) ? 50 : 20 ,
                    "use_external_images" => ( $params['image_option'] == 1 ||  $params['image_option'] == 2 ),
                    "use_external_thumbnail" => ( $params['image_option'] == 2 ) ,
                    "post_author" => $params['post_author']
                ];
        
                $mapper = new Mapper( $this->getCredentialToken() , self::DEMO_PROVIDER , $additionalFields , $importOptions );
                $result = $mapper->run();
                error_log("demo import result:" . var_export( $result , true));
        
                if ( !empty( $result ) ) {
        
                    if ( is_numeric( $result ) ){
        
                        $response['status'] = "OK";
                        $response['message'] = $result . " " . __('Properties has been added as Demo Listings!' , REALTYNA_MLS_SYNC_SLUG );
    
                        $this->setIdxImport( true );
        
                    }else{
                        $response['message'] = $result;
                    }
        
                }else{
                    $response['message'] = __('Unknown Result!' , REALTYNA_MLS_SYNC_SLUG ) ;
                }
    
            }    

        }else{
            $response["message"] = __("Invalid Params!" , REALTYNA_MLS_SYNC_SLUG ) ;
        }

        return $response ;

    }

	/**
	* Remove Listings cron job
	*
	* @author Chris A <chris.a@realtyna.net>
	* 
	* @return void
	*/
	public function purgeListings()
	{
				
		if ( $this->targetProduct && is_object( $this->targetProduct ) ){
			
			if ( \method_exists( $this->targetProduct , 'purgeListings' ) ){
				
				$this->targetProduct->purgeListings();
				
			}else error_log("App: PurgeListings : error call core");
			
		}else error_log("App: PurgeListings : error in object");
		
	}

	/**
	* Remove Attachments cron job
	*
	* @author Chris A <chris.a@realtyna.net>
	* 
	* @return void
	*/
	public function purgeAttachments()
	{
		
		if ( $this->targetProduct && is_object( $this->targetProduct ) ){
			
			if ( \method_exists( $this->targetProduct , 'purgeAttachments' ) ){
				
				$this->targetProduct->purgeAttachments();
				
			}else error_log("App: purgeAttachments : error call core ");
			
		}else error_log("App: purgeAttachments : error in object ");
		
	}
    
    /**
     * Update Plugin if it's available
     * 
     * @see UpdaterWpPlugin
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    static public function updatePlugin()
    {
        
        $updateResult = false;

        if ( Updater::class && UpdaterWpPlugin::class && !defined('DISABLE_MLS_SYNC_UPDATE') ){

            $pluginInfo = self::getPluginDetails();

            $pluginSlug = $pluginInfo['TextDomain'];
            $pluginVersion = $pluginInfo['Version'];

            if ( !empty( $pluginSlug ) || !empty( $pluginVersion ) ){

                $pluginUpdater = new UpdaterWpPlugin( $pluginSlug , $pluginVersion );
                $pluginUpdater->updateInquiry();

                if ( $pluginUpdater->isUpdateAvailable() ){

                    $updateResult = $pluginUpdater->updatePlugin() ;
                    
                    if ( $updateResult ){
						
                        return update_option( REALTYNA_MLS_SYNC_SLUG . "_UpdateTime" , time() );

                    }
                    
                }
    
            }

        }        

        return $updateResult;

    }

    /**
     * Auto Update Plugin Functionality
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    public function autoUpdatePlugin()
    {
        
        $now = time();
        $yesterday = strtotime( '-1 day', $now );
        $lastUpdateTime = get_option( REALTYNA_MLS_SYNC_SLUG . "_UpdateTime" ) ?: 0 ;

        if ( defined('DEBUG_LOG') && DEBUG_LOG ) {
            error_log("last update:" . $lastUpdateTime);
        }

        if ( $lastUpdateTime < $yesterday ){
            return self::updatePlugin();
        }
        
        return false;

    }

    /**
     * Store mls data to DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array array of mls data
     * 
     * @return bool
     */
    private function setMlsData( $params )
    {

        if ( is_array( $params ) && !empty( $params ) ){
            return update_option( self::REALTYNA_MLS_DATA , json_encode( $params ) );
        }

        return false;

    }

    /**
     * Get mls data from DB
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array
     */
    static public function getMlsData()
    {

        return ( get_option( self::REALTYNA_MLS_DATA ) ? json_decode( get_option( self::REALTYNA_MLS_DATA ) , true ) : false );

    }

    /**
     * Update mlsData in DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array array of mlsData
     * 
     * @return bool
     */
    private function updateMlsData( $params )
    {

        $mlsData = $this->getMlsData();

        if ( $mlsData !== false && !empty( $params ) ){
            
            foreach ( $params as $paramKey => $paramValue ){
                
                if ( isset( $mlsData[ $paramKey ] ) ){

                    $mlsData[ $paramKey ] = $paramValue;

                }

            }

            return $this->setMlsData( $mlsData );

        }

        return false;

    }

    /**
     * Reset MLS Data
     * 
     * @author Chris A <chris.a@realtyna.net>
     * @static
     * 
     * @return bool
     */
    static public function resetMlsData()
    {

        if ( function_exists('delete_option') ){

            return delete_option( self::REALTYNA_MLS_DATA );
        }

        return false;

    }

    /**
     * Extract Provider from mls data in DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string
     */
    private function getMlsProvider()
    {

        return $this->getMlsItem( 'slug' );

    }

    /**
     * Extract Needed Item from mls data in DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Name of selected mls data
     * 
     * @return string
     */
    private function getMlsItem( $item )
    {

        if ( empty( trim( $item ) ) )
            return '';

        $mls = $this->getMlsData();

        return ( $mls !== false  && isset( $mls[ $item ] ) ) ? $mls[ $item ] : '';

    }    

    /**
     * Store Credentials in DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array array of credentials info
     * 
     * @return bool
     */
    private function setCredentials( $params )
    {

        if ( is_array( $params ) && !empty( $params ) ){

            $param['wp_user_id'] = is_user_logged_in() ? get_current_user_id() : 0 ;

            return update_option( self::REALTYNA_IDX_CREDENTIAL , json_encode( $params ) );
            
        }

        return false;

    }

    /**
     * Get Credentials info from DB
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array
     */
    static public function getCredentials()
    {

        return ( get_option( self::REALTYNA_IDX_CREDENTIAL ) ? json_decode( get_option( self::REALTYNA_IDX_CREDENTIAL ) , true ) : false );

    }

    /**
     * Extract Token from Credentials info in DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string
     */
    private function getCredentialToken()
    {

        return $this->getCredentialItem( 'token' );

    }

    /**
     * Extract User ID from Credentials info in DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string
     */
    private function getCredentialUser()
    {

        return $this->getCredentialItem( 'user_id' );

    }

    /**
     * Extract Selected Item from Credentials info in DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Name of Selected Item in Credentials info
     * 
     * @return string
     */
    private function getCredentialItem( $item )
    {

        if ( empty( trim( $item ) ) )
            return '';

        $credentials = $this->getCredentials();

        return ( $credentials !== false  && isset( $credentials[ $item ] ) ) ? $credentials[ $item ] : '';

    }

    /**
     * Update IDX Options to DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array array of idx options
     * 
     * @return bool
     */
    private function updateIdxOptions( $params )
    {

        $options = $this->getIdxOptions();

        if ( !empty( $options ) && !empty( $params ) ){
            
            foreach ( $params as $paramKey => $paramValue ){
                
                if ( isset( $options[ $paramKey ] ) ){

                    $options[ $paramKey ] = $paramValue;

                }

            }

            return $this->setIdxOptions( $options );

        }

        return false;

    }

    /**
     * Store IDX Options to DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param array array of idx options
     * 
     * @return bool
     */
    private function setIdxOptions( $params )
    {

        if ( is_array( $params ) && !empty( $params ) ){

            $currentOptions = $this->getIdxOptions();

            if ( is_array( $currentOptions ) && !empty( $currentOptions ) ){

                $arrayDiff = array_diff_assoc( $params , $currentOptions );

                if ( empty( $arrayDiff ) )
                    return true;

            }

            return update_option( self::REALTYNA_IDX_OPTIONS , json_encode( $params ) );
        }

        return false;

    }

    /**
     * Get IDX Options from DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array
     */
    private function getIdxOptions()
    {

        return ( get_option( self::REALTYNA_IDX_OPTIONS ) ? json_decode( get_option( self::REALTYNA_IDX_OPTIONS ) , true ) : false );

    }

    /**
     * Store IDX Import status to DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param bool
     * 
     * @return bool
     */
    private function setIdxImport( $imported )
    {

        return update_option( self::REALTYNA_IDX_IMPORT , $imported );

    }

    /**
     * Get IDX Import status from DB
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    private function getIdxImport()
    {

        return ( get_option( self::REALTYNA_IDX_IMPORT ) ? get_option( self::REALTYNA_IDX_IMPORT ) : false );

    }

    /**
     * Remove IDX Import from DB
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    static public function deleteIdxImport()
    {

        if ( function_exists('delete_option') )
            return delete_option( self::REALTYNA_IDX_IMPORT );

        return false;

    }

    /**
     * Reset Client from DB
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    static public function resetClient()
    {

        if ( function_exists('delete_option') ){

            return ( delete_option( self::REALTYNA_IDX_CREDENTIAL ) && delete_option( self::REALTYNA_IDX_OPTIONS ) );

        }

        return false;

    }
    
    /**
     * Get Payment Success URL
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string
     */
    private function getPaymentSuccessURL()
    {

        return site_url() . "/wp-admin/admin.php?page=" . REALTYNA_MLS_SYNC_SLUG . "&payment=success";

    }

    /**
     * Get Payment Cancel URL
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string
     */
    private function getPaymentCancelURL()
    {

        return site_url() . "/wp-admin/admin.php?page=" . REALTYNA_MLS_SYNC_SLUG . "&payment=cancel";

    }


    /**
     * Load Neede Styles & CSS for plugin
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function loadPluginStyles()
    {

        wp_enqueue_style( 'eweqwe-css'  , plugins_url( '/assets/css/styles.css' , REALTYNA_MLS_SYNC_PLUGIN_FILE ) );
 
    }

    /**
     * Load Neede Scripts for plugin
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function loadPluginScripts()
    {

		wp_register_script( 'ajaxHandle', plugins_url( '/assets/js/realtyna_mls_sync.js', REALTYNA_MLS_SYNC_PLUGIN_FILE ),  array(),  false, true );
		wp_enqueue_script( 'ajaxHandle' );
		wp_localize_script( 'ajaxHandle', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )  );
 
    }

    /**
     * Dispaly Notice : Realtyna MLS Sync Error : There is no supported Theme/Plugin
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function noticeNotSupportedProduct()
    {

        $this->notice( 'Realtyna MLS Sync Error : There is no supported Theme/Plugin' , "error" );

    }

    /**
     * Dispaly Notice : Realtyna MLS Sync Error : Could not set a Target Product to Sync with the MLS
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function noticeFailedTargetProduct()
    {

        $this->notice( 'Realtyna MLS Sync : Could not set a Target Product to Sync with the MLS, <a href="admin.php?page=realtyna-mls-sync">Run Setup Wizard</a>' , "warning" );

    }

    /**
     * Dispaly Notice : Realtyna MLS Sync Error : Target Product is not Active to Sync with the MLS
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function noticeTargetProductIsNotActive()
    {

        $this->notice( 'Realtyna MLS Sync Error : Target Product is not Active to Sync with the MLS' , "error" );

    }

    /**
     * Display External Media in Wordpress
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Name of Media File
     * @param int ID of Media file also known as attachment ID
     * 
     * @return string External File URL
     */
    public function handleExternalMedia( $file , $attachmentId )
    {
        
        if (  empty( $file ) ) {

            $post = get_post( $attachmentId );

            return ( !empty( $post->guid ) ) ? $post->guid : $file;
        
        }

        return $file;

    }

    /**
     * Display External Media as Feature Image Thumbnail in Wordpress
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string HTML Code of current feature image
     * @param int Post ID
     * @param int Thumbnail Image ID
     * @param string Name of defined Images Size
     * @param array array of embeded Attributes
     * 
     * @return string IMG tag containing External File Link
     */
    public function handleExternalThumbnail( $html, $postId, $postThumbnailId, $size, $attr )
    {

        if ( ! get_post_meta( $postThumbnailId, self::EXTERNAL_IMAGES_MARK , true ) ){
            
            return $html;
            
        }

        $post = get_post( $postId );
        
        $alt = isset( $post->post_title ) ? $post->post_title : '';
        $class = isset( $attr['class'] ) ? $attr['class'] : '';

        $thumb = get_post( $postThumbnailId );

        if ( substr( $thumb->guid , strlen( site_url() ) ) == site_url() ){
            
            $src = wp_get_attachment_image_src( $postThumbnailId, $size );

            $url = isset( $src[0] ) ? $src[0] : '' ;

        }else{
            
            $url = $thumb->guid;

        }

        $html = '<img src="' . $url . '" alt="' . $alt . '" class="' . $class . '" />';

        return $html;
    
    }

    /**
     * Display External Media as Feature Image Thumbnail in Wordpress Edit Page
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string HTML Code of current feature image
     * @param int Media File ID or Attachment ID
     * @param string Name of defined Images Size
     * @param string icon
     * @param array array of embeded Attributes
     * 
     * @return string IMG tag containing External File Link
     */
    public function fixExternalThumbnailsInEditPage( $html, $attachmentId, $size, $icon , $attr )
    {

        if ( ! get_post_meta( $attachmentId, self::EXTERNAL_IMAGES_MARK , true ) ){
            
            return $html;

        }

        $thumb = get_post( $attachmentId );

        if ( substr( $thumb->guid , strlen( site_url() ) ) == site_url() ){
            
            return $html;

        }
            
        $modified_html = '<img src="' . $thumb->guid . '" alt="" loading="lazy" />';

        return $modified_html;
    
    }

    /**
     * Dispaly Notice : Realtyna MLS Sync Error : WP CronJobs should be enable for purge functionality
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function checkCronJob()
    {

        if ( defined( 'DISABLE_WP_CRON' ) && !empty( DISABLE_WP_CRON ) ){
		
			$this->notice( 'Realtyna MLS Sync Error : WP CronJobs should be enable for purge functionality ' , "warning" );
		
		}

    }

	/**
	 * Adds a custom cron schedule.
	 *
	 * @author Chris A <chris.a@realtyna.net>
	 *
	 * @param array $schedules An array of non-default cron schedules.
     * 
	 * @return array Filtered array of non-default cron schedules.
	 */
	public function cronSchedule( $schedules )
	{
		
		$interval = 30 * MINUTE_IN_SECONDS;
		
		if ( defined( 'REALTYNA_MLS_SYNC_CRON_INTERVAL' ) && !empty( REALTYNA_MLS_SYNC_CRON_INTERVAL ) && is_numeric( REALTYNA_MLS_SYNC_CRON_INTERVAL ) ){
			
			$interval = REALTYNA_MLS_SYNC_CRON_INTERVAL * MINUTE_IN_SECONDS;
			
		}
		
		$schedules[ 'realtyna-mls-sync-interval' ] = array( 'interval' => $interval, 'display' => __( "Every {$interval} seconds", REALTYNA_MLS_SYNC_SLUG ) );
		
		return $schedules;
		
	}

    /**
     * Force Max Execution Time to minimum needed seconds
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function setMaxExecutionTime()
    {

        $currentTime = ini_get("max_execution_time");

        if ( ( $currentTime > 0 ) && ( $currentTime < self::MINIMUM_EXECUTION_TIME_FOR_DATA_IMPORT ) ){
            ini_set( 'max_execution_time' , self::MINIMUM_EXECUTION_TIME_FOR_DATA_IMPORT );
        }

    }

    /**
     * Move User between Steps
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int Step Number
     * 
     * @return void
     */
    private function gotoStep( $step )
    {

        $step = ( is_numeric( $step ) ? $step : 1 );
        
        $this->redirect( "admin.php?page=" . REALTYNA_MLS_SYNC_SLUG . "&step=" . $step );

    }

    /**
     * Get External Images Mark
     * 
     * @static
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return string
     */
    static public function getExternalImagesMark()
    {
        
        return self::EXTERNAL_IMAGES_MARK;

    }

    /**
     * Initialize REST Routes
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    private function initRestRoutes()
    {
        
         if ( REST::class ){

             $idxOptions = $this->getIdxOptions();

             $agency = ( is_array( $idxOptions ) && isset( $idxOptions['agency'] ) ) ? $idxOptions['agency'] : null ;
             $agent = ( is_array( $idxOptions ) && isset( $idxOptions['agent'] ) ) ? $idxOptions['agent'] : null ;
             $agentOption = ( is_array( $idxOptions ) && isset( $idxOptions['agent_option'] ) ) ? $idxOptions['agent_option'] : null ;
             $imageOption = ( is_array( $idxOptions ) && isset( $idxOptions['image_option'] ) ) ? $idxOptions['image_option'] : null ;
            
             $additionalFields = [];
             
             if ( $this->targetProduct && $this->targetProduct->strtolowerName() == 'houzez' ){

                $additionalFields = [ 
                    "fave_property_agency" => $agency ,
                    "fave_agents" => $agent ,
                    "fave_agent_display_option" => $agentOption
                ];
   
             }else{

                $additionalFields = [ 
                    "property_agency" => $agency ,
                    "agents" => $agent ,
                ];

             }

             $wpUserID = $idxOptions['post_author'] ?? 0 ;
             $importOptions = [
                 "generate_thumbs_images" => false,
                 "max_images_import" => ( $imageOption > 0 ) ? 50 : 20 ,
                 "max_property_import" => -1 ,
                 "use_external_images" => ( $imageOption == 1 ||  $imageOption == 2 || $imageOption == null ),
                 "use_external_thumbnail" => ( $imageOption == 2 || $imageOption == null  ),
                 "post_author" => $wpUserID
             ];
            
             $idxRest = new REST( $this->getCredentialToken() , $this->getMlsProvider() , $additionalFields , $importOptions );

        }

    }
    
    /**
     * Get plugin Details
     * 
     * @static
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array array of plugin details
     */
    static public function getPluginDetails()
    {

        return [ "Version" => REALTYNA_MLS_SYNC_VERSION , "TextDomain" => REALTYNA_MLS_SYNC_SLUG ];

    }

    /**
     * Check Stripe CallBack
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return bool
     */
    private function isStripeCallBack()
    {
        
        $mlsData = $this->getMlsData();

        if ( !empty( $mlsData ) && is_array( $mlsData ) ){
                
            return ( $mlsData['id'] > 0 && $mlsData['status'] == 'pending' && !empty( $mlsData['checkout'] ) ) ;

        }

        return false;

    }

    /**
     * Redirect User to specefic URL
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string URL Address
     * 
     * @return void
     */
	private function redirect( $location )
    {

        if (!headers_sent()) {

            header('Location: ' . $location);
            exit;

        } else {

            echo '
                    <script type="text/javascript">
                        window.location.href="' . $location . '";
                    </script>
                    <noscript>
                        <meta http-equiv="refresh" content="0;url=' . $location . '" />
                    </noscript>
                ';

        }

    }

    /**
     * Display Wordpress Notice
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string Notice Text
     * @param string Notice Type an index of $noticeType Array
     * @param bool Is notice Dismissible?
     * 
     * @return void
     */
    private function notice( $text , $type = 'info' , $dismissible = false)
    {

        if ( !empty( trim( $text ) ) || !empty( trim( $type ) ) ){
            
            $noticeClass = ( in_array( $type , $this->noticeTypes ) ) ? 'notice-' . $type : '';

            $isDismissible = $dismissible ? 'is-dismissible' : '' ;

            echo    '
                    <div class="notice ' . $noticeClass . ' ' . $isDismissible .  ' "  style="margin-top: 10px;margin-bottom: 10px;">
                        
                        <p>' . __( $text , REALTYNA_MLS_SYNC_SLUG ) . '</p>
                
                    </div>
                    ';
        }

    }

    /**
     * Set Scheduler for Plugin
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function setScheduler()
    {

		if ( wp_get_scheduled_event( 'realtyna_mls_sync_update_plugin' ) === false ){
			
			if ( !wp_next_scheduled ( 'realtyna_mls_sync_update_plugin' ) ) {
				
				wp_schedule_event( time(), 'twicedaily', 'realtyna_mls_sync_update_plugin' );
				
			}
			
		}
		
		if ( wp_get_scheduled_event( 'realtyna_mls_sync_purge_listings' ) === false ){
			
			if ( !wp_next_scheduled ( 'realtyna_mls_sync_purge_listings' ) ) {
				
				wp_schedule_event(  strtotime("+2 minutes") , 'realtyna-mls-sync-interval', 'realtyna_mls_sync_purge_listings' );
				
			}
			
		}

		if ( wp_get_scheduled_event( 'realtyna_mls_sync_purge_attachments' ) === false ){
			
			if ( !wp_next_scheduled ( 'realtyna_mls_sync_purge_attachments' ) ) {
				
				wp_schedule_event( time(), 'realtyna-mls-sync-interval', 'realtyna_mls_sync_purge_attachments' );
				
			}
		
		}

    }

    /**
     * Plugin activation event handler
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function activatePlugin()
    {

        $this->setScheduler();

    }

    /**
     * Plugin deactivation event handler
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return void
     */
    public function deactivatePlugin()
    {

    //     if ( Realtyna\Sync\Addons\Dashboard\Dashboard::class ){

    //         $dashboard = new \Realtyna\Sync\Addons\Dashboard\Dashboard( $this->getCredentialToken() , $this->getCredentialUser() );

    //         $dashboard->deactivationSignal();

    //     }

    }

}