<?php
/**
 * Plugin Name: Houzez WooCommerce Addon
 * Plugin URI:  https://wordpress.org/plugins/houzez-woo-addon/
 * Description: Add woocommerce functionality to houzez theme
 * Version:     1.1.1
 * Author:      Favethemes
 * Author URI:  http://themeforest.net/user/favethemes
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: houzez-woo-addon
 * Domain Path: /languages
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Houzez_WooCommerce' ) ) :

    final class Houzez_WooCommerce {

        /**
         * Plugin's current version
         *
         * @var string
         */
        public $version;

        /**
         * Minimum Houzez Version
         *
         * @since 1.0.0
         *
         * @var string Minimum Houzez version required to run the plugin.
         */
        const MINIMUM_HOUZEZ_VERSION = '2.1.0';

        /**
         * Plugin Name
         *
         * @var string
         */
        public $plugin_name;

        /**
         * Plugin's instance.
         *
         * @var Houzez_WOO
         */
        protected static $_instance;

        /**
         * Constructor function.
         */
        public function __construct() {

            $this->plugin_name = 'houzez-woo';
            $this->version     = '1.0.0';

            // Check if Houzez Theme Functionality installed and activated
            if ( ! did_action( 'houzez_core' ) ) {
                add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
                return;
            }

            // Check if Houzez Theme Functionality installed and activated
            /*if ( ! class_exists( 'WooCommerce' ) ) {
                add_action( 'admin_notices', array( $this, 'admin_notice_missing_woocommerce_plugin' ) );
                return;
            }*/

            // Check for required Elementor version
            if ( ! version_compare( HOUZEZ_VERSION, self::MINIMUM_HOUZEZ_VERSION, '>=' ) ) {
                add_action( 'admin_notices', [ $this, 'admin_notice_minimum_houzez_version' ] );
                return;
            }

            $this->define_constants();
            $this->include_files();
            $this->init_hooks();

            do_action( 'houzez_woocommerce_loaded' );

        }

        /**
         * Provides instance.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /**
         * Initialize hooks.
         */
        public function init_hooks() {
            add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
            register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
            register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivate' ) );
        }

        /**
         * Defines constants.
         */
        protected function define_constants() {

            if ( ! defined( 'HOUZEZ_WOO_VERSION' ) ) {
                define( 'HOUZEZ_WOO_VERSION', $this->version );
            }

            if ( ! defined( 'HOUZEZ_WOO_PLUGIN_FILE' ) ) {
                define( 'HOUZEZ_WOO_PLUGIN_FILE', __FILE__ );
            }

            if ( ! defined( 'HOUZEZ_WOO_DIR' ) ) {
                define( 'HOUZEZ_WOO_DIR', plugin_dir_path( __FILE__ ) );
            }

            if ( ! defined( 'HOUZEZ_WOO_URL' ) ) {
                define( 'HOUZEZ_WOO_URL', plugin_dir_url( __FILE__ ) );
            }

            if ( ! defined( 'HOUZEZ_WOO_BASENAME' ) ) {
                define( 'HOUZEZ_WOO_BASENAME', plugin_basename( __FILE__ ) );
            }

        }

        /**
         * Admin notice
         *
         * Warning when the site doesn't have Houzez Theme - Functionality installed or activated.
         *
         * @since 1.0.0
         *
         * @access public
         */
        public function admin_notice_missing_main_plugin() {

            if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

            $message = sprintf(
                esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'houzez-woo-addon' ),
                '<strong>' . esc_html__( 'Houzez Woo Addon', 'houzez-woo-addon' ) . '</strong>',
                '<strong>' . esc_html__( 'Houzez Theme - Functionality', 'houzez-woo-addon' ) . '</strong>'
            );

            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

        }

        /**
         * Admin notice
         *
         * Warning when the site doesn't have WooCommerce installed or activated.
         *
         * @since 1.0.0
         *
         * @access public
         */
        public function admin_notice_missing_woocommerce_plugin() {

            if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

            $message = sprintf(
                esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'houzez-woo-addon' ),
                '<strong>' . esc_html__( 'Houzez Woo Addon', 'houzez-woo-addon' ) . '</strong>',
                '<strong>' . esc_html__( 'WooCommerce', 'houzez-woo-addon' ) . '</strong>'
            );

            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

        }

        /**
         * Admin notice
         *
         * Warning when the site doesn't have a minimum required Houzez version.
         *
         * @since 1.0.0
         *
         * @access public
         */
        public function admin_notice_minimum_houzez_version() {

            if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

            $message = sprintf(
                /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
                esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'houzez-woo-addon' ),
                '<strong>' . esc_html__( 'Houzez Woo Addon', 'houzez-woo-addon' ) . '</strong>',
                '<strong>' . esc_html__( 'Houzez', 'houzez-woo-addon' ) . '</strong>',
                 self::MINIMUM_HOUZEZ_VERSION
            );

            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

        }
    
        

        public static function houzez_is_crm_page() {
            if ( is_page_template( array(
                'template/user_dashboard_crm.php'
            ) ) ) {
                return true;
            }
            return false;
        }

        /**
         * Functions
         */
        public function include_files() {

            include_once( HOUZEZ_WOO_DIR . 'includes/payment.php' ); 
        }


        /**
         * Load text domain for translation.
         */
        public function load_plugin_textdomain() {
            load_plugin_textdomain( 'houzez-woo-addon', false, dirname( HOUZEZ_WOO_BASENAME ) . '/languages' );
        }

        /**
         * plugin activation
         */
        public function plugin_activation() {
            
        }


        /**
         * plugin de-activation
         */
        public function plugin_deactivate() {

        }

        /**
         * Unserializing is forbidden.
         */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Not good; huh?', 'houzez-woo-addon' ), HOUZEZ_WOO_VERSION );
        }


        /**
         * Cloning is forbidden.
         */
        public function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Not good; huh?', 'houzez-woo-addon' ), HOUZEZ_WOO_VERSION );
        }

    }

endif; // End class_exists check.


/**
 * Instance of Houzez_WOO.
 * @return Houzez_WOO
 */
function Houzez_WooLoader() {
    return Houzez_WooCommerce::instance();
}
Houzez_WooLoader();