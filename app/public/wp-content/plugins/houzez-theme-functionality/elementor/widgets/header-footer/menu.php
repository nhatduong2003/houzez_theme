<?php
namespace Houzez\Elementor\Widgets\HeaderFooter;
use Houzez\Classes;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Site Menu Widget.
 * @since 1.0.0
 */
class Houzez_Menu extends Widget_Base {

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
        return 'houzez_site_menu';
    }

    /**
     * Get widget title.
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Nav Menu Free', 'houzez' );
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
        return 'houzez-element-icon eicon-nav-menu';
    }

    public function get_keywords() {
        return [ 'Menu', 'Nav', 'navigation', 'houzez' ];
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
        return [ 'houzez-elements', 'favethemes_studio_header', 'favethemes_studio_footer' ];
    }

    /**
     * Get the all available menus
     *
     * Retrieve the list of all menus.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array menu slug.
     */
	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = array();

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
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
				'label' => __( 'Content', 'houzez' ),
			]
		);

		$menu_options = $this->get_available_menus();

		if( $menu_options  ) {
			$this->add_control(
				'menu_slug',
				[
					'label' => __( 'Menu', 'houzez' ),
					'type' => 'select',
					'default' => isset( array_keys( $menu_options )[0] ) ? array_keys( $menu_options )[0] : '',
					'options' => $menu_options,
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'houzez' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu_slug',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'houzez' ), self_admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                )
			);
		}

		$this->add_control(
			'align_items',
			[
				'label' => esc_html__( 'Align', 'elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-pro' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-pro' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-pro' ),
						'icon' => 'eicon-h-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Stretch', 'elementor-pro' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'prefix_class' => 'houzez-nav-menu__align-',
				'condition' => [
					//'layout!' => 'dropdown',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label' => esc_html__( 'Main Menu', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					//'layout!' => 'dropdown',
				],

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .houzez-elementor-menu .nav-link',
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'color_menu_item',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .houzez-elementor-menu--main .nav-link' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'color_menu_item_hover',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .houzez-elementor-menu--main .nav-link:hover,
					{{WRAPPER}} .houzez-elementor-menu--main .nav-link.nav-link-active,
					{{WRAPPER}} .houzez-elementor-menu--main .nav-link.highlighted,
					{{WRAPPER}} .houzez-elementor-menu--main .nav-link:focus' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
				'condition' => [
					//'pointer!' => 'background',
				],
			]
		);

		/*$this->add_control(
			'color_menu_item_hover_pointer_bg',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .houzez-elementor-menu--main .nav-link:hover,
					{{WRAPPER}} .houzez-elementor-menu--main .nav-link.nav-link-active,
					{{WRAPPER}} .houzez-elementor-menu--main .nav-link.highlighted,
					{{WRAPPER}} .houzez-elementor-menu--main .nav-link:focus' => 'color: {{VALUE}}',
				],
				'condition' => [
					'pointer' => 'background',
				],
			]
		);*/

		$this->add_control(
			'pointer_color_menu_item_hover',
			[
				'label' => esc_html__( 'Pointer Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .houzez-elementor-menu--main:not(.e--pointer-framed) .nav-link:before,
					{{WRAPPER}} .houzez-elementor-menu--main:not(.e--pointer-framed) .nav-link:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .e--pointer-framed .nav-link:before,
					{{WRAPPER}} .e--pointer-framed .nav-link:after' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					//'pointer!' => [ 'none', 'text' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active',
			[
				'label' => esc_html__( 'Active', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'color_menu_item_active',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .houzez-elementor-menu--main .nav-link.nav-link-active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pointer_color_menu_item_active',
			[
				'label' => esc_html__( 'Pointer Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .houzez-elementor-menu--main:not(.e--pointer-framed) .nav-link.nav-link-active:before,
					{{WRAPPER}} .houzez-elementor-menu--main:not(.e--pointer-framed) .nav-link.nav-link-active:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .e--pointer-framed .nav-link.nav-link-active:before,
					{{WRAPPER}} .e--pointer-framed .nav-link.nav-link-active:after' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					//'pointer!' => [ 'none', 'text' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$divider_condition = [
			'nav_menu_divider' => 'yes',
			//'layout' => 'horizontal',
		];

		$this->add_control(
			'nav_menu_divider',
			[
				'label' => esc_html__( 'Divider', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'elementor-pro' ),
				'label_on' => esc_html__( 'On', 'elementor-pro' ),
				'condition' => [
					//'layout' => 'horizontal',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-nav-menu-divider-content: "";',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'nav_menu_divider_style',
			[
				'label' => esc_html__( 'Style', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'elementor-pro' ),
					'double' => esc_html__( 'Double', 'elementor-pro' ),
					'dotted' => esc_html__( 'Dotted', 'elementor-pro' ),
					'dashed' => esc_html__( 'Dashed', 'elementor-pro' ),
				],
				'default' => 'solid',
				'condition' => $divider_condition,
				'selectors' => [
					'{{WRAPPER}}' => '--e-nav-menu-divider-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'nav_menu_divider_weight',
			[
				'label' => esc_html__( 'Width', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => $divider_condition,
				'selectors' => [
					'{{WRAPPER}}' => '--e-nav-menu-divider-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'nav_menu_divider_height',
			[
				'label' => esc_html__( 'Height', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => $divider_condition,
				'selectors' => [
					'{{WRAPPER}}' => '--e-nav-menu-divider-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'nav_menu_divider_color',
			[
				'label' => esc_html__( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => $divider_condition,
				'selectors' => [
					'{{WRAPPER}}' => '--e-nav-menu-divider-color: {{VALUE}}',
				],
			]
		);

		/* This control is required to handle with complicated conditions */
		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'pointer_width',
			[
				'label' => esc_html__( 'Pointer Width', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .e--pointer-framed .nav-link:before' => 'border-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .e--pointer-framed.e--animation-draw .nav-link:before' => 'border-width: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .e--pointer-framed.e--animation-draw .nav-link:after' => 'border-width: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
					'{{WRAPPER}} .e--pointer-framed.e--animation-corners .nav-link:before' => 'border-width: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .e--pointer-framed.e--animation-corners .nav-link:after' => 'border-width: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
					'{{WRAPPER}} .e--pointer-underline .nav-link:after,
					 {{WRAPPER}} .e--pointer-overline .nav-link:before,
					 {{WRAPPER}} .e--pointer-double-line .nav-link:before,
					 {{WRAPPER}} .e--pointer-double-line .nav-link:after' => 'height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'pointer' => [ 'underline', 'overline', 'double-line', 'framed' ],
				],
			]
		);

		$this->add_responsive_control(
			'padding_horizontal_menu_item',
			[
				'label' => esc_html__( 'Horizontal Padding', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .houzez-elementor-menu--main .nav-link' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'padding_vertical_menu_item',
			[
				'label' => esc_html__( 'Vertical Padding', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .houzez-elementor-menu--main .nav-link' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'menu_space_between',
			[
				'label' => esc_html__( 'Space Between', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-nav-menu-horizontal-menu-item-margin: calc( {{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .houzez-elementor-menu--main:not(.houzez-elementor-menu--layout-horizontal) .houzez-elementor-menu > li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius_menu_item',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .nav-link:before' => 'border-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .e--animation-shutter-in-horizontal .nav-link:before' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
					'{{WRAPPER}} .e--animation-shutter-in-horizontal .nav-link:after' => 'border-radius: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .e--animation-shutter-in-vertical .nav-link:before' => 'border-radius: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
					'{{WRAPPER}} .e--animation-shutter-in-vertical .nav-link:after' => 'border-radius: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'pointer' => 'background',
				],
			]
		);

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
        $menus = $this->get_available_menus();

		if ( empty( $menus ) ) {
			return;
		}

		$settings = $this->get_active_settings();

		$args = [
			'menu' => $settings['menu_slug'],
			'menu_class' => 'navbar-nav houzez-elementor-menu',
			'menu_id' => 'main-nav main-nav-' . $this->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container' => '',
			'echo' => false,
			'depth' => 4,
			'walker' => new Classes\houzez_plugin_nav_walker()
		];

		$menu_html = wp_nav_menu( $args );

		if ( empty( $menu_html ) ) {
			return;
		}

		if ( 'dropdown' !== $settings['layout'] ) :
			$this->add_render_attribute( 'main-menu', 'class', [
				'main-nav', 
				'on-hover-menu',
				'navbar-expand-lg',
				'flex-grow-1',
				'houzez-elementor-menu--main',
				'houzez-elementor-menu__container',
				'houzez-elementor-menu--layout-' . $settings['layout'],
			] );

			/*if ( $settings['pointer'] ) :
				$this->add_render_attribute( 'main-menu', 'class', 'e--pointer-' . $settings['pointer'] );

				foreach ( $settings as $key => $value ) :
					if ( 0 === strpos( $key, 'animation' ) && $value ) :
						$this->add_render_attribute( 'main-menu', 'class', 'e--animation-' . $value );

						break;
					endif;
				endforeach;
			endif;*/ ?>

	        <div class="navbar-menu-wrap">
				<nav <?php $this->print_render_attribute_string( 'main-menu' ); ?>>
					<?php
						// PHPCS - escaped by WordPress with "wp_nav_menu"
						echo $menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</nav>
			</div><!-- navbar-menu-wrap -->
			<?php
		endif;
    }

}

Plugin::instance()->widgets_manager->register( new Houzez_Menu );
