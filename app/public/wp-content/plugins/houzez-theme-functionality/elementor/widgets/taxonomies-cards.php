<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Elementor Grids Widget.
 * @since 3.0
 */
class Houzez_Taxonomies_Cards extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @since v3.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Houzez_Taxonomies_Cards';
    }

    /**
     * Get widget title.
     * @since v3.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Taxonomies Cards', 'houzez-theme-functionality' );
    }

    /**
     * Get widget icon.
     *
     * @since v3.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'houzez-element-icon eicon-gallery-grid';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the widget belongs to.
     *
     * @since v3.0
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
     * @since v3.0
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label'     => esc_html__( 'Content', 'houzez-theme-functionality' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        /*$this->add_control(
            'houzez_card_type',
            [
                'label'     => esc_html__( 'Choose Card', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'card-v1'  => 'Version v1',
                    'card-v2'    => 'Version v2',
                ],
                'description' => '',
                'default' => 'grid-v1',
            ]
        );*/

        $this->add_control(
            'houzez_cards_columns',
            [
                'label'     => esc_html__( 'Columns', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    '3cols'  => '3 Columns',
                    '4cols'  => '4 Columns',
                ],
                'description' => '',
                'default' => '4cols',
            ]
        );

        $this->add_control(
            'houzez_cards_from',
            [
                'label'     => esc_html__( 'Choose Taxonomy', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'property_type' => 'Types',
                    'property_status' => 'Status',
                    'property_label' => 'Label',
                    'property_country' => 'Country',
                    'property_state' => 'State',
                    'property_city' => 'City',
                    'property_area' => 'Area',
                ],
                'description' => '',
                'default' => 'property_type',
            ]
        );

        // Property taxonomies controls
        $prop_taxonomies = get_object_taxonomies( 'property', 'objects' );
        unset( $prop_taxonomies['property_feature'] );

        $page_filters = houzez_option('houzez_page_filters');

        if( isset($page_filters) && !empty($page_filters) ) {
            foreach ($page_filters as $filter) {
                unset( $prop_taxonomies[$filter] );
            }
        }

        if ( ! empty( $prop_taxonomies ) && ! is_wp_error( $prop_taxonomies ) ) {
            foreach ( $prop_taxonomies as $single_tax ) {

                $options_array = array();
                $terms   = get_terms( $single_tax->name );

                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $options_array[ $term->slug ] = $term->name;
                    }
                }

                $this->add_control(
                    $single_tax->name,
                    [
                        'label'    => $single_tax->label,
                        'type'     => Controls_Manager::SELECT2,
                        'multiple' => true,
                        'label_block' => true,
                        'options'  => $options_array,
                        'condition' => [
                            'houzez_cards_from' => $single_tax->name,
                        ],
                    ]
                );
            }
        }

        $this->add_control(
            'houzez_show_child',
            [
                'label'     => esc_html__( 'Show Child', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    '0'  => esc_html__( 'No', 'houzez-theme-functionality'),
                    '1'    => esc_html__( 'Yes', 'houzez-theme-functionality')
                ],
                'description' => '',
                'default' => '0',
            ]
        );

        $this->add_control(
            'houzez_hide_empty',
            [
                'label'     => esc_html__( 'Hide Empty', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    '0'  => esc_html__( 'No', 'houzez-theme-functionality'),
                    '1'    => esc_html__( 'Yes', 'houzez-theme-functionality')
                ],
                'description' => '',
                'default' => '1',
            ]
        );

        $this->add_control(
            'houzez_hide_count',
            [
                'label'     => esc_html__( 'Hide Count', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    '0'  => esc_html__( 'No', 'houzez-theme-functionality'),
                    '1'    => esc_html__( 'Yes', 'houzez-theme-functionality')
                ],
                'description' => '',
                'default' => '0',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'     => esc_html__( 'Order By', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'name'  => esc_html__( 'Name', 'houzez-theme-functionality'),
                    'count'    => esc_html__( 'Count', 'houzez-theme-functionality'),
                    'id'    => esc_html__( 'ID', 'houzez-theme-functionality')
                ],
                'description' => '',
                'default' => 'name',
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


        $this->add_control(
            'no_of_terms',
            [
                'label'     => esc_html__('Number of Items to Show', 'houzez-theme-functionality'),
                'type'      => Controls_Manager::TEXT,
                'description' => '',
                'default' => '',
            ]
        );
        
        $this->end_controls_section();

        /*--------------------------------------------------------------------------------
        * Styling
        * -------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'style_secingion',
            [
                'label'     => esc_html__( 'Style', 'houzez-theme-functionality' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tax_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .taxonomy-item-card .taxonomy-item-card-content-list dt a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tax_count_color',
            [
                'label'     => esc_html__( 'Count Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .taxonomy-item-card .taxonomy-item-card-content-list dd' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tax_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .taxonomy-item-card' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tax_border',
                'selector' => '{{WRAPPER}} .taxonomy-item-card',
            ]
        );

        $this->add_responsive_control(
            'tax_box_radius',
            [
                'label'      => esc_html__( 'Box Radius', 'houzez-theme-functionality' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .taxonomy-item-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tax_image_radius',
            [
                'label'      => esc_html__( 'Image Radius', 'houzez-theme-functionality' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .taxonomy-cards-module .taxonomy-item-card-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'tax_image_padding',
            [
                'label'      => esc_html__( 'Image Padding', 'houzez-theme-functionality' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .taxonomy-cards-module .taxonomy-item-card-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'label'    => esc_html__( 'Shadow', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .taxonomy-item-card',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since v3.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();
        $property_type = $property_status = $property_label = $property_country = $property_state = $property_city = $property_area = array();

        if(!empty($settings['property_type'])) {
            $property_type = implode (",", $settings['property_type']);
        }

        if(!empty($settings['property_status'])) {
            $property_status = implode (",", $settings['property_status']);
        }

        if(!empty($settings['property_label'])) {
            $property_label = implode (",", $settings['property_label']);
        }

        if(!empty($settings['property_state'])) {
            $property_state = implode (",", $settings['property_state']);
        }

        if(!empty($settings['property_country'])) {
            $property_country = implode (",", $settings['property_country']);
        }

        if(!empty($settings['property_city'])) {
            $property_city = implode (",", $settings['property_city']);
        }

        if(!empty($settings['property_area'])) {
            $property_area = implode (",", $settings['property_area']);
        }

        $args['houzez_card_type'] =  '';//$settings['houzez_card_type'];
        $args['houzez_cards_from'] =  $settings['houzez_cards_from'];
        $args['houzez_cards_columns'] =  $settings['houzez_cards_columns'];
        $args['houzez_show_child'] =  $settings['houzez_show_child'];
        $args['houzez_hide_count'] =  $settings['houzez_hide_count'];
        $args['orderby'] =  $settings['orderby'];
        $args['order'] =  $settings['order'];
        $args['houzez_hide_empty'] =  $settings['houzez_hide_empty'];
        $args['no_of_terms'] =  $settings['no_of_terms'];

        $args['property_type']   =  $property_type;
        $args['property_status']   =  $property_status;
        $args['property_label']   =  $property_label;
        $args['property_country']   =  $property_country;
        $args['property_state']   =  $property_state;
        $args['property_city']   =  $property_city;
        $args['property_area']   =  $property_area;
       
        if( function_exists( 'houzez_taxonomies_cards' ) ) {
            echo houzez_taxonomies_cards( $args );
        }

    }

}

Plugin::instance()->widgets_manager->register( new Houzez_Taxonomies_Cards );