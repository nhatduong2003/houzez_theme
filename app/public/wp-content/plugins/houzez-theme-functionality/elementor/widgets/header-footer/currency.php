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
 * Site Currency Switcher Widget.
 * @since 1.0.0
 */
class Shopely_Currency_Switcher extends Widget_Base {

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
        return 'shopely_currency_switcher';
    }

    /**
     * Get widget title.
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Currency Switcher', 'shopely' );
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
        return [ 'Currency', 'Switcher' ];
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
			'section_content',
			[
				'label' => __( 'Content', 'shopely' ),
			]
		);

		/*$this->add_control(
			'important_note',
			[
				'type' => 'raw_html',
				'raw' => esc_html__('To customize the output of this shortcode, go to the WPML -> Languages page and use the settings in the Custom language switcher section.', 'shopely'),
				'content_classes' => 'elementor-control-field-description',
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
		?>
		<div class="dropdown">
            <a class="btn dropdown-toggle" href="#" role="button" id="currencyDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="shopely-icon shopely-icon-currency-dollar-circle"></i> USD
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="languageDropdown">
                <a class="dropdown-item" href="#">USD</a>
                <a class="dropdown-item" href="#">EUR</a>
                <a class="dropdown-item" href="#">BTC</a>
            </div>
        </div>
		<?php
		
    }

}
Plugin::instance()->widgets_manager->register( new Shopely_Currency_Switcher );