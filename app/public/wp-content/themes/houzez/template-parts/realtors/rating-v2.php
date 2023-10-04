<?php 
if(is_author()) {
	global $author_id;
	$rating_id = $author_id;
} else {
	$rating_id = get_the_ID();
}
$total_ratings = get_post_meta($rating_id, 'houzez_total_rating', true); 

if( empty( $total_ratings ) ) {
	$total_ratings = 0;
}
?>

<div class="rating-score-wrap flex-grow-1">
	<span class="star">
		<?php echo houzez_get_stars($total_ratings, false); ?>
	</span><!-- star -->
</div>