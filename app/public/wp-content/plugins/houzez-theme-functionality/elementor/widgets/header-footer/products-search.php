<?php
namespace Shopely\Elementor\Widgets\HeaderFooter;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Site Products Search Widget.
 * @since 1.0.0
 */
class Shopely_Products_Search extends Widget_Base {

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
        return 'shopely_products_search';
    }

    /**
     * Get widget title.
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Products Search', 'shopely' );
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
        return [ 'Prodcuts Search', 'Search', 'shopely' ];
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
		$this->register_style_controls();
		$this->register_button_style_controls();
	}

	

	/**
	 * Register Search Controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_general_controls() {
		
		$this->start_controls_section(
			'section_menu_icon_content',
			[
				'label' => __( 'Search', 'shopely' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'shopely' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'input' => esc_html__('Input Box', 'shopely'),
					'icon' => esc_html__('Search Icon', 'shopely'),
				),
				'default' => 'input',
			]
		);

		$this->add_control(
			'categories',
			[
				'label' => __( 'Show Categories', 'shopely' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'shopely' ),
				'label_off' => __( 'Hide', 'shopely' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'button',
			[
				'label' => __( 'Button', 'shopely' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'shopely' ),
				'label_off' => __( 'Hide', 'shopely' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'shopely' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Search',
			]
		);

		/*$this->add_control(
			'toggle_align',
			[
				'label' => __( 'Toggle Align', 'elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-pro' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-pro' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-pro' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'left' => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right' => 'margin-left: auto',
				],
				'selectors' => [
					'{{WRAPPER}} .search-button-wrap' => '{{VALUE}}',
				],
				'condition' => [
					'layout' => 'icon',
				],
			]
		);*/

		$this->end_controls_section();
	}

	/**
	 * Register Search Controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'section_input_style',
			[
				'label' => __( 'Input', 'shopely' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} input[type="search"].form-control,{{WRAPPER}} .filter-option-inner-inner',
			]
		);

		$this->add_responsive_control(
			'input_icon_size',
			[
				'label'     => __( 'Width', 'shopely' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 460,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 1500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .search-button-widget-wrap .dropdown-menu-search' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'icon',
				],
			]
		);

		$this->add_control(
			'input_text_color',
			[
				'label'     => __( 'Text Color', 'shopely' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{WRAPPER}} input[type="search"].form-control,{{WRAPPER}} .filter-option-inner-inner' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label'     => __( 'Placeholder Color', 'shopely' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} input[type="search"].form-control::placeholder' => 'color: {{VALUE}}',
				],
				'default'   => '',
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label'     => __( 'Background Color', 'shopely' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .search-bar.form-control, {{WRAPPER}} button.dropdown-toggle' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_style',
			[
				'label'       => __( 'Border Style', 'shopely' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => [
					'none'   => __( 'None', 'shopely' ),
					'solid'  => __( 'Solid', 'shopely' ),
					'double' => __( 'Double', 'shopely' ),
					'dotted' => __( 'Dotted', 'shopely' ),
					'dashed' => __( 'Dashed', 'shopely' ),
				],
				'selectors'   => [
					'{{WRAPPER}} .search-wrap .search-departments .btn ,{{WRAPPER}} .search-wrap .search-bar' => 'border-style: {{VALUE}};',
				],
				'condition'   => [
					'layout!' => 'icon',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'     => __( 'Border Color', 'shopely' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_style!' => 'none',
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .search-wrap .search-departments .btn ,{{WRAPPER}} .search-wrap .search-bar' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label'      => __( 'Border Width', 'shopely' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'    => '1',
					'bottom' => '1',
					'left'   => '1',
					'right'  => '1',
					'unit'   => 'px',
				],
				'condition'  => [
					'border_style!' => 'none',
				],
				'selectors'  => [
					'{{WRAPPER}} .search-wrap .search-departments .btn ,{{WRAPPER}} .search-wrap .search-bar' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'layout!' => 'icon',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'     => __( 'Border Radius', 'shopely' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .search-wrap .search-departments .btn ,{{WRAPPER}} .search-wrap .search-bar' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function register_button_style_controls() {
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'shopely' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'shopely' ),
			]
		);

		$this->add_control(
			'button_icon_color',
			[
				'label'     => __( 'Icon Color', 'shopely' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} button.search-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'button_background',
				'label'          => __( 'Background', 'shopely' ),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} button.search-btn',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_border_style',
			[
				'label'       => __( 'Border Style', 'shopely' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => [
					'none'   => __( 'None', 'shopely' ),
					'solid'  => __( 'Solid', 'shopely' ),
					'double' => __( 'Double', 'shopely' ),
					'dotted' => __( 'Dotted', 'shopely' ),
					'dashed' => __( 'Dashed', 'shopely' ),
				],
				'selectors'   => [
					'{{WRAPPER}} button.search-btn' => 'border-style: {{VALUE}};',
				],
				'condition'   => [
					'layout!' => 'icon',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'     => __( 'Border Color', 'shopely' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_style!' => 'none',
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} button.search-btn' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_width',
			[
				'label'      => __( 'Border Width', 'shopely' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'    => '1',
					'bottom' => '1',
					'left'   => '1',
					'right'  => '1',
					'unit'   => 'px',
				],
				'condition'  => [
					'border_style!' => 'none',
				],
				'selectors'  => [
					'{{WRAPPER}} button.search-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'layout!' => 'icon',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'     => __( 'Border Radius', 'shopely' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} button.search-btn' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'shopely' ),
			]
		);

		$this->add_control(
			'button_hover_icon_color',
			[
				'label'     => __( 'Icon Color', 'shopely' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} button.search-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'button_hover_background',
				'label'          => __( 'Background', 'shopely' ),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} button.search-btn:hover',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_style',
			[
				'label'       => __( 'Border Style', 'shopely' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => [
					'none'   => __( 'None', 'shopely' ),
					'solid'  => __( 'Solid', 'shopely' ),
					'double' => __( 'Double', 'shopely' ),
					'dotted' => __( 'Dotted', 'shopely' ),
					'dashed' => __( 'Dashed', 'shopely' ),
				],
				'selectors'   => [
					'{{WRAPPER}} button.search-btn:hover' => 'border-style: {{VALUE}};',
				],
				'condition'   => [
					'layout!' => 'icon',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'shopely' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_style!' => 'none',
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} button.search-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_width',
			[
				'label'      => __( 'Border Width', 'shopely' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'    => '1',
					'bottom' => '1',
					'left'   => '1',
					'right'  => '1',
					'unit'   => 'px',
				],
				'condition'  => [
					'border_style!' => 'none',
				],
				'selectors'  => [
					'{{WRAPPER}} button.search-btn:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'layout!' => 'icon',
				],
			]
		);

		$this->add_control(
			'button_hover_border_radius',
			[
				'label'     => __( 'Border Radius', 'shopely' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} button.search-btn:hover' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	
	private function search_icon( $settings, $placeholder ) {
		?>
		<div class="search-button-wrap search-button-widget-wrap">
			<div class="dropdown">
				<a class="shopely-search-icon-js dropdown-toggle" role="button">
					<span data-toggle="tooltip" data-placement="top" title="<?php echo esc_attr($placeholder); ?>">
						<i class="shopely-icon shopely-icon-search"></i>
					</span>
				</a>
				<div class="shopely-show-search dropdown-menu dropdown-menu-right dropdown-menu-search">
					<?php $this->search_input_box( $settings, $placeholder ); ?>	
				</div>
			</div><!-- dropdown -->
		</div><!-- search-button-wrap -->
		<?php
	}

	private function search_input_box( $settings, $placeholder ) {
		?>
		<div class="search-wrap search-wrap-v1">
			<form method="get" class="form-inline" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
				<?php 
				if( $settings['categories'] ) {
					shopely_search_categories(); 
				}?>

				<div class="search-bar-wrap">
					<input name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" class="search-bar form-control" type="search" placeholder="<?php echo esc_attr($placeholder); ?>" aria-label="<?php echo esc_attr($placeholder); ?>">
				</div>
				<input type="hidden" name="post_type" value="product">
				<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ): ?>
					<input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>" />
				<?php endif ?>
				<?php if( $settings['button'] ) { ?>
				<button class="btn search-btn" type="submit"><i class="shopely-icon shopely-icon-search"></i></button>
				<?php } ?>
			</form>
		</div>
		<?php
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

    	$placeholder = __( 'Search', 'woocommerce' ) . '&hellip;';
		if ( $settings['placeholder'] ) {
			$placeholder = $settings['placeholder'];
		}

		if( $settings['layout'] == 'icon' ) {

			$this->search_icon($settings, $placeholder);

		} else {
			$this->search_input_box($settings, $placeholder);
		}

       
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) :      
        ?>
        <script>
            jQuery('.selectpicker').selectpicker('refresh');
        </script>
        <?php
    	endif;
    }

}

Plugin::instance()->widgets_manager->register( new Shopely_Products_Search );
