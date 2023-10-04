<?php
/*
Plugin Name: FaveThemes White Label
Plugin URI:  http://themeforest.net/user/favethemes
Description: Adds functionality to Favethemes Themes
Version:     1.0.0
Author:      Favethemes
Author URI:  http://themeforest.net/user/favethemes
License:     GPL2
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FAVE_WHITE_LABEL_PLUGIN_URL',         plugin_dir_url( __FILE__ )); // Plugin directory URL
define( 'FAVE_WHITE_LABEL_DIR', 			   plugin_dir_path( __FILE__ ) ); // Plugin directory path
define( 'FAVE_WHITE_LABEL_PATH',              dirname( __FILE__ ));

if( ! class_exists('Favethemes_White_Label') ) {

	final class Favethemes_White_Label {
		
		private static $_instance = null;

		public function __construct( $widget_areas = array() ) {
	
			add_action( 'init', array( $this, 'setup' ) );
			add_filter('houzez_admin_sub_menus', array($this, 'admin_menu'), 11, 3);

			if ( true == get_option( 'fave_hide_themes_customizer', false ) ) {
				add_action( 'customize_register', array( $this, 'remove_themes_section' ), 30 );
			}
		}


		public static function instance() {
			if ( is_null( self::$_instance ) )
				self::$_instance = new self();
			return self::$_instance;
		}
		
		public function admin_menu( $sub_menus ) {
			if ( ! self::have_white_label_page() ) {
				return $sub_menus;
			}
			$sub_menus['favethemes_white_label'] = array(
	            'houzez_dashboard',
	            esc_html__( 'White Label', 'houzez-theme-functionality' ),
	            esc_html__( 'White Label', 'houzez-theme-functionality' ),
	            'manage_options',
	            'fave_white_label',
	            array( $this, 'white_label_box' ),
	        );
			return $sub_menus;
		}


		public function setup() {

			add_action( 'admin_init', array( $this, 'register_setting' ) );
			add_filter( 'wp_prepare_themes_for_js', array( $this, 'set_theme_branding' ) );
			add_filter( 'update_right_now_text', array( $this, 'dashboard_right_now' ) );
			add_filter( 'favethemes_theme_branding', array( $this, 'get_theme_branding_settings' ) );
			add_filter( 'houzez_theme_branding_logo', array( $this, 'get_theme_branding_logo' ) );

			add_action( 'admin_enqueue_scripts', array( __CLASS__ , 'enqueue_scripts' ) );
		}

		public static function enqueue_scripts( $hook ) {
	        $js_path = 'js/';
	        $css_path = 'css/';

	        if( isset( $_GET['page'] ) && $_GET['page'] == 'fave_white_label' ) {
	        	wp_enqueue_media();

	        	wp_enqueue_script('favethemes-branding-uploader', FAVE_WHITE_LABEL_PLUGIN_URL . $js_path . 'uploader.js', array('media-upload'), false, true);

	        	wp_enqueue_style('favethemes-branding-style', FAVE_WHITE_LABEL_PLUGIN_URL . $css_path . 'style.css', array(), '1.0.0', 'all');
	        }
	    }

		public static function get_settings() {

			$branding = array(
				'branding'        			=> get_option( 'fave_theme_branding' ),
				'name'        				=> get_option( 'fave_theme_name' ),
				'author'      				=> get_option( 'fave_theme_author' ),
				'author_url'  				=> get_option( 'fave_theme_author_url' ),
				'description' 				=> get_option( 'fave_theme_description' ),
				'screenshot'  				=> get_option( 'fave_theme_screenshot' ),
				'branding-logo'  			=> get_option( 'fave_theme_branding-logo' ),
				'hide_themes_customizer'  	=> get_option( 'fave_hide_themes_customizer', false ),
				'hide_page'  				=> get_option( 'favethemes_hide_white_label_page', false ),
			);

			return apply_filters( 'fave_white_label_settings', $branding );
		}


	    public static function set_theme_branding( $themes ) {

			$key = 'houzez';

			if ( isset( $themes[ $key ] ) ) {

				// Get settings
				$theme_data = self::get_settings();

				// Theme naem
				if ( ! empty( $theme_data['name'] ) ) {

					$themes[ $key ]['name'] = $theme_data['name'];

					foreach ( $themes as $parent_key => $theme ) { 
						
						if ( isset( $theme['parent'] ) && 'Houzez' == $theme['parent'] ) {
							$themes[ $parent_key ]['parent'] = $theme_data['name'];
							
							if( $parent_key == 'houzez-child' ) {
	 							$themes[ $parent_key ]['name'] = $theme_data['name'].' Child';

								if ( ! empty( $theme_data['screenshot'] ) ) {
									$themes[ $parent_key ]['screenshot'] = array( $theme_data['screenshot'] );
								}

								if ( ! empty( $theme_data['description'] ) ) {
									$themes[ $parent_key ]['description'] = $theme_data['description'];
								}

								if ( ! empty( $theme_data['author'] ) ) {
									$author_url = empty( $theme_data['author_url'] ) ? '#' : $theme_data['author_url'];
									$themes[ $parent_key ]['author'] = $theme_data['author'];
									$themes[ $parent_key ]['authorAndUri'] = '<a href="' . esc_url( $author_url ) . '">' . $theme_data['author'] . '</a>';
								}
							}
						}
					}
				}

				// Theme description
				if ( ! empty( $theme_data['description'] ) ) {
					$themes[ $key ]['description'] = $theme_data['description'];
				}

				// Theme author and author url
				if ( ! empty( $theme_data['author'] ) ) {
					$author_url = empty( $theme_data['author_url'] ) ? '#' : $theme_data['author_url'];
					$themes[ $key ]['author'] = $theme_data['author'];
					$themes[ $key ]['authorAndUri'] = '<a href="' . esc_url( $author_url ) . '">' . $theme_data['author'] . '</a>';
				}

				// Theme screenshot
				if ( ! empty( $theme_data['screenshot'] ) ) {
					$themes[ $key ]['screenshot'] = array( $theme_data['screenshot'] );
				}
			}
			return $themes;
	    }

	    public static function dashboard_right_now( $return ) {

			$theme_data = self::get_settings();

			if ( is_admin() && 'Houzez' == wp_get_theme() && ! empty( $theme_data['name'] ) ) {
				return sprintf( $return, get_bloginfo( 'version', 'display' ), '<a href="themes.php">' . $theme_data['name'] . '</a>' );
			}

			// Return
			return $return;

		}

		public static function remove_themes_section( $wp_customize ) {
			$wp_customize->remove_panel( 'themes' );
		}

		public static function have_white_label_page() {

			$return = true;
			if ( true == get_option( 'favethemes_hide_white_label_page', false ) ) {
				$return = false;
			}

			return $return;
		}

		public function register_setting() {
			register_setting( 'favethemes_branding', 'favethemes_branding', array( $this, 'sanitize_settings' ) ); 
		}

		public static function get_theme_branding_settings( $return ) {

			$theme_branding = get_option( 'fave_theme_branding' );

			if ( $theme_branding ) {
				$return = $theme_branding;
			}

			return $return;
	 
	    }

	    public static function get_theme_branding_logo( $return ) {

			$theme_branding = get_option( 'fave_theme_branding-logo' );

			if ( $theme_branding ) {
				$return = '<img src="'.$theme_branding.'" width="127" height="24">';
			}

			return $return;
	 
	    }

		public static function fave_sanitize_checkbox( $input ) {
			return isset( $input ) ? $input : null;
		}
		
	    public function sanitize_settings() {

	    	if ( ! isset( $_POST['favethemes-white-label-nonce'] )
	    		&& ! wp_verify_nonce( $_POST['favethemes-white-label-nonce'], 'favethemes-white-label' ) ) {
	    		return;
	    	}

	        if ( ! isset( $_POST['favethemes_branding'] ) ) {
				return;
			}

			// Get settings
			$settings = self::get_settings();

			// Loop
			foreach( $settings as $key => $setting ) {

				if ( in_array( $key, array( 'description' ) ) ) {
					if ( isset( $_POST['favethemes_branding']['description'] ) ) {
						update_option( 'fave_theme_description', wp_filter_nohtml_kses( wp_unslash( $_POST['favethemes_branding']['description'] ) ) );
					}
				} else if ( in_array( $key, array( 'hide_themes_customizer' ) ) ) {
					if ( isset( $_POST['favethemes_branding']['hide_themes_customizer'] ) ) {
						update_option( 'fave_hide_themes_customizer', true );
					} else {
						update_option( 'fave_hide_themes_customizer', false );
					}
				} else if ( in_array( $key, array( 'hide_page' ) ) ) {
					if ( isset( $_POST['favethemes_branding']['hide_page'] ) ) {
						update_option( 'favethemes_hide_white_label_page', self::fave_sanitize_checkbox( $_POST['favethemes_branding']['hide_page'] ) );
					}
				} else {
					if ( isset( $_POST['favethemes_branding'][$key] ) ) {
						update_option( 'fave_theme_'. $key, sanitize_text_field( wp_unslash( $_POST['favethemes_branding'][$key] ) ) );
					}
				}
			}
	 
	    }

		public static function white_label_box() {

			// Only if manage_options attr
			if ( ! current_user_can( 'manage_options' ) ) {
		        return;
		    }

			load_template( FAVE_WHITE_LABEL_DIR . 'template/form.php' );
			?>

			

		<?php
		}


	} // End Class

	function Favethemes_White_Label() {
		return Favethemes_White_Label::instance();
	} 

	Favethemes_White_Label();

}