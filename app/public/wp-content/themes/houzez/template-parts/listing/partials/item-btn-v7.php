<?php 
global $houzez_local, $post; 
$key = '';
$userID      =   get_current_user_id();
$fav_option = 'houzez_favorites-'.$userID;
$fav_option = get_option( $fav_option );
if( !empty($fav_option) ) {
    $key = array_search($post->ID, $fav_option);
}

$icon = '';
if( $key != false || $key != '' ) {
    $icon = 'text-danger';
}
?>
<div class="item-buttons-wrap">
	<div class="item-buttons-left-wrap">
		<?php get_template_part('template-parts/listing/partials/item-btns-cew'); ?>		
	</div><!-- item-buttons-left-wrap -->
	<?php
	if(houzez_option('disable_favorite', 1) || houzez_option('disable_compare', 1) || houzez_option('disable_preview', 1) ) { ?>
	<div class="item-buttons-right-wrap">
		<?php if(houzez_option('disable_preview', 1)) { ?>
		<span class="hz-show-lightbox-js btn btn-primary-outlined item-preview btn-item" data-listid="<?php echo intval($post->ID)?>" data-toggle="tooltip" data-placement="top" title="<?php echo houzez_option('cl_preview', 'Preview'); ?>">
                <i class="houzez-icon icon-expand-3"></i>   
        </span><!-- item-tool-favorite -->
		<?php } ?>

		<?php if(houzez_option('disable_favorite', 1)) { ?>
	    <span class="add-favorite-js btn btn-primary-outlined item-favorite btn-item" data-toggle="tooltip" data-placement="top" title="<?php echo houzez_option('cl_favorite', 'Favourite'); ?>" data-listid="<?php echo intval($post->ID)?>">
            <i class="houzez-icon icon-love-it <?php echo esc_attr($icon); ?>"></i> 
        </span><!-- item-tool-favorite -->
	    <?php } ?>

	    <?php 
	    if(houzez_option('disable_compare', 1)) { 
	        $property_img_url = get_the_post_thumbnail_url( $post->ID, 'houzez-item-image-1' );
	        if ( empty( $property_img_url ) ) {
	            $property_img_url = houzez_get_image_placeholder_url( 'houzez-item-image-1' );
	        }
	    ?>
	    <span class="btn btn-primary-outlined btn-item item-compare houzez_compare compare-<?php echo intval($post->ID); ?> show-compare-panel" data-toggle="tooltip" data-placement="top" title="<?php echo houzez_option('cl_add_compare', 'Add to Compare'); ?>" data-listing_id="<?php echo intval($post->ID); ?>" data-listing_image="<?php echo esc_attr($property_img_url); ?>">
            <i class="houzez-icon icon-add-circle"></i>
        </span><!-- item-tool-compare -->
	    <?php } ?>

    </div><!-- item-buttons-right-wrap -->
	<?php } ?>
</div><!-- item-buttons-wrap --> 