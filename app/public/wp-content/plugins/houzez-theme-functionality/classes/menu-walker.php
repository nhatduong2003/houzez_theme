<?php
namespace Houzez\Classes;

use Elementor\Plugin as Elementor;
/**
 * Create HTML list of nav menu items.
 */
class houzez_plugin_nav_walker extends \Walker_Nav_Menu {

	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output )
	{
        if ( $depth == "" ) {
            $depth = 0;
        }
		$id_field = $this->db_fields['id'];
        $id       = $element->$id_field;
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		}

        // Remove children from mega menu items.
        if ( get_post_meta( $id, '_menu_item_html_block', true ) ) {
            $this->unset_children( $element, $children_elements );
        }

		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

    /**
     * Start the element output.
     *
     * @param  string $output Passed by reference. Used to append additional content.
     * @param  object $item   Menu item data object.
     * @param  int $depth     Depth of menu item. May be used for padding.
     * @param  array $args    Additional strings.
     * @return void
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';
        
        $is_top_level   = $depth == 0;
        $behavior       = get_post_meta( $item->ID, '_menu_item_behavior', true );
        $html_block     = get_post_meta( $item->ID, '_menu_item_html_block', true );
        $design         = get_post_meta( $item->ID, '_menu_item_design', true );
        $width          = get_post_meta( $item->ID, '_menu_item_width', true );
        $height         = get_post_meta( $item->ID, '_menu_item_height', true );
        $icon_type      = get_post_meta( $item->ID, '_menu_item_icon_type', true );
        $icon_id        = get_post_meta( $item->ID, '_menu_item_icon_id', true );
        $icon_width     = get_post_meta( $item->ID, '_menu_item_icon_width', true );
        $icon_height    = get_post_meta( $item->ID, '_menu_item_icon_height', true );
        $icon_html      = get_post_meta( $item->ID, '_menu_item_icon_html', true );
        $is_mega_menu   = ! empty( $html_block );


        $dropdown_anchor_calss = '';
        $classes   = empty ( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Set Active Class.
        if ( in_array( 'current-menu-ancestor', $classes, true ) || in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) ) {
            $classes[] = '';
        }

        if( $is_top_level ) {
            $classes[] = 'menu-item-design-' . $design;

            if( $is_mega_menu ) {
                $classes[] = 'menu-item-has-megamenu';
            }
        }

        if ( $is_top_level && ( $is_mega_menu || $args->has_children ) ) {
            $classes[] = 'dropdown';

            if( $is_mega_menu  ) {
                $classes[] = 'yamm-fw';
            }
            /*if ( 'click' === $behavior ) {
                $classes[] = 'nav-dropdown-toggle';
            }*/

            $dropdown_anchor_calss = "dropdown-toggle";
        }

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @since 4.4.0
         *
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param WP_Post  $item  Menu item data object.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

        /**
         * Filters the CSS classes applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';


        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes  = '';

        if($depth > 0 ) {
        	$attributes .=  ' class="dropdown-item '.$dropdown_anchor_calss.'"';
        } else {
	        $attributes .=  ' class="nav-link '.$dropdown_anchor_calss.'"';
	    }

        if( wp_is_mobile() ) {
            $attributes .= $args->has_children ? ' data-toggle="dropdown" ' : '';
        }

        if( ! wp_is_mobile() && $behavior == 'click' ) {
            $attributes .= ( $args->has_children || $is_mega_menu ) ? ' data-toggle="dropdown" ' : '';
        }

        ! empty( $item->attr_title )
            and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
        ! empty( $item->target )
            and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
        ! empty( $item->xfn )
            and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
        ! empty( $item->url )
            and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';

        
        $description = ( ! empty ( $item->description ) and 0 == $depth )
            ? '<small class="nav_desc">' . esc_attr( $item->description ) . '</small>' : '';

        $title = apply_filters( 'the_title', $item->title, $item->ID );

        $item_output = $args->before
            . "<a $attributes>"
            . $args->link_before
            . $title
            . '</a> '
            . $args->link_after
            . $description
            . $args->after;

        if ( $is_top_level && $is_mega_menu ) {
            $dropdown_classes = array( 'dropdown-menu' );
            $dropdown_classes = implode( ' ', $dropdown_classes );

            $item_output .= '<div class="' . esc_attr( $dropdown_classes ) . '">';
            $item_output .= houzez_get_elementor_template( $html_block );
            $item_output .= '</div>';
        }

        $css = "";
        if ( $design == 'custom-size' && ! empty( $width ) ) {
            $css .= '#menu-item-' . $item->ID . ' > .dropdown-menu {';
            $css .= 'width: ' . $width . 'px;';
            if ( ! empty( $height ) ) {
                $css .= 'min-height: ' . $height . 'px;';
            }
            $css .= '}';
        }

        if ( $css != '' ) {
            $item_output .= '<style>';
            $item_output .= $css;
            $item_output .= '</style>';
        }

        // Since $output is called by reference we don't need to return anything.
        $output .= apply_filters(
            'walker_nav_menu_start_el'
        ,   $item_output
        ,   $item
        ,   $depth
        ,   $args
        );
    }

    function start_lvl( &$output, $depth=0, $args = array() ) {


        // depth dependent classes
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        
        if( $display_depth > 1 ) {
            $classes = array(
            'dropdown-menu',
            'submenu'
            );
        } else {
            $classes = array(
            'dropdown-menu'
            );
        }
        $class_names = implode( ' ', $classes );

        // build html
        $output .= "\n" . $indent . '<ul class="' . esc_attr( $class_names ) . '">' . "\n";
    }

    function end_lvl( &$output, $depth=0, $args = array() ) {

        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";

    }
}