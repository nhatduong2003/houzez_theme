<?php 
global $post, $map_street_view; 
$gallery_active = $map_active = $street_active = $virtual_active = $video_active = "";
$active_tab = houzez_option('prop_default_active_tab', 'image_gallery');
if( $active_tab == 'map_view' ) {
	$map_active = 'active';

} elseif( $active_tab == 'street_view' ) {
	$street_active = 'active'; 
} elseif( $active_tab == 'virtual_tour' ) {
	$virtual_active = 'active'; 
} elseif( $active_tab == 'video' ) {
	$video_active = 'active'; 
} else {
	$gallery_active = 'active';
}
$media_tabs = houzez_get_media_tabs();
$tabs_count = count($media_tabs);
$tabs_count = $tabs_count + 2; //add 2 for mobile
?>
<ul class="nav nav-pills houzez-media-tabs-<?php esc_attr_e($tabs_count);?>" id="pills-tab" role="tablist">
	
	<?php 
	if ($media_tabs): foreach ($media_tabs as $key=>$value) {
		switch($key) {

        case 'gallery': ?>
            <li class="nav-item">
				<a class="nav-link <?php echo esc_attr($gallery_active); ?>" id="pills-gallery-tab" data-toggle="pill" href="#pills-gallery" role="tab" aria-controls="pills-gallery" aria-selected="true">
					<i class="houzez-icon icon-picture-sun"></i>
				</a>
			</li>
			<?php
           	break;

        case 'map':
        	?>
        	<li class="nav-item">
				<a class="nav-link <?php echo esc_attr($map_active); ?>" id="pills-map-tab" data-toggle="pill" href="#pills-map" role="tab" aria-controls="pills-map" aria-selected="true">
					<i class="houzez-icon icon-maps"></i>
				</a>
			</li>
        	<?php
        	break;

        case 'street_view':
        	?>
        	<li class="nav-item">
				<a class="nav-link <?php echo esc_attr($street_active); ?>" id="pills-street-view-tab" data-toggle="pill" href="#pills-street-view" role="tab" aria-controls="pills-street-view" aria-selected="false">
					<i class="houzez-icon icon-location-user"></i>
				</a>
			</li>
        	<?php
        	break;

        case '360_virtual_tour': 
        	?>
			<li class="nav-item">
				<a class="nav-link <?php echo esc_attr($virtual_active); ?>" id="pills-360tour-tab" data-toggle="pill" href="#pills-360tour" role="tab" aria-controls="pills-360tour" aria-selected="true">
					<i class="houzez-icon icon-surveillance-360-camera"></i>
				</a>
			</li>
			<?php
        	break;

        case 'video': 
        	?>
			<li class="nav-item">
				<a class="nav-link <?php echo esc_attr($video_active); ?>" id="pills-video-tab" data-toggle="pill" href="#pills-video" role="tab" aria-controls="pills-video" aria-selected="true">
					<i class="houzez-icon icon-video-player-movie-1"></i>
				</a>
			</li>
			<?php
        	break;
    }
}
endif;
?>
</ul><!-- nav -->	