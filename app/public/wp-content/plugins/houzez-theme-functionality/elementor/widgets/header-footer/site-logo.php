<?php
namespace Houzez\Elementor\Widgets\HeaderFooter;

use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Site Logo Widget.
 * @since 1.0.0
 */
class Houzez_Site_Logo extends Widget_Base {

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
        return 'houzez_site_logo';
    }

    /**
     * Get widget title.
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Site Logo', 'houzez-theme-functionality' );
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
        return 'houzez-element-icon eicon-site-logo';
    }

    public function get_keywords() {
        return [ 'logo', 'brand', 'houzez' ];
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
     * Register widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
	protected function register_controls() {
		$this->site_logo_controls();
		$this->site_logo_styling_controls();
		$this->site_logo_caption_styling_controls();
	}

	/**
	 * Register Site Logo Styling Controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function site_logo_styling_controls() {
		$this->start_controls_section(
			'section_style_site_logo',
			[
				'label' => esc_html__( 'Site logo', 'houzez-theme-functionality' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'logo_top',
			[
				'label'          => __( 'Top', 'houzez-theme-functionality' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units'     => [ 'px' ],
				'range'          => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .favethemes-site-logo' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'          => __( 'Width', 'houzez-theme-functionality' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ '%', 'px', 'vw' ],
				'range'          => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .favethemes-site-logo img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'logo_source' => 'custom_logo',
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label'          => __( 'Max Width', 'houzez-theme-functionality' ) . ' (%)',
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ '%' ],
				'range'          => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .favethemes-site-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'logo_source' => 'custom_logo',
				],
			]
		);

		$this->add_control(
			'separator_panel_style',
			array(
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
            )
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal',
			array(
				'label' => __( 'Normal', 'houzez-theme-functionality' ),
            )
		);

		$this->add_control(
			'opacity',
			array(
				'label' => __( 'Opacity', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .favethemes-site-logo img, {{WRAPPER}} .favethemes-site-logo .text-logo' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .favethemes-site-logo img, {{WRAPPER}} .favethemes-site-logo .text-logo',
            )
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			array(
				'label' => __( 'Hover', 'houzez-theme-functionality' ),
            )
		);

		$this->add_control(
			'opacity_hover',
			array(
				'label' => __( 'Opacity', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}  .favethemes-site-logo:hover img, {{WRAPPER}} .favethemes-site-logo:hover .text-logo' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .favethemes-site-logo:hover img, {{WRAPPER}} .favethemes-site-logo:hover .text-logo',
            )
		);

		$this->add_control(
			'background_hover_transition',
			array(
				'label' => __( 'Transition Duration', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .favethemes-site-logo img, {{WRAPPER}} .favethemes-site-logo .text-logo' => 'transition-duration: {{SIZE}}s',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .favethemes-site-logo img, {{WRAPPER}} .favethemes-site-logo .text-logo',
				'separator' => 'before',
            )
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label' => __( 'Border Radius', 'houzez-theme-functionality' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .favethemes-site-logo img, {{WRAPPER}} .favethemes-site-logo .text-logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'image_box_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .favethemes-site-logo img, {{WRAPPER}} .favethemes-site-logo .text-logo',
			)
        );

		$this->end_controls_section();
	}

	/**
	 * Register Site Logo Controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function site_logo_controls() {
		
		 $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Logo', 'houzez-theme-functionality' ),
            ]
        );

        $this->add_control(
			'logo_source',
			[
				'label' => esc_html__( 'Logo Source', 'houzez-theme-functionality' ),
				'type' => 'select',
				'options' => [
					'customizer' => esc_html__( 'Theme Options', 'houzez-theme-functionality' ),
					'custom_logo' => esc_html__( 'Custom Logo', 'houzez-theme-functionality' ),
				],
				'default' => 'customizer',
			]
		);

		$this->add_control(
			'important_note',
			[
				'type' => 'raw_html',
				'raw' => sprintf(
					__( 'Please select or upload your <strong>Logo</strong> in the <a target="_blank" href="%1$s"><em>Theme Options</em></a>.', 'houzez-theme-functionality' ),
					add_query_arg( array(
						'page' => 'houzez_options',
						'tab' => '9',
					), admin_url( 'admin.php' ) )
				),
				'content_classes' => 'elementor-control-field-description',
				'condition' => [
					'logo_source' => 'customizer',
				],
			]
		);

		$this->add_control(
			'custom_image',
			[
				'label' => esc_html__( 'Choose Image', 'houzez-theme-functionality' ),
				'type' => 'media',
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'logo_source' => 'custom_logo',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'logo_size',
				'label'   => __( 'Image Size', 'houzez-theme-functionality' ),
				'default' => 'medium',
				'condition' => [
					'logo_source' => 'custom_logo',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'houzez-theme-functionality' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'houzez-theme-functionality' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'houzez-theme-functionality' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'houzez-theme-functionality' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .favethemes-site-logo' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'caption_source',
			[
				'label'   => __( 'Caption', 'houzez-theme-functionality' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'no'  => __( 'No', 'houzez-theme-functionality' ),
					'yes' => __( 'Yes', 'houzez-theme-functionality' ),
				],
				'default' => 'no',
				'condition' => [
					'logo_source' => 'custom_logo',
				],
			]
		);

		$this->add_control(
			'caption',
			[
				'label'       => __( 'Custom Caption', 'houzez-theme-functionality' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Enter caption', 'houzez-theme-functionality' ),
				'condition'   => [
					'caption_source' => 'yes',
					'logo_source' => 'custom_logo',
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'link_to',
			[
				'label'   => __( 'Link', 'houzez-theme-functionality' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'houzez-theme-functionality' ),
					'none'    => __( 'None', 'houzez-theme-functionality' ),
					'custom'  => __( 'Custom URL', 'houzez-theme-functionality' ),
				],
				'condition' => [
					'logo_source' => 'custom_logo',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'houzez-theme-functionality' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'houzez-theme-functionality' ),
				'condition'   => [
					'link_to' => 'custom',
					'logo_source' => 'custom_logo',
				],
				'show_label'  => false,
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Register Site Logo style Controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function site_logo_caption_styling_controls() {
		$this->start_controls_section(
			'section_style_caption',
			[
				'label'     => __( 'Caption', 'houzez-theme-functionality' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'caption_source!' => 'no',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'houzez-theme-functionality' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} .site-tagline' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'caption_background_color',
			[
				'label'     => __( 'Background Color', 'houzez-theme-functionality' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .site-tagline' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .site-tagline',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'caption_text_shadow',
				'selector' => '{{WRAPPER}} .site-tagline',
			]
		);

		$this->add_responsive_control(
			'caption_padding',
			[
				'label'      => __( 'Padding', 'houzez-theme-functionality' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .site-tagline' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'caption_space',
			[
				'label'     => __( 'Spacing', 'houzez-theme-functionality' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .site-tagline' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: 0px;',
				],
			]
		);

		$this->end_controls_section();
	}
	

	/**
	 * Check if the current widget has caption
	 *
	 * @access private
	 * @since 1.0.0
	 *
	 * @param array $settings returns settings.
	 *
	 * @return boolean
	 */
	private function has_caption( $settings ) {
		return ( ! empty( $settings['caption_source'] ) && 'no' !== $settings['caption_source'] );
	}

	/**
	 * Get the caption for current widget.
	 *
	 * @access private
	 * @since 1.0.0
	 * @param array $settings returns the caption.
	 *
	 * @return string
	 */
	private function get_caption( $settings ) {
		$caption = '';
		if ( 'yes' === $settings['caption_source'] ) {
			$caption = ! empty( $settings['caption'] ) ? $settings['caption'] : '';
		}
		return $caption;
	}

	/**
	 * Render Site Image output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @param array $size returns the size of an image.
	 * @access public
	 */
	public function site_image_url( $size ) {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['custom_image']['url'] ) ) {
			$logo = wp_get_attachment_image_src( $settings['custom_image']['id'], $size, true );
		} else {
			$logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), $size, true );
		}
		return $logo[0];
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
        $has_caption = $this->has_caption( $settings );
        $logo_source = $settings['logo_source'];

        if ( 'default' === $settings['link_to'] ) {
			$link = site_url();
			$this->add_render_attribute( 'link', 'href', $link );
		} else {
			$link = $this->get_link_url( $settings );

			if ( $link ) {
				$this->add_link_attributes( 'link', $link );
			}
		}
		?>
		<div class="favethemes-site-logo">
			<?php if ( $link ) : ?>
				<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
			<?php endif; ?>
			<?php

				if ( 'custom_logo' === $logo_source ) {
					$this->custom_logo_render( $settings );

					if ( $has_caption ) {
						$caption_text = $this->get_caption( $settings );
						if ( ! empty( $caption_text ) ) {

							echo '<p class="site-tagline">'.wp_kses_post( $caption_text ).'</p>';
						}
						
					}
				}

				if ( 'customizer' === $logo_source ) {
					$this->customizer_logo_render();
				}

			?>
			<?php if ( $link ) : ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
    }

    protected function custom_logo_render( $settings ) {
		$has_caption = $this->has_caption( $settings );
		$size = $settings['logo_size_size'];
		$site_image = $this->site_image_url( $size );
		$img_animation = '';


		if ( ! empty( $site_image ) ) {

			if ( 'custom' !== $size ) {
				$image_size = $size;
			} else {
				require_once ELEMENTOR_PATH . 'includes/libraries/bfi-thumb/bfi-thumb.php';

				$image_dimension = $settings['logo_size_custom_dimension'];

				$image_size = [
					// Defaults sizes.
					0           => null, // Width.
					1           => null, // Height.

					'bfi_thumb' => true,
					'crop'      => true,
				];

				$has_custom_size = false;
				if ( ! empty( $image_dimension['width'] ) ) {
					$has_custom_size = true;
					$image_size[0]   = $image_dimension['width'];
				}

				if ( ! empty( $image_dimension['height'] ) ) {
					$has_custom_size = true;
					$image_size[1]   = $image_dimension['height'];
				}

				if ( ! $has_custom_size ) {
					$image_size = 'full';
				}

			} // end image size

			$image_url = $site_image;

			if ( ! empty( $settings['custom_image']['url'] ) ) {
				$image_data = wp_get_attachment_image_src( $settings['custom_image']['id'], $image_size, true );

				$site_image_class = 'elementor-animation-';

				if ( ! empty( $settings['hover_animation'] ) ) {
					$img_animation = $settings['hover_animation'];
				}
				if ( ! empty( $image_data ) ) {
					$image_url = $image_data[0];
				}

				$class_animation = $site_image_class . $img_animation;

				echo '<img class="image-logo '.esc_attr( $class_animation ).'"  src="'.esc_url( $image_url ).'" alt="'.esc_attr( Control_Media::get_image_alt( $settings['custom_image'] ) ).'"/>';

			}
		} // end ! empty( $site_image )

	}

	protected function customizer_logo_render() {
		global $post;

		$fave_main_menu_trans = '';
		if( houzez_postid_needed() ) {
			$fave_main_menu_trans = get_post_meta($post->ID, 'fave_main_menu_trans', true);
		}
		$splash_logo = houzez_option( 'custom_logo_splash', false, 'url' );
		$custom_logo = houzez_option( 'custom_logo', false, 'url' );
		$splash_logolink_type = houzez_option('splash-logolink-type');
		$splash_logolink = houzez_option('splash-logolink');

		if( is_page_template( 'template/template-splash.php' ) ) {
			if($splash_logolink_type == 'custom') {
				$splash_logo_link = $splash_logolink;
			} else {
				$splash_logo_link = home_url( '/' );
			}
		} else {
			$splash_logo_link = home_url( '/' );
		}

		$logo_height = houzez_option('retina_logo_height');
		$logo_width = houzez_option('retina_logo_width');

		?>

		<?php if ( is_page_template( 'template/template-splash.php' ) || ($fave_main_menu_trans == 'yes' && houzez_option('header_style') == '4' ) && !wp_is_mobile() ) { ?>
			<div class="logo logo-splash">
				<a href="<?php echo esc_url( $splash_logo_link ); ?>">
					<?php if( !empty( $splash_logo ) ) { ?>
						<img src="<?php echo esc_url( $splash_logo ); ?>" height="<?php echo esc_attr($logo_height); ?>" width="<?php echo esc_attr($logo_width); ?>" alt="logo">
					<?php } ?>
				</a>
			</div>
		<?php } else { ?>

			<div class="logo logo-desktop">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php if( !empty( $custom_logo ) ) { ?>
						<img src="<?php echo esc_url( $custom_logo ); ?>" height="<?php echo esc_attr($logo_height); ?>" width="<?php echo esc_attr($logo_width); ?>" alt="logo">
					<?php } ?>
				</a>
			</div>
		<?php }

	}


	private function get_link_url( $settings ) {
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}

			if ( ! empty( $settings['is_external'] ) ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $settings['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}

			return $settings['link'];
		}

		if ( 'default' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return site_url();
		}
	}
}

Plugin::instance()->widgets_manager->register( new Houzez_Site_Logo );
