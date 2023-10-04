<?php
namespace Elementor;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Houzez_Create_Listing_Btn extends Widget_Base {


	public function get_name() {
		return 'houzez-create-listing-btn';
	}

	public function get_title() {
		return __( 'Create Listing Button', 'houzez-theme-functionality' );
	}

	public function get_icon() {
		return 'houzez-element-icon eicon-button';
	}

	public function get_categories() {
		return [ 'houzez-elements', 'favethemes_studio_header', 'favethemes_studio_footer' ];
	}

	public function get_keywords() {
		return ['Create', 'Listing', 'Add New Property' ];
	}

	protected function register_controls() {
		//parent::register_controls();


		$this->start_controls_section(
            'create_listing_content',
            [
                'label' => __( 'Create Listing Button', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => __( 'Padding', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .btn-create-listing' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '0',
					'bottom' => '0',
					'left' => '15',
					'right' => '15'
				]
			]
		);

		$this->add_responsive_control(
			'btn_margin',
			[
				'label' => __( 'Margin', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .btn-create-listing' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .btn-create-listing',
			]
		);

		$this->add_control(
			'create_btn_color',
			[
				'label' => __( 'Button Color', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-create-listing' => 'color: {{VALUE}}',
				],
				'default' => '#ffffff'
			]
		);

		$this->add_control(
			'create_btn_color_hover',
			[
				'label' => __( 'Button Color Hover', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-create-listing:hover, .btn-create-listing:active' => 'color: {{VALUE}}',
				],
				'default' => '#fffffffc',
				'alpha' => true,
			]
		);

		$this->add_control(
			'create_btn_bg_color',
			[
				'label' => __( 'Button Background Color', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-create-listing' => 'background-color: {{VALUE}}',
				],
				'default' => '#00aeff'
			]
		);

		$this->add_control(
			'create_btn_bg_color_hover',
			[
				'label' => __( 'Button Background Color Hover', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-create-listing:hover, .btn-create-listing:active' => 'background-color: {{VALUE}}',
				],
				'default' => '#00aeffa6'
			]
		);

		$this->add_control(
			'create_btn_border_color',
			[
				'label' => __( 'Button Border Color', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-create-listing' => 'border-color: {{VALUE}}',
				],
				'default' => '#00aeff'
			]
		);

		$this->add_control(
			'create_btn_border_color_hover',
			[
				'label' => __( 'Button Border Color Hover', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-create-listing:hover, .btn-create-listing:active' => 'border-color: {{VALUE}}',
				],
				'default' => '#00aeffa6'
			]
		);

       
		$this->end_controls_section();

		$this->start_controls_section(
            'create_section_link',
            [
                'label' => __( 'Button Link', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
			'show_custom',
			[
				'label' => __( 'Custom Link', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'houzez-theme-functionality' ),
				'label_off' => __( 'No', 'houzez-theme-functionality' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'create_btn_link',
			[
				'label' => __( 'Link', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'houzez-theme-functionality' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'show_custom' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		
	}

	protected function render() {
		global $ele_settings; 
		$settings = $this->get_settings();
		
		$dashboard_add_listing = houzez_get_template_link_2('template/user_dashboard_submit.php');
		$link_target = $link_nofollow = '';

		$target = $settings['create_btn_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['create_btn_link']['nofollow'] ? ' rel="nofollow"' : '';

		if( $settings['show_custom'] == 'yes' ) {
			$dashboard_add_listing = $settings['create_btn_link']['url'];
			$link_target = $target;
			$link_nofollow = $nofollow;
		}

		if( houzez_check_role() ){ ?>

        <a class="btn btn-create-listing" href="<?php echo esc_url($dashboard_add_listing); ?>" <?php echo $link_nofollow; ?> <?php echo $link_target; ?>>
            <?php echo houzez_option('dsh_create_listing', 'Create a Listing'); ?>
        </a>

        <?php }
	}

}
Plugin::instance()->widgets_manager->register( new Houzez_Create_Listing_Btn );