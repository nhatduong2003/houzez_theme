<?php
namespace Elementor;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Property_Section_FloorPlan_v2 extends Widget_Base {


	public function get_name() {
		return 'houzez-property-section-floorplan-v2';
	}

	public function get_title() {
		return __( 'Section Floor Plan v2', 'houzez-theme-functionality' );
	}

	public function get_icon() {
		return 'houzez-element-icon eicon-featured-image';
	}

	public function get_categories() {
		return [ 'houzez-single-property' ];
	}

	public function get_keywords() {
		return ['property', 'Floor Plan', 'houzez' ];
	}

	protected function register_controls() {
		parent::register_controls();


		$this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->add_control(
            'section_header',
            [
                'label' => esc_html__( 'Section Header', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => 'true',
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label' => esc_html__( 'Section Title', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => '',
                'condition' => [
                	'section_header' => 'true'
                ],
            ]
        );

        $this->add_control(
            'hide_size',
            [
                'label' => esc_html__( 'Hide Size', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fpl-size' => 'display: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'hide_beds',
            [
                'label' => esc_html__( 'Hide Beds', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fpl-rooms' => 'display: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'hide_bath',
            [
                'label' => esc_html__( 'Hide Bath', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fpl-baths' => 'display: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'hide_price',
            [
                'label' => esc_html__( 'Hide Price', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fpl-price' => 'display: {{VALUE}}!important;',
                ],
            ]
        );


        $this->end_controls_section();

	
		$this->start_controls_section(
            'box_style',
            [
                'label' => __( 'Section Style', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_background',
                'label' => __( 'Background', 'houzez-theme-functionality' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .block-wrap',
            ]
        );

        $this->add_responsive_control(
            'section_margin_top',
            [
                'label' => esc_html__( 'Margin Top', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .block-wrap' => 'margin-top: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding_bottom',
            [
                'label' => esc_html__( 'Title Padding Bottom', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .block-title-wrap' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin_bottom',
            [
                'label' => esc_html__( 'Title Margin Bottom', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .block-title-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


		$this->end_controls_section();

        // Section title
		$this->start_controls_section(
            'content_style',
            [
                'label' => __( 'Section Title', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
            'sec_title_color',
            [
                'label'     => esc_html__( 'Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .block-title-wrap h2' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'label'    => esc_html__( 'Typography', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .block-title-wrap h2',
            ]
        );
      

		$this->end_controls_section();

        // Floor Plan title
        $this->start_controls_section(
            'floorplan_style',
            [
                'label' => __( 'Floor Plan Title', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'fp_title_color',
            [
                'label'     => esc_html__( 'Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .floor-plan-right-wrap h3' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'fp_title_typo',
                'label'    => esc_html__( 'Typography', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .floor-plan-right-wrap h3',
            ]
        );
      

        $this->end_controls_section();

        // Meta
        $this->start_controls_section(
            'floorplanmeta_style',
            [
                'label' => __( 'Content', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'fp_meta_color',
            [
                'label'     => esc_html__( 'Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .floor-plan-right-wrap' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'fp_meta_typo',
                'label'    => esc_html__( 'Typography', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .floor-plan-right-wrap',
            ]
        );
      

        $this->end_controls_section();

	}

	protected function render() {
		
		global $ele_settings, $post;

		$settings = $this->get_settings_for_display();

		$ele_settings = $settings;

		
        get_template_part('property-details/luxury-homes/floor-plans');
         

	}

}
Plugin::instance()->widgets_manager->register( new Property_Section_FloorPlan_v2 );