<?php
/*
* Menu Options
*
*/
namespace Houzez\Admin;

defined( 'ABSPATH' ) || exit;

class Favethemes_Menu {

	/**
	 * Option field
	 *
	 * @var array
	 */
	private $fields = array(
		'design',
		'behavior',
		'width',
		'height',
		'html_block',
		'icon_type',
		'icon_id',
		'icon_width',
		'icon_height',
		'icon_html',
	);

	/**
	 * Holds all Elementor Menu Blocks.
	 *
	 * @var array
	 */
	private $elementor_blocks = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->elementor_blocks = houzez_get_elementor_library('html_blocks');
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'add_menu_fields' ), 10, 5 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'update_menu_fields' ), 10, 3 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Add custom fields for menu ite,.
	 *
	 * @param int       $item_id
	 * @param \WP_Post  $menu_item
	 * @param int       $depth   
	 * @param \stdClass $args    
	 * @param int       $menu_id 
	 */
	public function add_menu_fields( $item_id, $menu_item, $depth, $args, $menu_id ) {
		$behavior    = get_post_meta( $item_id, '_menu_item_behavior', true );
		$html_block  = get_post_meta( $item_id, '_menu_item_html_block', true );
		$design      = get_post_meta( $item_id, '_menu_item_design', true );
		$width       = get_post_meta( $item_id, '_menu_item_width', true );
		$height      = get_post_meta( $item_id, '_menu_item_height', true );
		$icon_type   = get_post_meta( $item_id, '_menu_item_icon_type', true );
		$icon_id     = get_post_meta( $item_id, '_menu_item_icon_id', true );
		$icon_width  = get_post_meta( $item_id, '_menu_item_icon_width', true );
		$icon_height = get_post_meta( $item_id, '_menu_item_icon_height', true );
		$icon_html   = get_post_meta( $item_id, '_menu_item_icon_html', true );

		ob_start();
		?>
		<div class="fave-menu-items-wrap">
			<h3><?php esc_html_e('Menu Item Options', 'houzez'); ?></h3>

			<div class="fave-items__menu-section">
				<h4 class="fave-item__section-title description-wide"><?php esc_html_e('Menu Dropdown', 'houzez'); ?></h4>
				<p class="field-menu-item-design description description-wide">
					<label for="edit-menu-item-design-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e('Design', 'houzez'); ?><br>
						<select id="edit-menu-item-design-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="menu-item-design[<?php echo esc_attr( $item_id ); ?>]">
							<option value="default" <?php selected( $design, 'default', true ); ?>><?php esc_html_e('Default', 'houzez'); ?></option>
							<option value="custom-size" <?php selected( $design, 'custom-size', true ); ?>><?php esc_html_e('Custom Size', 'houzez'); ?></option>
							<option value="container-width" <?php selected( $design, 'container-width', true ); ?>><?php esc_html_e('Container width', 'houzez'); ?></option>
							<option value="full-width" <?php selected( $design, 'full-width', true ); ?>><?php esc_html_e('Full width', 'houzez'); ?></option>
						</select>
					</label>
					<small class="field-instruction"><?php esc_html_e('Choose dropdown design.', 'houzez'); ?></small>
				</p>

				<p class="field-menu-item-width description description-thin">
					<label for="edit-menu-item-width-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Width', 'houzez' ); ?> (px)<br>
						<input type="number" id="edit-menu-item-width-<?php echo esc_attr( $item_id ); ?>" class="widefat" min="0" name="menu-item-width[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $width ); ?>">
					</label>
				</p>
				<p class="field-menu-item-height description description-thin">
					<label for="edit-menu-item-height-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Height (optional)', 'houzez' ); ?> (px)<br>
						<input type="number" id="edit-menu-item-height-<?php echo esc_attr( $item_id ); ?>" class="widefat" min="0" name="menu-item-height[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $height ); ?>">
					</label>
				</p>

				<p class="field-menu-item-block description description-wide">
					<label for="edit-menu-item-block-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e('Elementor Block', 'houzez'); ?><br>
						<select id="edit-menu-item-html_block-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="menu-item-html_block[<?php echo esc_attr( $item_id ); ?>]">
							<option value="" <?php selected( $html_block, '', true ); ?>><?php esc_html_e('-- None --', 'houzez'); ?></option>
							<?php foreach ( $this->elementor_blocks as $ele_block_id => $title ) : ?>
								<option value="<?php echo esc_attr( $ele_block_id ); ?>" <?php selected( $html_block, $ele_block_id, true ); ?>><?php echo esc_html( $title ); ?></option>
							<?php endforeach ?>
						</select>
					</label>
					<small class="field-instruction"><?php esc_html_e('Choose elementor block for this menu.', 'houzez'); ?></small>
				</p>
				<p class="field-menu-item-hover description description-wide">
					<label for="edit-menu-item-behavior-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Reveal', 'houzez' ); ?><br>
						<select id="edit-menu-item-behavior-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="menu-item-behavior[<?php echo esc_attr( $item_id ); ?>]">
							<option value="hover" <?php selected( $behavior, 'hover', true ); ?>><?php esc_html_e( 'On hover', 'houzez' ); ?></option>
							<option value="click" <?php selected( $behavior, 'click', true ); ?>><?php esc_html_e( 'On click', 'houzez' ); ?></option>
						</select>
					</label>
				</p>
			</div>
		</div>

		<?php
		echo ob_get_clean(); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Updates menu custom fields.
	 *
	 * @param int   $menu_id         ID of the updated menu.
	 * @param int   $menu_item_db_id ID of the updated menu item.
	 * @param array $args            An array of arguments used to update a menu item.
	 */
	public function update_menu_fields( $menu_id, $menu_item_db_id, $args ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		foreach ( $this->fields as $field ) {
			$key = 'menu-item-' . $field;

			if ( isset( $_POST[ $key ] ) && ! empty( $_POST[ $key ] ) && is_array( $_POST[ $key ] ) && isset( $_POST[ $key ][ $menu_item_db_id ] ) ) { //phpcs:ignore WordPress.Security
				$value = wp_unslash( $_POST[ $key ][ $menu_item_db_id ] ); //phpcs:ignore WordPress.Security
				update_post_meta( $menu_item_db_id, '_menu_item_' . $field, $value );
			}
		}
	}

	public function admin_scripts( $hook ) {
		if ( 'nav-menus.php' === $hook ) {
			$theme   = wp_get_theme( get_template() );
			$version = $theme->get( 'Version' );

			wp_enqueue_media();

			wp_enqueue_style( 'favethemes-admin-menu', get_template_directory_uri() . '/framework/admin/menu/css/style.css', null, $version );
		}
	}

}
new Favethemes_Menu();