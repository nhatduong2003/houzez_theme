<?php 
global $post, $listing_agent_info, $current_user; 
$agent_email = $listing_agent_info['agent_email'];
$agent_email = is_email( $agent_email );

$terms_page_id = houzez_option('terms_condition');
$terms_page_id = apply_filters( 'wpml_object_id', $terms_page_id, 'page', true );

$hide_form_fields = houzez_option('hide_prop_contact_form_fields');
$agent_display = houzez_get_listing_data('agent_display_option');
$property_id = houzez_get_listing_data('property_id');

$user_name = $user_email = '';
if(!houzez_is_admin()) {
    $user_name =  $current_user->display_name;
    $user_email =  $current_user->user_email;
}

if( ! empty( $agent_email ) ) {
?>
<div class="modal fade mobile-property-form" id="email-popup-<?php echo esc_attr($post->ID); ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="property-form-wrap">

                <?php 
                if(houzez_form_type()) {

                    echo $listing_agent_info['agent_data'];
                    
                    if(!empty(houzez_option('contact_form_agent_above_image'))) {
                        echo do_shortcode(houzez_option('contact_form_agent_above_image'));
                    }

                } else { ?>
                    <div class="property-form clearfix">
                        <form method="post" action="#">
                            
                            <?php echo $listing_agent_info['agent_data']; ?>

                            <?php if( $hide_form_fields['name'] != 1 ) { ?>
                            <div class="form-group">
                                <input class="form-control" name="name" value="<?php echo esc_attr($user_name); ?>" type="text" placeholder="<?php echo houzez_option('spl_con_name', 'Name'); ?>">
                            </div><!-- form-group -->
                            <?php } ?>

                            <?php if( $hide_form_fields['phone'] != 1 ) { ?>    
                            <div class="form-group">
                                <input class="form-control" name="mobile" value="" type="text" placeholder="<?php echo houzez_option('spl_con_phone', 'Phone'); ?>">
                            </div><!-- form-group -->
                            <?php } ?>

                            <div class="form-group">
                                <input class="form-control" name="email" value="<?php echo esc_attr($user_email); ?>" type="email" placeholder="<?php echo houzez_option('spl_con_email', 'Email'); ?>">
                            </div><!-- form-group -->

                            <?php if( $hide_form_fields['message'] != 1 ) { ?>  
                            <div class="form-group form-group-textarea">
                                <textarea class="form-control hz-form-message" name="message" rows="4" placeholder="<?php echo houzez_option('spl_con_message', 'Message'); ?>"><?php echo houzez_option('spl_con_interested', "Hello, I am interested in"); ?> [<?php echo get_the_title(); ?>]</textarea>
                            </div><!-- form-group -->   
                            <?php } ?>

                            <?php if( $hide_form_fields['usertype'] != 1 ) { ?> 
                            <div class="form-group">
                                <select name="user_type" class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('spl_con_select', 'Select'); ?>">

                                    <?php if( houzez_option('spl_con_buyer') != "" ) { ?>
                                    <option value="buyer"><?php echo houzez_option('spl_con_buyer', "I'm a buyer"); ?></option>
                                    <?php } ?>

                                    <?php if( houzez_option('spl_con_tennant') != "" ) { ?>
                                    <option value="tennant"><?php echo houzez_option('spl_con_tennant', "I'm a tennant"); ?></option>
                                    <?php } ?>

                                    <?php if( houzez_option('spl_con_agent') != "" ) { ?>
                                    <option value="agent"><?php echo houzez_option('spl_con_agent', "I'm an agent"); ?></option>
                                    <?php } ?>

                                    <?php if( houzez_option('spl_con_other') != "" ) { ?>
                                    <option value="other"><?php echo houzez_option('spl_con_other', 'Other'); ?></option>
                                    <?php } ?>
                                </select><!-- selectpicker -->
                            </div><!-- form-group -->
                            <?php } ?>

                            <?php if( houzez_option('gdpr_and_terms_checkbox', 1) ) { ?>
                            <div class="form-group">
                                <label class="control control--checkbox m-0 hz-terms-of-use">
                                    <input type="checkbox" name="privacy_policy"><?php echo houzez_option('spl_sub_agree', 'By submitting this form I agree to'); ?> <a target="_blank" href="<?php echo esc_url(get_permalink($terms_page_id)); ?>"><?php echo houzez_option('spl_term', 'Terms of Use'); ?></a>
                                    <span class="control__indicator"></span>
                                </label>
                            </div><!-- form-group -->   
                            <?php } ?>      
                        
                            <?php if ( $listing_agent_info['is_single_agent'] == true ) : ?>
                                <input type="hidden" name="target_email" value="<?php echo antispambot($agent_email); ?>">
                            <?php endif; ?>
                            <input type="hidden" name="property_agent_contact_security" value="<?php echo wp_create_nonce('property_agent_contact_nonce'); ?>"/>
                            <input type="hidden" name="property_permalink" value="<?php echo esc_url(get_permalink($post->ID)); ?>"/>
                            <input type="hidden" name="property_title" value="<?php echo esc_attr(get_the_title($post->ID)); ?>"/>
                            <input type="hidden" name="property_id" value="<?php echo esc_attr($property_id); ?>"/>
                            <input type="hidden" name="action" value="houzez_property_agent_contact">
                            <input type="hidden" name="listing_id" value="<?php echo intval($post->ID)?>">
                            <input type="hidden" name="is_listing_form" value="yes">
                            <input type="hidden" name="agent_id" value="<?php echo intval($listing_agent_info['agent_id'])?>">
                            <input type="hidden" name="agent_type" value="<?php echo esc_attr($listing_agent_info['agent_type'])?>">

                            <?php get_template_part('template-parts/google', 'reCaptcha'); ?>
                            <div class="form_messages"></div>
                            <button type="button" class="houzez_agent_property_form btn btn-secondary btn-full-width">
                                <?php get_template_part('template-parts/loader'); ?>
                                <?php echo houzez_option('spl_btn_send', 'Send Email'); ?>
                                
                            </button>
                            
                        </form>
                    </div><!-- property-form -->
                    
                <?php } ?>
            </div><!-- property-form-wrap -->

    
            </div>
        </div>
    </div>
</div>
<?php } ?>