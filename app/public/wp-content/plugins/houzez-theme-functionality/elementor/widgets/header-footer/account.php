<?php
namespace Shopely\Elementor\Widgets\HeaderFooter;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Site Account Widget.
 * @since 1.0.0
 */
class Shopely_Menu_Account extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'shopely_menu_account';
    }

    /**
     * Get widget title.
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Login & Register', 'shopely' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'shopely-element-icon eicon-site-search';
    }

    public function get_keywords() {
        return [ 'Login', 'Register', 'shopely' ];
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'shopely-elements', 'favethemes_studio_header', 'favethemes_studio_footer' ];
    }

    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
	protected function register_controls() {
		$this->register_general_controls();
	}

	

	/**
	 * Register Site Logo Controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_general_controls() {
		
		$this->start_controls_section(
			'section_menu_icon_content',
			[
				'label' => __( 'Content', 'shopely' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label' => __( 'Type', 'shopely' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'icon' => esc_html__('Icon with tooltip', 'shopely'),
					'with_text' => esc_html__('Icon with text', 'shopely'),
				),
				'default' => 'icon',
			]
		);

		 $this->add_control(
            'show_dropdown',
            [
                'label'     => esc_html__( 'Show Drop Down', 'shopely' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                	'no' => esc_html__('No', 'shopely'),
                    'shopely-nav-show-edit' => esc_html__('Yes', 'shopely'),
                ),
                'description' => esc_html__('Only for design purpose', 'shopely'),
                'default' => 'no',
            ]
        );

		/*$this->add_control(
			'title_text',
			[
				'label' => __( 'Title', 'shopely' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Wishlist',
			]
		);*/

	
        
		$this->end_controls_section();
	}

	

	/**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
    	$settings = $this->get_settings_for_display();

  
		$show_dropdown = '';
		if( Plugin::$instance->editor->is_edit_mode() ) {
			$show_dropdown = $settings['show_dropdown'];
		}

		$is_text = false;
		$main_class = 'account-button-wrap-v1';
		$nav_classes = 'shopely-nav';
		if( $settings['type'] == 'with_text' ) {
			$main_class = 'account-button-wrap-v2';
			$nav_classes = 'sign-in-register-v2-account-wrap shopely-nav';
			$is_text = true;
		}
		?>
		<div class="shopely-elementor account-button-wrap <?php echo esc_attr($main_class); ?>">
			<div class="<?php echo esc_attr($nav_classes); ?> <?php echo esc_attr($show_dropdown); ?>">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php if( $is_text ) { ?>
							<i class="shopely-icon shopely-icon-single-neutral-actions"></i> <span><?php esc_html_e('Account', 'shopely'); ?></span>
						<?php } else { ?>
							<span data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Account', 'shopely'); ?>">
								<i class="shopely-icon shopely-icon-single-neutral-actions"></i>
							</span>
						<?php } ?>
						
					</a>
					<ul class="woocommerce-MyAccount-navigation dropdown-menu dropdown-menu-end account-nav-dropdown">
						<li class="account-nav-buttons-nav">
							<?php if ( Plugin::$instance->editor->is_edit_mode() ) { ?>
							
								<div class="account-nav-buttons-wrap">
									<a class="btn btn-primary btn-sign-in btn-block" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php esc_html_e('Sign In', 'shopely'); ?></a>
									<a class="btn btn-primary-outlined btn-register btn-block" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php esc_html_e('Register', 'shopely'); ?></a>
								</div>

							<?php } else { ?>
								<?php  if( ! is_user_logged_in() ) { ?>
								<div class="account-nav-buttons-wrap">
									<a class="btn btn-primary btn-sign-in btn-block" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php esc_html_e('Sign In', 'shopely'); ?></a>
									<a class="btn btn-primary-outlined btn-register btn-block" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php esc_html_e('Register', 'shopely'); ?></a>
								</div>
								<?php } 
							} ?>
						</li>

						<?php wc_get_template('myaccount/links.php'); ?>
					</ul>
				</div>
			</div>
		</div>
		<?php
		
    }

}
Plugin::instance()->widgets_manager->register( new Shopely_Menu_Account );