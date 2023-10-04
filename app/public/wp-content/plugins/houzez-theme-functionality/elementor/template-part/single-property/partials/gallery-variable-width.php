<?php
global $post;
$size = 'houzez-variable-gallery';
$properties_images = rwmb_meta( 'fave_property_images', 'type=plupload_image&size='.$size, $post->ID );
$gallery_caption = houzez_option('gallery_caption', 0); 
$property_gallery_popup_type = houzez_get_popup_gallery_type(); 
$gallery_token = wp_generate_password(5, false, false);

if( !empty($properties_images) && count($properties_images)) {
?>
<div class="top-gallery-section top-gallery-variable-width-section">
	
	<div class="listing-slider-variable-width houzez-all-slider-wrap">
		<?php
		$i = 0;
        foreach( $properties_images as $prop_image_id => $prop_image_meta ) { $i++;
  			
  			if( $property_gallery_popup_type == 'photoswipe' ) {

  				echo '<div>
					<a href="'.esc_url( $prop_image_meta['full_url'] ).'" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="all-'.esc_attr($gallery_token).'">
						<img class="img-responsive" data-lazy="'.esc_attr( $prop_image_meta['url'] ).'" src="'.esc_attr( $prop_image_meta['url'] ).'" alt="'.esc_attr($prop_image_meta['alt']).'" title="'.esc_attr($prop_image_meta['title']).'">
					</a>';

					if( !empty($prop_image_meta['caption']) && $gallery_caption != 0 ) {
		               echo '<span class="hz-image-caption">'.esc_attr($prop_image_meta['caption']).'</span>';
		            }

				echo '</div>';

  			} else {
				echo '<div>
					<a rel="gallery-1" href="#" data-slider-no="'.esc_attr($i).'" class="houzez-trigger-popup-slider-js swipebox" data-toggle="modal" data-target="#property-lightbox">
						<img class="img-responsive" data-lazy="'.esc_attr( $prop_image_meta['url'] ).'" src="'.esc_attr( $prop_image_meta['url'] ).'" alt="'.esc_attr($prop_image_meta['alt']).'" title="'.esc_attr($prop_image_meta['title']).'">
					</a>';

					if( !empty($prop_image_meta['caption']) && $gallery_caption != 0 ) {
		               echo '<span class="hz-image-caption">'.esc_attr($prop_image_meta['caption']).'</span>';
		            }

				echo '</div>';
				if($i == 5) {
					$i = 0;
				}
			}
        }
        ?>
	</div>
	
</div><!-- top-gallery-section -->
<?php } ?>