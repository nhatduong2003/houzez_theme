<?php
if(houzez_get_map_system() == 'google') {
	wp_enqueue_script('houzez-overview-listing-map', HOUZEZ_JS_DIR_URI. 'single-property-google-overview-map.js', array('jquery'), '1.0.0', true);
} else {
	wp_enqueue_script('houzez-overview-listing-map', HOUZEZ_JS_DIR_URI. 'single-property-osm-overview-map.js', array('jquery'), '1.0.0', true);
}

?>
<div class="property-overview-wrap property-overview-wrap-v2 property-section-wrap" id="property-overview-wrap">
	<div class="block-wrap">
		
		<div class="block-title-wrap d-flex justify-content-between align-items-center">
			<h2><?php echo houzez_option('sps_overview', 'Overview'); ?></h2>
		</div><!-- block-title-wrap -->

		<div class="row">
			<div class="col-md-8 col-sm-12">
				<div class="houzez-layout-row houzez-desktop-layout-3cols houzez-tablet-layout-2cols houzez-mobile-layout-2cols property-overview-data">
					<?php get_template_part('property-details/partials/overview-data'); ?>
				</div><!-- d-flex -->
			</div><!-- col-md-8 col-sm-12 -->
			<div class="col-md-4 col-sm-12">
				<div id="houzez-overview-listing-map" class="block-map-wrap">
				</div><!-- block-map-wrap -->
			</div><!-- col-md-4 col-sm-12 -->
		</div><!-- row -->
	</div><!-- block-wrap -->
</div><!-- property-overview-wrap -->