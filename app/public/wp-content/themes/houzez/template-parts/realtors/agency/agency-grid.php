<?php
global $houzez_local;
$service_area = get_post_meta( get_the_ID(), 'fave_agent_service_area', true );
$properties = Houzez_Query::agency_properties_count( get_the_ID() );
?>
<div class="agency-grid-wrap">	
	<div class="agency-grid-image-wrap">
		<a class="agency-grid-image" href="<?php the_permalink(); ?>">
			<?php get_template_part('template-parts/realtors/agency/image'); ?>
		</a>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php 
        if( houzez_option( 'agency_review', 0 ) != 0 ) {
            get_template_part('template-parts/realtors/rating','v2'); 
        }?>
	</div><!-- agency-list-image -->
	<div class="agency-grid-content-wrap">
		<ul class="agency-list-contact list-unstyled">
			<?php if( ! empty($properties) ) { ?>
			<li><?php echo $houzez_local['properties']?>: <strong><?php echo esc_attr($properties); ?></strong></li>
			<?php } ?>
			<?php
			if( !empty( $service_area ) ) { ?>
				<li><?php echo $houzez_local['service_area']; ?>:
					<strong>
					<?php echo esc_attr( $service_area ); ?></strong> 
				</li>
			<?php } ?>
		</ul><!-- agency-list-contact -->
		<a class="btn btn-primary-outlined btn-full-width" href="<?php the_permalink(); ?>">
			<?php echo $houzez_local['view_profile']; ?></a>
	</div><!-- agency-list-content -->
</div><!-- agency-list-wrap -->