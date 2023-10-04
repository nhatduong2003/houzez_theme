<?php
global $post;

if ( houzez_site_width() == '1210px' ) {
	$size = 'houzez-item-image-4';
} else {
	$size = 'houzez-gallery';
}

$listing_images = rwmb_meta( 'fave_property_images', 'type=plupload_image&size='.$size, $post->ID );
$i = 0; $j = 0;
$total_images = count($listing_images);
$property_gallery_popup_type = houzez_get_popup_gallery_type();

$css_class = 'houzez-trigger-popup-slider-js';
$dataModal = 'data-toggle="modal" data-target="#property-lightbox"';
if( $property_gallery_popup_type == 'photoswipe' ) {
	$css_class = 'houzez-photoswipe-trigger';
	$dataModal = '';
}
$layout = houzez_option('property_blocks');
$layout = $layout['enabled'];
?>
<div class="property-top-wrap">
    <div class="property-banner">
		<div class="visible-on-mobile">
			<div class="tab-content" id="pills-tabContent">
				<?php get_template_part('property-details/partials/media-tabs'); ?>
			</div><!-- tab-content -->
		</div><!-- visible-on-mobile -->

		<div class="container hidden-on-mobile">
			<div class="row">
				<?php
				if(!empty($listing_images)) {
					foreach( $listing_images as $image ) { $i++; 
					
						if($i == 1) {
						?>
						<div class="col-md-8">
							<a href="#" data-slider-no="<?php echo esc_attr($i); ?>" data-image="<?php echo esc_attr($j); ?>" class="<?php echo esc_attr($css_class); ?> img-wrap-1" <?php echo $dataModal; ?>>
								<img class="img-fluid" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
							</a>
						</div><!-- col-md-8 -->
						<?php } elseif($i == 2 || $i == 3) { ?>

						<?php if($i == 2) { ?>
						<div class="col-md-4">
						<?php } ?>
							<a href="#" data-slider-no="<?php echo esc_attr($i); ?>" data-image="<?php echo esc_attr($j); ?>" <?php echo $dataModal; ?> class="<?php echo esc_attr($css_class); ?> swipebox img-wrap-<?php echo esc_attr($i); ?>">
								<?php if($total_images > 3 && $i == 3) { ?>
								<div class="img-wrap-3-text"><?php echo $total_images-3; ?> <?php echo esc_html__('More', 'houzez'); ?></div>
								<?php } ?>

								<img class="img-fluid" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
							</a>
						<?php if( ($i == 3 && $total_images == 3) || ( $i == 2 && $total_images == 2 ) || ( $i == 1 && $total_images == 1 ) || $i == 3 ) { ?>
						</div><!-- col-md-4 -->
						<?php } ?>
						<?php } else { ?>
							<a href="#" class="img-wrap-1 gallery-hidden">
								<img class="img-fluid" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
							</a>
						<?php
						}
						$j++;
					}
				}?>
				
				<?php 
				if( ! array_key_exists( 'overview-v2', $layout ) ) { ?>
				<div class="col-md-12">
					<div class="block-wrap">
						<div class="d-flex property-overview-data">
							<?php get_template_part('property-details/partials/overview-data'); ?>
						</div><!-- d-flex -->
					</div><!-- block-wrap -->
				</div><!-- col-md-12 -->
				<?php } ?>
			</div><!-- row -->
		</div><!-- hidden-on-mobile -->
	</div><!-- property-banner -->

	<?php 
	if( $property_gallery_popup_type == 'photoswipe' ) {
	$items_array = houzez_property_images_for_photoswipe();
	get_template_part( 'property-details/photoswipe'); ?>

	<script>
	initPhotoswipeDomForJson(<?php echo json_encode($items_array); ?>);
	function initPhotoswipeDomForJson(imageData) {

	    var pswpElement = document.querySelectorAll('.pswp')[0];

	    var items = [],
	        item;

	    jQuery.each(imageData, function(i, obj) {
	        item = {
	            src: obj.src,
	            w: obj.w,
	            h: obj.h
	        };

	        items.push(item);
	    });


	    var options = {
	      index: 0
	    };

	    var x = document.querySelectorAll(".houzez-photoswipe-trigger");

	    for (let i = 0; i < x.length; i++) {
	      x[i].addEventListener("click", function() {
	        openGallery(x[i].dataset.image);
	      });
	    }

	    function openGallery(j) {
	      options.index = parseInt(j);
	      options.history = false;
	      gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
	      gallery.init();
	    }
	}
	</script>
	<?php } ?>

</div><!-- property-top-wrap -->
