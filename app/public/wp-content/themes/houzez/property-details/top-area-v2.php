<div class="property-top-wrap">
    <div class="property-banner">
		<div class="container hidden-on-mobile">
			<?php get_template_part('property-details/partials/banner-nav'); ?>
		</div><!-- container -->
		<div class="tab-content" id="pills-tabContent">
			<?php get_template_part('property-details/partials/media-tabs'); ?>
		</div><!-- tab-content -->
	</div><!-- property-banner -->

	<?php 
	if( houzez_get_popup_gallery_type() == 'photoswipe' ) {
	$items_array = houzez_property_images_for_photoswipe($with_featured = true);
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

	    jQuery('.property-banner-trigger').on("click", function() {
	    	openGallery();
	    });

	    function openGallery() {
	      gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
	      gallery.init();
	    }
	}
	</script>
	<?php } ?>
</div><!-- property-top-wrap -->