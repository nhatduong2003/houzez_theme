<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Elementor Products Tabs vs Widget.
 * @since 1.0.0
 */
class Houzez_Properties_Tabs extends Widget_Base {

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
        return 'houzez_properties_tabs';
    }

    /**
     * Get widget title.
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Properties Ajax Tabs', 'houzez-theme-functionality' );
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
        return 'houzez-element-icon eicon-tabs';
    }

    public function get_keywords() {
        return [ 'Products', 'Tabs', 'houzez-theme-functionality' ];
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
        return [ 'houzez-elements' ];
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
        $this->start_controls_section(
            'section_general',
            [
                'label' => __( 'General', 'houzez-theme-functionality' ),
            ]
        );

        $this->add_control(
            'houzez_tabs_style',
            [
                'label' => esc_html__( 'Tabs Style', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    'property-nav-tabs-v1' => esc_html__( 'Tabs v1', 'houzez-theme-functionality' ),
                    'property-nav-tabs-v2' => esc_html__( 'Tabs v2', 'houzez-theme-functionality' ),
                    'property-nav-tabs-v3' => esc_html__( 'Tabs v3', 'houzez-theme-functionality' ),
                    'property-nav-tabs-v4' => esc_html__( 'Tabs v4', 'houzez-theme-functionality' ),
                    'property-nav-tabs-v5' => esc_html__( 'Tabs v5', 'houzez-theme-functionality' ),
                ],
                'default'   => 'property-nav-tabs-v1',
            ]
        );
        
        $this->add_control(
            'houzez_tabs_position',
            [
                'label'         =>  __( 'Position', 'houzez-theme-functionality' ),
                'type'          =>  Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'start'    => [
                        'title' => __( 'Left', 'houzez-theme-functionality' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'houzez-theme-functionality' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'end' => [
                        'title' => __( 'Right', 'houzez-theme-functionality' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default'       => 'center',
            ]
        );
        
        $this->add_responsive_control(
            'tabs_spacer',
            [
                'label' => esc_html__('Tab Spacer', 'houzez-theme-functionality'),
                'type' =>  Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => .5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.property-nav-tabs li:not(:last-child):not(.skip)' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bottom_spacer',
            [
                'label' => esc_html__('Bottom Spacer', 'houzez-theme-functionality'),
                'type' =>  Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => .5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.property-nav-tabs' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'show_tabs_icon',
            [
                'label' => esc_html__( 'Enable Icon', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );
        
        $this->add_control(
            'tabs_icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-icon',
                'label_block' => false,
                'options' => [
                    'top-icon'    => esc_html__('Above', 'houzez-theme-functionality'),
                    'inline-icon' => esc_html__('Before title', 'houzez-theme-functionality'),
                ],
                'condition' => ['tabs_icon' => 'yes'],
            ]
        );
        
        $this->add_control(
            'show_tabs_title',
            [
                'label' => esc_html__( 'Show title', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();

        /**
         * Tabs settings.
         */
        $this->start_controls_section(
            'tabs_content_section',
            [
                'label' => esc_html__( 'Tabs', 'houzez-theme-functionality' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'content_tabs' );

        
        // Tab
        $repeater->start_controls_tab(
            'tab_icon',
            [
                'label' => esc_html__( 'Tab', 'houzez-theme-functionality' ),
            ]
        );

        $repeater->add_control(
            'tab_title', [
                'label' => esc_html__('Tab Title', 'houzez-theme-functionality'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Tab Title', 'houzez-theme-functionality'),
            ]
        );

        /*$repeater->add_control(
            'show_as_default', [
                'label' => esc_html__('Set as Default', 'houzez-theme-functionality'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'inactive',
                'return_value' => 'active-default',
            ]
        );*/
        
        $repeater->add_control(
            'tabs_icon_type', [
                'label' => esc_html__('Icon Type', 'houzez-theme-functionality'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'none' => [
                        'title' => esc_html__('None', 'houzez-theme-functionality'),
                        'icon' => 'fa fa-ban',
                    ],
                    'icon' => [
                        'title' => esc_html__('Icon', 'houzez-theme-functionality'),
                        'icon' => 'fas fa-cog',
                    ],
                    'image' => [
                        'title' => esc_html__('Image', 'houzez-theme-functionality'),
                        'icon' => 'far fa-image',
                    ],
                ],
                'default' => 'icon',
            ]
        );
        
        $repeater->add_control(
            'tabs_icon_icon', [
                'label' => esc_html__('Icon', 'houzez-theme-functionality'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
                'condition' => ['tabs_icon_type' => 'icon']
            ]
        );
        
        $repeater->add_control(
            'tab_icon_image', [
                'label' => esc_html__('Image', 'houzez-theme-functionality'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => ['tabs_icon_type' => 'image']
            ]
        );
        

        $repeater->end_controls_tab();


        // Data Query
        $repeater->start_controls_tab(
            'data_query_tab',
            [
                'label' => esc_html__( 'Query', 'houzez-theme-functionality' ),
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
                $terms = get_terms( 
                    array(
                        'taxonomy' => $single_tax->name,
                        'hide_empty' => false
                )   );

                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $options_array[ $term->slug ] = $term->name;
                    }
                }

                $repeater->add_control(
                    $single_tax->name,
                    [
                        'label'    => $single_tax->label,
                        'type'     => Controls_Manager::SELECT2,
                        'multiple' => true,
                        'label_block' => true,
                        'options'  => $options_array,
                    ]
                );
            }
        }
        

        $repeater->add_control(
            'properties_by_agents',
            [
                'label'    => esc_html__('Properties by Agents', 'houzez'),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'options'  => array_slice( houzez_get_agents_array(), 1, null, true ),
            ]
        );

        $repeater->add_control(
            'properties_by_agencies',
            [
                'label'    => esc_html__('Properties by Agencies', 'houzez'),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'options'  => array_slice( houzez_get_agency_array(), 1, null, true ),
            ]
        );

        $repeater->add_control(
            'min_price',
            [
                'label'    => esc_html__('Minimum Price', 'houzez'),
                'type'     => Controls_Manager::NUMBER,
                'label_block' => false,
            ]
        );
        $repeater->add_control(
            'max_price',
            [
                'label'    => esc_html__('Maximum Price', 'houzez'),
                'type'     => Controls_Manager::NUMBER,
                'label_block' => false,
            ]
        );
        

        $repeater->add_control(
            'houzez_user_role',
            [
                'label'     => esc_html__( 'User Role', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    ''  => esc_html__( 'All', 'houzez-theme-functionality'),
                    'houzez_owner'    => 'Owner',
                    'houzez_manager'  => 'Manager',
                    'houzez_agent'  => 'Agent',
                    'author'  => 'Author',
                    'houzez_agency'  => 'Agency',
                ],
                'description' => '',
                'default' => '',
            ]
        );

        $repeater->add_control(
            'featured_prop',
            [
                'label'     => esc_html__( 'Featured Properties', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    ''  => esc_html__( '- Any -', 'houzez-theme-functionality'),
                    'no'    => esc_html__('Without Featured', 'houzez'),
                    'yes'  => esc_html__('Only Featured', 'houzez')
                ],
                "description" => esc_html__("You can make a post featured by clicking featured properties checkbox while add/edit post", "houzez-theme-functionality"),
                'default' => '',
            ]
        );

        $repeater->end_controls_tab();

    
        $repeater->end_controls_tabs();

        $this->add_control(
            'tabs_items',
            [
                'type'        => Controls_Manager::REPEATER,
                'title_field' => '{{{ tab_title }}}',
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'tab_title' => 'Tab title 1',
                    ],
                    [
                        'tab_title' => 'Tab title 2',
                    ],
                    [
                        'tab_title' => 'Tab title 3',
                    ],
                ],
            ]
        );

        $this->end_controls_section();


        //Products Style
        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Properties', 'houzez-theme-functionality' ),
                 'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'grid_style',
            [
                'label'   => esc_html__( 'Cards Style', 'houzez-theme-functionality' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'cards-v1',
                'options' => array(
                    'cards-v1'     => 'Property Cards v1',
                    'cards-v2'     => 'Property Cards v2',
                    'cards-v3'     => 'Property Cards v3',
                    'cards-v5'     => 'Property Cards v5',
                    'cards-v6'     => 'Property Cards v6',
                    'cards-v7'     => 'Property Cards v7',
                ),
            ]
        );

        $this->add_control(
            'module_type',
            [
                'label'     => esc_html__( 'Layout', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'grid_3_cols'  => esc_html__( '3 Columns', 'houzez-theme-functionality'),
                    'grid_4_cols'  => esc_html__( '4 Columns', 'houzez-theme-functionality'),
                    'grid_2_cols'    => esc_html__( '2 Columns', 'houzez-theme-functionality'),
                ],
                'description' => '',
                'default' => 'grid_3_cols',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'listing_thumb',
                'exclude' => [ 'custom', 'thumbnail', 'houzez-image_masonry', 'houzez-map-info', 'houzez-variable-gallery', 'houzez-gallery' ],
                'include' => [],
                'default' => 'houzez-item-image-1',
            ]
        );

        $this->add_control(
            'sort_by',
            [
                'label'     => esc_html__( 'Sort By', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => houzez_sorting_array(),
                'description' => '',
                'default' => '',
            ]
        );

        $this->add_control(
            'posts_limit',
            [
                'label'     => esc_html__('Number of properties', 'houzez-theme-functionality'),
                'type'      => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 500,
                'step'    => 1,
                'default' => 9,
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
            'pagination_type',
            [
                'label'     => esc_html__( 'Pagination', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => houzez_pagination_type(),
                'description' => '',
                'default' => 'loadmore',
            ]
        );


        $this->end_controls_section();

        /*--------------------------------------------------------------------------------
        * Show/Hide 
        * -------------------------------------------------------------------------------*/
        $this->start_controls_section(
            'hide_show_section',
            [
                'label'     => esc_html__( 'Show/Hide Data', 'houzez-theme-functionality' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'hide_compare',
            [
                'label' => esc_html__( 'Hide Compare Button', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .property-cards-module .item-tools .item-compare' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_favorite',
            [
                'label' => esc_html__( 'Hide Favorite Button', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .property-cards-module .item-tools .item-favorite' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_preview',
            [
                'label' => esc_html__( 'Hide Preview Button', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .property-cards-module .item-tools .item-preview' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_featured_label',
            [
                'label' => esc_html__( 'Hide Featured Label', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .property-cards-module .label-featured' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_status',
            [
                'label' => esc_html__( 'Hide Status', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .property-cards-module .labels-wrap .label-status' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_label',
            [
                'label' => esc_html__( 'Hide Labels', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .property-cards-module .labels-wrap .hz-label' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_button',
            [
                'label' => esc_html__( 'Hide Details Button', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .property-cards-module .item-body .btn-item' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hide_author_date',
            [
                'label' => esc_html__( 'Hide Date & Agent', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'houzez-theme-functionality' ),
                'label_off' => esc_html__( 'No', 'houzez-theme-functionality' ),
                'return_value' => 'none',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .property-cards-module .item-footer' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .property-cards-module .item-author' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .property-cards-module .item-date' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .property-cards-module .btn-item' => 'bottom: 20px;',
                ],
            ]
        );

        $this->end_controls_section();

        /*
        * Tabs Style
        ******************************************************/
        $this->start_controls_section(
            'section_tabs_style',
            [
                'label' => __( 'Tabs', 'houzez-theme-functionality' ),
                 'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tab_title_color',
            [
                'label' => esc_html__('Title Color', 'houzez-theme-functionality'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tab-title' => 'color:{{VALUE}}' 
                ]
            ]
        );

        $this->add_control(
            'tab_title_color_hover',
            [
                'label' => esc_html__('Title Color Hover', 'houzez-theme-functionality'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.tab-title:hover' => 'color:{{VALUE}}' 
                ]
            ]
        );

        $this->add_control(
            'tab_title_border',
            [
                'label' => esc_html__('Active tab border color', 'houzez-theme-functionality'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property-tabs-module.property-nav-tabs-v1 .nav-item .nav-link.active, .property-tabs-module.property-nav-tabs-v2 .nav-item .nav-link.active' => 'box-shadow: 0 3px 0 inset {{VALUE}}',
                    '{{WRAPPER}} .property-tabs-module.property-nav-tabs-v3 .nav-link.active:before, .property-tabs-module.property-nav-tabs-v4 .nav-link.active:before' => 'border-top-color: {{VALUE}}',
                    '{{WRAPPER}} .property-tabs-module.property-nav-tabs-v3 .nav-link.active, .property-tabs-module.property-nav-tabs-v4 .nav-link.active' => 'border-bottom: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .property-tabs-module.property-nav-tabs-v5 .nav-link.active' => 'border-bottom: 3px solid {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'tab_border_after_color',
            [
                'label' => esc_html__('Active tab border after color', 'houzez-theme-functionality'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property-tabs-module.property-nav-tabs-v3 .nav-link.active:after, .property-tabs-module.property-nav-tabs-v4 .nav-link.active:after' => 'border-top-color: {{VALUE}}',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'houzez_tabs_style',
                            'operator' => 'in',
                            'value' => [
                                'property-nav-tabs-v3',
                                'property-nav-tabs-v4',
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tabs_typography',
                'selector' => '{{WRAPPER}} span.tab-title',
            ]
        );

        $this->end_controls_section();

    }

    protected function products_tabs( $settings ) {
        ?>
        <div class="houzez-products-tabs-js property-tabs-module <?php echo esc_attr($settings['houzez_tabs_style']); ?>">
            <ul class="nav nav-tabs property-nav-tabs justify-content-<?php echo esc_attr($settings['houzez_tabs_position']); ?>">

                <?php 
                $settings['module_id'] = $this->get_id();
                foreach ( $settings['tabs_items'] as $key => $item ) : 

                $link_classes  = '';

                if ( 0 === $key ) {
                    $link_classes .= 'active';
                }
                
                $encoded_settings  = wp_json_encode( $settings + $item );
                ?>
                <li data-json="<?php echo esc_attr( $encoded_settings ); ?>" class="nav-item">
                    <a class="nav-link <?php echo esc_attr($link_classes); ?>" data-toggle="tab" role="tab">
                        <?php 
                        if ( 'yes' === $settings['show_tabs_icon'] ):

                            if ( $item['tabs_icon_type'] === 'icon' ) {

                                if( isset( $item['tabs_icon_icon']['library'] ) && $item['tabs_icon_icon']['library'] == "svg" ) {?>

                                    <img class="tab-icon" src="<?php echo esc_attr( $item['tabs_icon_icon']['value']['url'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $item['tabs_icon_icon']['value']['id'], '_wp_attachment_image_alt', true ) ); ?>" width="20" height="20">

                                <?php
                                } else {
                                    echo '<i class="' . $item['tabs_icon_icon']['value'] . '"></i>';
                                }

                            } elseif ( $item['tabs_icon_type'] === 'image' ) { ?>

                                <img class="tab-icon" src="<?php echo esc_attr( $item['tab_icon_image']['url'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $item['tab_icon_image']['id'], '_wp_attachment_image_alt', true ) ); ?>" width="20" height="20">

                        <?php }
                        endif;
                        ?>
                        <?php if( $settings['show_tabs_title'] ) { ?>
                        <span class="tab-title"><?php echo esc_html( $item['tab_title'] ); ?></span>
                        <?php } ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <?php 
            if ( isset( $settings['tabs_items'][0] ) ) :
            
                $settings = $settings + $settings['tabs_items'][0];
                
                houzez_products_tab($settings);

            endif; 
            ?>
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

       $this->products_tabs( $settings );

    }


}

Plugin::instance()->widgets_manager->register( new Houzez_Properties_Tabs );