<?php 
global $post, $sticky_hidden, $sticky_data, $hidden_data;
$sticky_hidden = $sticky_data = '';
$hidden_data = '0';
if( !is_404() && !is_search() ) {
    $adv_search_enable = get_post_meta($post->ID, 'fave_adv_search_enable', true);
    $adv_search = get_post_meta($post->ID, 'fave_adv_search', true);
}
$search_sticky = houzez_option('mobile-search-sticky');
$sticky_data = $search_sticky;
?>
<section class="advanced-search advanced-search-nav mobile-search-nav" data-sticky='<?php echo esc_attr( $sticky_data ); ?>'>
	<div class="container">
		<div class="advanced-search-v1">
			<div class="d-flex">
				<div class="flex-search flex-grow-1">
					<div class="form-group">
						<div class="search-icon">
							<input type="text" class="form-control" placeholder="<?php echo houzez_option('srh_mobile_title', 'Search'); ?>" onfocus="blur();">
						</div><!-- search-icon -->
					</div><!-- form-group -->
				</div><!-- flex-search -->
			</div><!-- d-flex -->
		</div><!-- advanced-search-v1 -->
	</div><!-- container -->
</section><!-- advanced-search -->