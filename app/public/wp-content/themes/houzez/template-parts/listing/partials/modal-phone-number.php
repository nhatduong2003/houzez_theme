<?php 
global $post, $listing_agent_info; 
$prop_id = houzez_get_listing_data('property_id');

$agent_mobile = $listing_agent_info['agent_mobile'];
$agent_mobile_call = $listing_agent_info['agent_mobile_call'];

if( ! empty( $agent_mobile ) ) {
?>
<div class="modal fade modal-phone-number" id="call-action-<?php echo esc_attr($post->ID); ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php esc_html_e('Contact us', 'houzez'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_html_e('Close', 'houzez'); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div><!-- modal-header -->
            <div class="modal-body">
                <p class="modal-body-phone-number-text">
                    <?php esc_html_e('Please quote property reference', 'houzez'); ?><br>

                    <strong><?php echo get_bloginfo('name'); ?> - <?php echo houzez_propperty_id_prefix($prop_id); ?></strong>
                </p>
                <p class="modal-body-phone-number-number">
                     <a class="btn btn-primary-outlined" href="tel:<?php echo esc_attr($agent_mobile_call); ?>"><i class="houzez-icon icon-phone-actions-ring"></i> <?php echo esc_attr($agent_mobile); ?></a>
                </p>
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- login-register-form -->
<?php } ?>