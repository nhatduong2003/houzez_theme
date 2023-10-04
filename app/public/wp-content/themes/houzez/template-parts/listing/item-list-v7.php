<?php 
global $post, $ele_thumbnail_size, $image_size, $listing_agent_info; 
$listing_agent_info = houzez20_property_contact_form();

if( houzez_is_fullwidth_2cols_custom_width() ) {
	$image_size = 'houzez-item-image-4';
} else {
	$image_size = 'houzez-item-image-1';
}
?>
<div class="item-listing-wrap hz-item-gallery-js card" data-hz-id="hz-<?php esc_attr_e($post->ID); ?>" <?php houzez_property_gallery($image_size); ?>>
	<div class="item-wrap item-wrap-v8 item-wrap-no-frame h-100">
		<div class="d-flex align-items-center h-100">
			<div class="item-header">
				<?php get_template_part('template-parts/listing/partials/item-featured-label'); ?>
				<?php get_template_part('template-parts/listing/partials/item-labels'); ?>
				<?php get_template_part('template-parts/listing/partials/item-tools'); ?>
				<?php get_template_part('template-parts/listing/partials/item-image'); ?>
				<div class="preview_loader"></div>
			</div><!-- item-header -->	
			<div class="item-body flex-grow-1">
				<ul class="item-amenities item-amenities-with-icons">
					<?php get_template_part('template-parts/listing/partials/type'); ?>
				</ul>
				<?php get_template_part('template-parts/listing/partials/item-price'); ?>
				<?php get_template_part('template-parts/listing/partials/item-title'); ?>
				<?php get_template_part('template-parts/listing/partials/item-address'); ?>
				<?php get_template_part('template-parts/listing/partials/item-features-v7'); ?>
			</div><!-- item-body -->
		</div><!-- d-flex -->
		<div class="item-footer">
			<div class="item-footer-left-wrap">
				<?php get_template_part('template-parts/listing/partials/item-date'); ?>
				<?php get_template_part('template-parts/listing/partials/item-author'); ?>
			</div><!-- item-footer-left-wrap -->
			<div class="item-footer-right-wrap">
				<?php get_template_part('template-parts/listing/partials/item-btn-v7'); ?>
			</div><!-- item-footer-right-wrap -->
		</div>
	</div><!-- item-wrap -->
	<?php get_template_part('template-parts/listing/partials/modal-phone-number'); ?>
	<?php get_template_part('template-parts/listing/partials/modal-agent-contact-form'); ?>
</div><!-- item-listing-wrap -->