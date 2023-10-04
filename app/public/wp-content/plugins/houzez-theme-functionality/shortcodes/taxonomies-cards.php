<?php
/*-----------------------------------------------------------------------------------*/
/*	Module 1
/*-----------------------------------------------------------------------------------*/
if( !function_exists('houzez_taxonomies_cards') ) {
	function houzez_taxonomies_cards($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'houzez_card_type' => '',
			'houzez_cards_from' => '',
			'houzez_show_child' => '',
			'houzez_hide_count' => '',
			'orderby' 			=> '',
			'order' 			=> '',
			'houzez_hide_empty' => '',
			'no_of_terms' 		=> '',
			'property_type' => '',
			'property_status' => '',
			'property_area' => '',
			'property_state' => '',
			'property_country' => '',
			'houzez_cards_columns' => '',
			'property_city' => '',
			'property_label' => ''
		), $atts));

		ob_start();
		$module_type = '';

		$slugs = '';

		if( $houzez_cards_from == 'property_city' ) {
			$slugs = $property_city;

		} else if ( $houzez_cards_from == 'property_area' ) {
			$slugs = $property_area;

		} else if ( $houzez_cards_from == 'property_label' ) {
			$slugs = $property_label;

		} else if ( $houzez_cards_from == 'property_country' ) {
			$slugs = $property_country;

		} else if ( $houzez_cards_from == 'property_state' ) {
			$slugs = $property_state;

		} else if ( $houzez_cards_from == 'property_status' ) {
			$slugs = $property_status;

		} else {
			$slugs = $property_type;
		}

		if ($houzez_show_child == 1) {
			$houzez_show_child = '';
		}

		if( $houzez_cards_from == 'property_type' ) {
			$custom_link_for = 'fave_prop_type_custom_link';
		} else {
			$custom_link_for = 'fave_prop_taxonomy_custom_link';
		}

		$tax_name = $houzez_cards_from;
		$taxonomy = get_terms(array(
			'hide_empty' => $houzez_hide_empty,
			'parent' => $houzez_show_child,
			'slug' => houzez_traverse_comma_string($slugs),
			'number' => $no_of_terms,
			'orderby' => $orderby,
			'order' => $order,
			'taxonomy' => $tax_name,
		));
		?>

		<div class="taxonomy-cards-module taxonomy-cards-module-<?php echo esc_attr($houzez_cards_columns);?>">
			<div class="d-flex taxonomy-cards-module-row">
				<?php
				if ( !is_wp_error( $taxonomy ) ) {
					$i = 0;
					$j = 0;

					foreach ($taxonomy as $term) {

					$i++;
					$j++;

					$attach_id = get_term_meta($term->term_id, 'fave_taxonomy_img', true);

					$attachment = wp_get_attachment_image_src( $attach_id, 'houzez-top-v7' );

					if(empty($attachment)) {
						$img_url = 'https://place-hold.it/360x360';
						$img_width = '360';
						$img_height = '360';
					}else{
	                    $img_url = $attachment['0'];
	                    $img_width = $attachment['1'];
	                    $img_height = $attachment['2'];
	                }
					
					$taxonomy_custom_link = get_term_meta($term->term_id, 'fave_prop_taxonomy_custom_link', true);
					if( !empty($taxonomy_custom_link) ) {
						$term_link = $taxonomy_custom_link;
					} else {
						$term_link = get_term_link($term, $tax_name);
					}
					?>

				    
				        <div class="taxonomy-item-card">
							<div class="taxonomy-item-card-image">
								<a href="<?php echo esc_url($term_link);?>"><img class="img-fluid" src="<?php echo esc_url($img_url); ?>" width="<?php echo $img_width; ?>" height="<?php echo $img_height; ?>" alt="<?php esc_attr_e($tax_name);?>"></a>
							</div><!-- taxonomy-item-card-image -->
							<div class="taxonomy-item-card-content">
								<dl class="taxonomy-item-card-content-list">
									<dt><a href="<?php echo esc_url($term_link);?>"><?php echo esc_attr($term->name); ?></a></dt>
									<?php if( $houzez_hide_count != 1 ) { ?>
									<dd>
										<?php echo esc_attr($term->count); ?> 
										<?php
										if ($term->count < 2) {
											echo houzez_option('cl_property', 'Property');
										} else {
											echo houzez_option('cl_properties', 'Properties');
										}
										?>
									<dd>
									<?php } ?>
								</dl>
							</div><!-- taxonomy-item-card-content -->
						</div><!-- taxonomy-item-card -->
				    
				<?php } 
				}?>
			</div><!-- taxonomy-cards-module-row -->
		</div>
		
		<?php
		$result = ob_get_contents();
		ob_end_clean();
		return $result;

	}

	add_shortcode('houzez_taxonomies_cards', 'houzez_taxonomies_cards');
}
?>