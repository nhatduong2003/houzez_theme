<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Elementor Agents Widget.
 * @since 2.7.4
 */
class Houzez_Elementor_Agents_Grid extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @since 2.7.4
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'houzez_elementor_agents_grid';
    }

    /**
     * Get widget title.
     * @since 2.7.4
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Agents Grid', 'houzez-theme-functionality' );
    }

    /**
     * Get widget icon.
     *
     * @since 2.7.4
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'houzez-element-icon eicon-posts-grid';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the widget belongs to.
     *
     * @since 2.7.4
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'houzez-elements' ];
    }

    /**
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 2.7.4
     * @access protected
     */
    protected function register_controls() {

        $agent_category = array();
        $agent_city = array();
        
        houzez_get_terms_array( 'agent_category', $agent_category );
        houzez_get_terms_array( 'agent_city', $agent_city );

        $this->start_controls_section(
            'content_section',
            [
                'label'     => esc_html__( 'Content', 'houzez-theme-functionality' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'agents_layout',
            [
                'label'     => esc_html__( 'Layout', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'agent-grid'  => esc_html__('Version 1', 'houzez-theme-functionality'),
                    'agent-grid-v2'    => esc_html__('Version 2', 'houzez-theme-functionality')
                ],
                "description" => '',
                'default' => 'agent-grid',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'     => esc_html__( 'Columns', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    '4'  => esc_html__('4 Columns', 'houzez-theme-functionality'),
                    '3'  => esc_html__('3 Columns', 'houzez-theme-functionality')
                ],
                "description" => '',
                'default' => '3',
            ]
        );

        $this->add_control(
            'agent_category',
            [
                'label'     => esc_html__( 'Category', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT2,
                'options'   => $agent_category,
                'description' => '',
                'multiple' => true,
                'default' => '',
            ]
        );

        $this->add_control(
            'agent_city',
            [
                'label'     => esc_html__('City', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT2,
                'options'   => $agent_city,
                'multiple' => true,
                'description' => '',
                'default' => '',
            ]
        );

        $this->add_control(
            'posts_limit',
            [
                'label'     => esc_html__('Number of Agents', 'houzez-theme-functionality'),
                'type'      => Controls_Manager::TEXT,
                'description' => '',
                'default' => '9',
            ]
        );

        $this->add_control(
            'offset',
            [
                'label'     => 'Offset',
                'type'      => Controls_Manager::TEXT,
                'description' => '',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'     => esc_html__( 'Order By', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'  => esc_html__( 'None', 'houzez-theme-functionality'),
                    'ID'  => esc_html__( 'ID', 'houzez-theme-functionality'),
                    'title'   => esc_html__( 'Title', 'houzez-theme-functionality'),
                    'date'   => esc_html__( 'Date', 'houzez-theme-functionality'),
                    'rand'   => esc_html__( 'Random', 'houzez-theme-functionality'),
                    'menu_order'   => esc_html__( 'Menu Order', 'houzez-theme-functionality'),
                ],
                'default' => 'none',
            ]
        );
        $this->add_control(
            'order',
            [
                'label'     => esc_html__( 'Order', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'ASC'  => esc_html__( 'ASC', 'houzez-theme-functionality'),
                    'DESC'  => esc_html__( 'DESC', 'houzez-theme-functionality')
                ],
                'default' => 'ASC',
            ]
        );

        $this->end_controls_section();

        /*----------------------------------------------------------
        * Show Hide Date
        **---------------------------------------------------------*/
        $this->start_controls_section(
            'show_hide_section',
            [
                'label'     => esc_html__( 'Show/Hide', 'houzez-theme-functionality' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'hide_position',
            [
                'label' => esc_html__( 'Hide Agent Position', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .agents-grid-view .agent-list-position' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_properties_count',
            [
                'label' => esc_html__( 'Hide Listings Count', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .agent-list-contact .agent-listings-count' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_languages',
            [
                'label' => esc_html__( 'Hide Languages', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .agent-list-contact .agent-languages-list' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_button',
            [
                'label' => esc_html__( 'Hide Button', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .agent-grid-content-wrap .btn' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_footer',
            [
                'label' => esc_html__( 'Hide Empty Bottom', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .agent-grid-content-wrap' => 'display: {{VALUE}};',
                ],
                'conditions' => array(
                  'relation' => 'and',
                  'terms'    => array(
                    
                    array(
                      'name'     => 'hide_properties_count',
                      'operator' => '==',
                      'value'    => 'none',
                    ),
                    array(
                      'name'     => 'hide_languages',
                      'operator' => '==',
                      'value'    => 'none',
                    ),
                    array(
                      'name'     => 'hide_button',
                      'operator' => '==',
                      'value'    => 'none',
                    ),
                  )
                ),
            ]
        );


        $this->end_controls_section();

        /*----------------------------------------------------------
        * Styling
        **---------------------------------------------------------*/
        $this->start_controls_section(
            'styling_section',
            [
                'label'     => esc_html__( 'Box', 'houzez-theme-functionality' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'agent_box_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .agents-grid-view .agent-grid-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'agent_box_border',
                'selector' => '{{WRAPPER}} .agents-grid-view .agent-grid-wrap',
            ]
        );

        $this->add_control(
            'agent_box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'houzez-theme-functionality' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .agents-grid-view .agent-grid-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'agent_box_shadow',
                'selector' => '{{WRAPPER}} .agents-grid-view .agent-grid-wrap',
            ]
        );

        $this->add_control(
            'hide_separator',
            [
                'label' => esc_html__( 'Hide Separator', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .agents-grid-view .agent-grid-content-wrap' => 'border-top: {{VALUE}};',
                    '{{WRAPPER}} .agent-grid-content-wrap' => 'padding-top: 0;',
                ],
            ]
        );

        $this->add_control(
            'agent_box_separator_color',
            [
                'label'     => esc_html__( 'Separator Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#dce0e0',
                'selectors' => [
                    '{{WRAPPER}} .agents-grid-view .agent-grid-content-wrap' => 'border-color: {{VALUE}}',
                ],
                'conditions' => array(
                  'terms'    => array(
                    
                    array(
                      'name'     => 'hide_separator',
                      'operator' => '!=',
                      'value'    => 'none',
                    ),
                  )
                ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'typo_section',
            [
                'label'     => esc_html__( 'Typography', 'houzez-theme-functionality' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'agent_title',
                'label'    => esc_html__( 'Agent Name', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .agent-grid-wrap h2',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'agent_position',
                'label'    => esc_html__( 'Position', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .agent-list-position',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'agent_listing_count',
                'label'    => esc_html__( 'Listings Count', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .agent-listings-count',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'agent_languages',
                'label'    => esc_html__( 'Languages', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .agent-languages-list',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'agent_button_typo',
                'label'    => esc_html__( 'Button', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .agent-grid-wrap .btn',
            ]
        );

        $this->end_controls_section(); 


        $this->start_controls_section(
            'button_section',
            [
                'label'     => esc_html__( 'Button', 'houzez-theme-functionality' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'conditions' => array(
                  'terms'    => array(
                    
                    array(
                      'name'     => 'hide_button',
                      'operator' => '!=',
                      'value'    => 'none',
                    ),
                  )
                ),
            ]
        );

        $this->start_controls_tabs(
            'button_style_tabs'
        );

        $this->start_controls_tab(
            'style_normal_botton',
            [
                'label' => esc_html__( 'Normal', 'textdomain' ),
            ]
        );

        $this->add_control(
            'view_button_bg',
            [
                'label'     => esc_html__( 'Background Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .agent-grid-wrap .btn' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'view_button_color',
            [
                'label'     => esc_html__( 'Text Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#00aeff',
                'selectors' => [
                    '{{WRAPPER}} .agent-grid-wrap .btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'agent_button_border',
                'selector' => '{{WRAPPER}} .agent-grid-wrap .btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_hover_botton',
            [
                'label' => esc_html__( 'Hover', 'textdomain' ),
            ]
        );

        $this->add_control(
            'view_button_hover_bg',
            [
                'label'     => esc_html__( 'Background Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#33beff',
                'selectors' => [
                    '{{WRAPPER}} .agent-grid-wrap .btn:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'view_button_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .agent-grid-wrap .btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'agent_button_hover_border',
                'selector' => '{{WRAPPER}} .agent-grid-wrap .btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 2.7.4
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $agent_category = $agent_city = array();

        if(!empty($settings['agent_category']) && is_array($settings['agent_category']) ) {
           $agent_category = implode (",", $settings['agent_category']);
        }

        if(!empty($settings['agent_city']) && is_array($settings['agent_city']) ) {
            $agent_city = implode (",", $settings['agent_city']);
        }

        $args['agent_category']   =  $agent_category;
        $args['agent_city']   =  $agent_city;

        $args['agents_layout'] =  $settings['agents_layout'];
        $args['orderby'] =  $settings['orderby'];
        $args['posts_limit'] =  $settings['posts_limit'];
        $args['columns'] =  $settings['columns'];
        $args['order'] =  $settings['order'];
        $args['offset'] =  $settings['offset'];
        
        if( function_exists( 'houzez_agents_grid' ) ) {
            echo houzez_agents_grid( $args );
        }

    }

}

Plugin::instance()->widgets_manager->register( new Houzez_Elementor_Agents_Grid );