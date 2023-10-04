<?php
namespace Elementor;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Houzez_Login_Modal extends Widget_Base {


	public function get_name() {
		return 'houzez-login-modal';
	}

	public function get_title() {
		return __( 'Login Modal', 'houzez-theme-functionality' );
	}

	public function get_icon() {
		return 'houzez-element-icon eicon-lock-user';
	}

	public function get_categories() {
		return [ 'houzez-elements', 'favethemes_studio_header', 'favethemes_studio_footer' ];
	}

	public function get_keywords() {
		return ['Login', 'Register', 'Modal' ];
	}

	protected function register_controls() {
		//parent::register_controls();


		$this->start_controls_section(
            'login_modal_content',
            [
                'label' => __( 'Login Modal', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'login_type',
            [
                'label'     => esc_html__( 'Login, register type', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                	'as_icon' => esc_html__('Show as Icon', 'houzez-theme-functionality'),
                    'as_text' => esc_html__('Show as Text', 'houzez-theme-functionality'),
                ),
                'description' => '',
                'default' => 'as_icon',
            ]
        );

        $this->add_control(
            'logged_in_view',
            [
                'label'     => esc_html__( 'Show LoggedIn View', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                	'no' => esc_html__('No', 'houzez-theme-functionality'),
                    'yes' => esc_html__('Yes', 'houzez-theme-functionality'),
                ),
                'description' => esc_html__('Only for design purpose', 'houzez-theme-functionality'),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'show_dropdown',
            [
                'label'     => esc_html__( 'Show Drop Down', 'houzez-theme-functionality' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                	'no' => esc_html__('No', 'houzez-theme-functionality'),
                    'show' => esc_html__('Yes', 'houzez-theme-functionality'),
                ),
                'description' => esc_html__('Only for design purpose', 'houzez-theme-functionality'),
                'default' => 'no',
            ]
        );

        $this->add_responsive_control(
            'login_icon_size',
            [
                'label' => esc_html__( 'Icon Size(px)', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'login_type' => 'as_icon'
                ],
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
                    '{{WRAPPER}} .btn-icon-login-register' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
			'login_alignment',
			[
				'label' => __( 'Alignment', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'houzez-theme-functionality' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'houzez-theme-functionality' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'houzez-theme-functionality' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .login-register' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'logged_in_view_position',
			[
				'label' => __( 'LoggedIn Position', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'houzez-theme-functionality' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'houzez-theme-functionality' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .navbar-logged-in-wrap' => 'float: {{VALUE}}',
				],
				'default' => 'right',
			]
		);

		$this->add_responsive_control(
			'login_padding',
			[
				'label' => __( 'Padding', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .btn-icon-login-register' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    'login_type' => 'as_icon'
                ],
			]
		);

		$this->add_responsive_control(
			'login_text_padding',
			[
				'label' => __( 'Login Text Padding', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .login-link a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    'login_type' => 'as_text'
                ],
			]
		);

		$this->add_responsive_control(
			'register_text_padding',
			[
				'label' => __( 'Register Text Padding', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .register-link a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    'login_type' => 'as_text'
                ],
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
            'typography_section',
            [
                'label' => __( 'Typography', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'nav_links_typo',
                'label'    => esc_html__( 'Nav Links', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .logged-in-nav',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'login_register_text_typo',
                'label'    => esc_html__( 'Login/Register Text', 'houzez-theme-functionality' ),
                'selector' => '{{WRAPPER}} .login-link, .register-link',
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
            'tab_style',
            [
                'label' => __( 'Colors', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'login_icon_color',
			[
				'label' => __( 'Icon Color', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-icon-login-register' => 'color: {{VALUE}}',
				],
				'default' => '#004274'
			]
		);
		$this->add_control(
			'login_icon_color_hover',
			[
				'label' => __( 'Icon Color Hover', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-icon-login-register:hover' => 'color: {{VALUE}}',
				],
				'default' => '#00aeef'
			]
		);

		$this->add_control(
			'login_regis_text_color',
			[
				'label' => __( 'Login/Register Text', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .login-link a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .register-link a' => 'color: {{VALUE}}',
				],
				'default' => '#00aeef'
			]
		);

		$this->add_control(
			'login_regis_text_color_hover',
			[
				'label' => __( 'Login/Register Text Hover', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .login-link a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .register-link a:hover' => 'color: {{VALUE}}',
				],
				'default' => '#00aeef'
			]
		);

		$this->add_control(
			'dropdown_bg',
			[
				'label' => __( 'Drop Down Background', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .logged-in-nav a' => 'background-color: {{VALUE}}',
				],
				'default' => '#ffffff'
			]
		);

		$this->add_control(
			'dropdown_border_color',
			[
				'label' => __( 'Drop Down border', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .logged-in-nav a' => 'border-color: {{VALUE}}',
				],
				'default' => '#e6e6e6'
			]
		);

		$this->add_control(
			'dropdown_text_color',
			[
				'label' => __( 'User Nav Links', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .logged-in-nav a' => 'color: {{VALUE}}',
				],
				'default' => '#2e3e49'
			]
		);

		$this->add_control(
			'dropdown_bg_hover',
			[
				'label' => __( 'Drop Down Hover Background', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .logged-in-nav a:hover' => 'background-color: {{VALUE}}',
				],
				'default' => '#00aeff1a'
			]
		);

		$this->add_control(
			'dropdown_border_color_hover',
			[
				'label' => __( 'Drop Down border hover', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .logged-in-nav a:hover' => 'border-color: {{VALUE}}',
				],
				'default' => '#dce0e0'
			]
		);

		$this->add_control(
			'dropdown_text_color_hover',
			[
				'label' => __( 'User Nav Links Hover', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .logged-in-nav a:hover' => 'color: {{VALUE}}',
				],
				'default' => '#00aeff'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
            'tab_style_sizes',
            [
                'label' => __( 'Sizes & Spacing', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'avatar_radius',
            [
                'label' => esc_html__( 'Avatar Border Radius(px)', 'houzez-theme-functionality' ),
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
                    '{{WRAPPER}} .rounded' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_width',
            [
                'label' => esc_html__( 'Drop Down Size(px)', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
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
                    '{{WRAPPER}} .logged-in-nav' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_top_margin',
            [
                'label' => esc_html__( 'Position from Top', 'houzez-theme-functionality' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'size' => '45',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => '45',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => '45',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .logged-in-nav' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
            'tab_box_shadow',
            [
                'label' => __( 'Box Shadow', 'houzez-theme-functionality' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'drop_down_box_shadow',
				'label'    => esc_html__( 'Drop Down Box Shadow', 'houzez-theme-functionality' ),
				'selector' => '{{WRAPPER}} .logged-in-nav',
			]
		);
		$this->end_controls_section();

		
	}

	protected function render() {
		global $ele_settings; 
		$settings = $this->get_settings();
		$ele_settings = $settings;
		$login_type = $settings['login_type'];

		if ( Plugin::$instance->editor->is_edit_mode() ) { global $ele_edit_mode_settings;  $ele_edit_mode_settings = $settings; ?>

			<?php if( $settings['logged_in_view'] == 'no') { ?>
			<div class="login-register">	
				<?php if( $login_type == "as_text" ) { ?>
				<ul class="login-register-nav">
					<li class="login-link">
						<a href="#" data-toggle="modal" data-target="#login-register-form"><?php esc_html_e('Login', 'houzez'); ?></a>
					</li>

					<li class="register-link">
						<a href="#" data-toggle="modal" data-target="#login-register-form"><?php esc_html_e('Register', 'houzez'); ?></a>
					</li>
				</ul>
				<?php } else { ?>
				<div class="login-link">
					<a class="btn btn-icon-login-register" href="#" data-toggle="modal" data-target="#login-register-form"><i class="houzez-icon icon-single-neutral-circle"></i></a>
				</div>
				<?php } ?>
			</div>

			<?php } else { get_template_part('template-parts/header/partials/logged-in-nav-ele'); } ?>

		<?php
		} else {
		?>
		
		<?php if( ! is_user_logged_in() ) { ?>
			<div class="login-register">	
				<?php if( $login_type == "as_text" ) { ?>
				<ul class="login-register-nav">
					<li class="login-link">
						<a href="#" data-toggle="modal" data-target="#login-register-form"><?php esc_html_e('Login', 'houzez'); ?></a>
					</li>

					<?php if( houzez_option('header_register') ) { ?>
					<li class="register-link">
						<a href="#" data-toggle="modal" data-target="#login-register-form"><?php esc_html_e('Register', 'houzez'); ?></a>
					</li>
					<?php } ?>
				</ul>
				<?php } else { ?>
				<div class="login-link">
					<a class="btn btn-icon-login-register" href="#" data-toggle="modal" data-target="#login-register-form"><i class="houzez-icon icon-single-neutral-circle"></i></a>
				</div>
				<?php } ?>
			</div>
		<?php
			} else {
				get_template_part('template-parts/header/partials/logged-in-nav-ele');
			}
		}

	}

}
Plugin::instance()->widgets_manager->register( new Houzez_Login_Modal );