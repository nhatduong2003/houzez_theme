<?php
class Houzez_Library
{
	public function __construct()
	{
		$this->hooks();
		$this->register_templates_source();
	}

	public function hooks()
	{
		add_action('elementor/editor/after_enqueue_scripts', array($this, 'enqueue'));
		add_action('elementor/editor/footer', array($this, 'render'));
		add_action('elementor/frontend/before_enqueue_styles', array($this, 'inline_styles'));
	}

	public function inline_styles()
	{
?>
		<style>.houzez-library-modal-btn {margin-left: 5px;background: #35AAE1;vertical-align: top;font-size: 0 !important;}.houzez-library-modal-btn:before {content: '';width: 16px;height: 16px;background-image: url('<?php echo get_template_directory_uri() . '/img/favicon.png';?>');background-position: center;background-size: contain;background-repeat: no-repeat;}#houzez-library-modal .houzez-elementor-template-library-template-name {text-align: right;flex: 1 0 0%;}</style>
	<?php
	}

	public function register_templates_source()
	{
		Elementor\Plugin::instance()->templates_manager->register_source('Houzez_Library_Source');
	}

	public function enqueue()
	{
		wp_enqueue_script('houzez-blocks', get_template_directory_uri() . '/inc/blocks/assets/js/blocks-templates.js', array('jquery'), '1.0.0', true);
	}

	public function render()
	{
	?>
		<script type="text/html" id="tmpl-elementor-houzez-library-modal-header">
			<div class="elementor-templates-modal__header">
				<div class="elementor-templates-modal__header__logo-area">
					<div class="elementor-templates-modal__header__logo">
						<span class="elementor-templates-modal__header__logo__title">
							Houzez Library
						</span>
					</div>
				</div>

				<div class="elementor-templates-modal__header__menu-area">
					<div id="elementor-houzez-library-header-menu">
						<div id="houzez-tab-block" class="elementor-component-tab elementor-template-library-menu-item elementor-active" data-tab="block">Blocks</div>
						<div id="houzez-tab-template" class="elementor-component-tab elementor-template-library-menu-item" data-tab="template">Pages</div>
					</div>
				</div>

				<div class="elementor-templates-modal__header__items-area">
					<div class="elementor-templates-modal__header__close elementor-templates-modal__header__close--normal elementor-templates-modal__header__item">
						<i class="eicon-close" aria-hidden="true" title="<?php echo esc_html__('Close', 'houzez'); ?>"></i>

						<span class="elementor-screen-only">
							<?php echo esc_html__('Close', 'houzez'); ?>
						</span>
					</div>
				</div>
			</div>
		</script>

		<script type="text/html" id="tmpl-elementor-houzez-library-modal-order">
			<div id="elementor-template-library-filter">
				<select id="elementor-template-library-filter-subtype" class="elementor-template-library-filter-select" data-elementor-filter="subtype">
					<option value="all"><?php echo esc_html__('All', 'houzez'); ?></option>
					<# data.tags.forEach(function(item, i) { #>
						<option value="{{{item.slug}}}">{{{item.title}}}</option>
						<# }); #>
				</select>
			</div>
		</script>

		<script type="text/template" id="tmpl-elementor-houzez-library-header-menu">
			<# jQuery.each( tabs, ( tab, args ) => { #>	
				<div class="elementor-component-tab elementor-template-library-menu-item" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
			<# } ); #>
		</script>

		<script type="text/html" id="tmpl-elementor-houzez-library-modal">
			<div id="elementor-template-library-templates" data-template-source="remote">
				<div id="elementor-template-library-toolbar">
					<div id="elementor-template-library-filter-toolbar-remote" class="elementor-template-library-filter-toolbar"></div>

					<div id="elementor-template-library-filter-text-wrapper">
						<label for="elementor-template-library-filter-text" class="elementor-screen-only"><?php echo esc_html__('Search Templates:', 'houzez'); ?></label>
						<input id="elementor-template-library-filter-text" placeholder="<?php echo esc_attr__('Search', 'houzez'); ?>">
						<i class="eicon-search"></i>
					</div>
				</div>

				<div id="elementor-template-library-templates-container"></div>

				<div id="elementor-template-library-footer-banner">
					<img class="elementor-nerd-box-icon" src="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/elementor/assets/images/information.svg">
					<div class="elementor-excerpt">Stay tuned! More awesome templates coming real soon.</div>
				</div>
			</div>

			<div class="elementor-loader-wrapper" style="display: none">
				<div class="elementor-loader">
					<div class="elementor-loader-boxes">
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
					</div>
				</div>
				<div class="elementor-loading-title"><?php echo esc_html__('Loading', 'houzez'); ?></div>
			</div>
		</script>

		<script type="text/html" id="tmpl-elementor-houzez-library-modal-item">
			<# data.elements.forEach(function(item, i) { #>
				
				<div class="elementor-template-library-template elementor-template-library-template-remote elementor-template-library-template-{{{item.type === 'template' ? 'page' : 'block'}}}" data-slug="{{{item.slug}}}" data-tag="{{{item.category}}}" data-type="{{{item.type}}}">

					<div class="elementor-template-library-template elementor-template-library-template-remote elementor-template-library-template-block" data-title="{{{item.image}}}" data-slug="{{{item.slug}}}" data-tag="{{{item.class}}}">
					
					<div class="elementor-template-library-template-body">
						<# if (item.type === 'block') { #>
							<img src="{{{item.image}}}">
						<# } else { #>
						<div class="elementor-template-library-template-screenshot" style="background-image: url({{{item.image}}})"></div>
						<# } #>

						<a class="elementor-template-library-template-preview" href="{{{item.link}}}" target="_blank">
							<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
						</a>
					</div>

					<div class="elementor-template-library-template-footer">
						<a class="elementor-template-library-template-action elementor-template-library-template-insert elementor-button" data-id="{{{item.id}}}">
							<i class="eicon-file-download" aria-hidden="true"></i>
							<span class="elementor-button-title">Insert</span>
						</a>
						<div class="houzez-elementor-template-library-template-name">{{{item.title}}}</div>
					</div>
				</div>
				<# }); #>
		</script>
<?php
	}
}
