<?php
global $post, $top_area, $map_street_view;
$featured_image = houzez_get_image_url('full');
$featured_image_url = $featured_image[0];
$property_gallery_popup_type = houzez_get_popup_gallery_type(); 
if( ! has_post_thumbnail( $post->ID ) || get_the_post_thumbnail($post->ID) == "" ) {
	$featured_image_url = houzez_get_image_placeholder_url('full');
}

$gallery_active = $map_active = $street_active = $virtual_active = $video_active = "";
$active_tab = houzez_option('prop_default_active_tab', 'image_gallery');
if( $active_tab == 'map_view' ) {
	$map_active = 'show active';

} elseif( $active_tab == 'street_view' ) {
	$street_active = 'show active'; 
} elseif( $active_tab == 'virtual_tour' ) {
	$virtual_active = 'show active'; 
} elseif( $active_tab == 'video' ) {
	$video_active = 'show active'; 
} else {
	$gallery_active = 'show active';
}

$media_tabs = houzez_get_media_tabs();

if ($media_tabs): foreach ($media_tabs as $key=>$value) {
	switch($key) {

    case 'gallery': 
        if($top_area == 'v2') { ?>
			<div class="tab-pane <?php echo esc_attr($gallery_active); ?>" id="pills-gallery" role="tabpanel" aria-labelledby="pills-gallery-tab" style="background-image: url(<?php echo esc_url($featured_image_url); ?>);">
				<?php get_template_part('property-details/partials/image-count'); ?>	
				<div class="d-flex page-title-wrap page-label-wrap">
					<div class="container">
					<?php get_template_part('property-details/partials/item-labels'); ?>
					</div>
				</div>
				<?php get_template_part('property-details/property-title'); ?> 
				<?php if( $property_gallery_popup_type == "photoswipe" ) { ?>
					<a class="houzez-photoswipe-trigger property-banner-trigger" href="#"></a>
				<?php } else { ?>
					<a class="property-banner-trigger" data-toggle="modal" data-target="#property-lightbox" href="#"></a>
				<?php } ?>
			</div>

		<?php } elseif($top_area == 'v3' || $top_area == 'v4') { ?>

			<div class="tab-pane <?php echo esc_attr($gallery_active); ?>" id="pills-gallery" role="tabpanel" aria-labelledby="pills-gallery-tab">
				<?php get_template_part('property-details/partials/gallery'); ?>
			</div>

		<?php } elseif($top_area == 'v5') { ?>

			<div class="tab-pane <?php echo esc_attr($gallery_active); ?>" id="pills-gallery" role="tabpanel" aria-labelledby="pills-gallery-tab">
				<?php get_template_part('property-details/partials/image-count'); ?>
				<?php get_template_part('property-details/partials/gallery-variable-width'); ?>
			</div>

		<?php } elseif( $top_area == 'v1' || $top_area == 'v6' ) { ?>

			<div class="tab-pane <?php echo esc_attr($gallery_active); ?>" id="pills-gallery" role="tabpanel" aria-labelledby="pills-gallery-tab" style="background-image: url(<?php echo esc_url($featured_image_url); ?>);">
				<?php get_template_part('property-details/partials/image-count'); ?>
				<?php 
				if(houzez_option('agent_form_above_image')) {
					get_template_part('property-details/agent-form'); 
				}?>

				<?php if( $property_gallery_popup_type == "photoswipe" ) { ?>
					<a class="houzez-photoswipe-trigger property-banner-trigger" href="#"></a>
				<?php } else { ?>
					<a class="property-banner-trigger" data-toggle="modal" data-target="#property-lightbox" href="#"></a>
				<?php } ?>
			</div>

		<?php }
       	break;

    case 'map':
    	?>
		<div class="tab-pane <?php echo esc_attr($map_active); ?>" id="pills-map" role="tabpanel" aria-labelledby="pills-map-tab">
			<?php get_template_part('property-details/partials/map'); ?>
		</div>
		<?php
    	break;

    case 'street_view':
    	?>
		<div class="tab-pane <?php echo esc_attr($street_active); ?>" id="pills-street-view" role="tabpanel" aria-labelledby="pills-street-view-tab">
		</div>
		<?php
    	break;

    case '360_virtual_tour': 
    	?>
		<div class="tab-pane houzez-360-virtual-tour <?php echo esc_attr($virtual_active); ?>" id="pills-360tour" role="tabpanel" aria-labelledby="pills-360tour-tab">
			<?php echo houzez_get_listing_data('virtual_tour'); ?>
		</div>
		<?php
    	break;

    case 'video': 
    	$prop_video_url = houzez_get_listing_data('video_url');
    	?>
		<div class="tab-pane houzez-top-area-video <?php echo esc_attr($video_active); ?>" id="pills-video" role="tabpanel" aria-labelledby="pills-video-tab">
			<?php $embed_code = wp_oembed_get($prop_video_url); echo $embed_code; ?>
		</div>
		<?php
    	break;
    }
}
endif;
?>






