<?php 
global $post, $listing_agent_info; 
$agent_mobile = $listing_agent_info['agent_mobile'];
$agent_whatsapp = $listing_agent_info['agent_whatsapp'];
$agent_email = $listing_agent_info['agent_email'];
$agent_display = houzez_get_listing_data('agent_display_option');
?>

<?php if( $agent_mobile != '' ) { ?>
<button type="button" class="btn hz-call-popup-js btn-primary-outlined btn-item" data-model-id="call-action-<?php echo esc_attr($post->ID); ?>">
	<i class="houzez-icon icon-phone-actions-ring"></i> <?php esc_html_e('Call', 'houzez'); ?>
</button>
<?php } ?>

<?php if( $agent_email != '' ) { ?>
<button type="button" class="btn hz-email-popup-js btn-primary-outlined btn-item" data-model-id="email-popup-<?php echo esc_attr($post->ID); ?>">
	<i class="houzez-icon icon-envelope"></i> <?php esc_html_e('Email', 'houzez'); ?>
</button>
<?php } ?>	

<?php if( $agent_whatsapp != '' ) { 
	$agent_whatsapp_call = $listing_agent_info['agent_whatsapp_call'];
?>
<a class="btn btn-primary-outlined btn-item" target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $agent_whatsapp_call ); ?>&text=<?php echo houzez_option('spl_con_interested', "Hello, I am interested in").' ['.get_the_title().'] '.get_permalink(); ?> ">
	<i class="houzez-icon icon-messaging-whatsapp"></i> <span><?php esc_html_e('WhatsApp', 'houzez'); ?></span>
</a><!-- btn-item -->
<?php } ?>	