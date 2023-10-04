<?php
global $post, $current_user;
$return_array = houzez20_property_contact_form();
if(empty($return_array)) {
	return;
}
$terms_page_id = houzez_option('terms_condition');
$terms_page_id = apply_filters( 'wpml_object_id', $terms_page_id, 'page', true );
$hide_form_fields = houzez_option('hide_prop_contact_form_fields');
$agent_display = houzez_get_listing_data('agent_display_option');
$property_id = houzez_get_listing_data('property_id');
$schedule_time_slots = houzez_option('schedule_time_slots');

$agent_number = $return_array['agent_mobile'];
$agent_whatsapp_call = $return_array['agent_whatsapp_call'];
$agent_mobile_call = $return_array['agent_mobile_call'];
if( empty($agent_number) ) {
	$agent_number = $return_array['agent_phone'];
	$agent_mobile_call = $return_array['agent_phone_call'];
}

$user_name = $user_email = '';
if(!houzez_is_admin()) {
	$user_name =  $current_user->display_name;
	$user_email =  $current_user->user_email;
}

$send_btn_class = 'btn-half-width';
if($return_array['is_single_agent'] == false || empty($agent_number) || wp_is_mobile() ) {
	$send_btn_class = 'btn-full-width';
}

$action_class = "houzez-send-message";
$login_class = '';
$dataModel = '';
if( !is_user_logged_in() ) {
	$action_class = '';
	$login_class = 'msg-login-required';
	$dataModel = 'data-toggle="modal" data-target="#login-register-form"';
}

$agent_email = is_email( $return_array['agent_email'] );

$agent_mobile_num = houzez_option('agent_mobile_num', 1 ); 
$agent_whatsapp_num = houzez_option('agent_whatsapp_num', 1);

$whatsappBtnClass = "btn-full-width mt-10";
$messageBtnClass = "btn-full-width mt-10";

if( $agent_mobile_num != 1 && !wp_is_mobile() ) {
	$whatsappBtnClass = "btn-half-width";
}
if( $agent_mobile_num != 1 && $agent_whatsapp_num != 1 && !wp_is_mobile() ) {
	$messageBtnClass = "btn-half-width";
}

if ($agent_email && $agent_display != 'none') {


if(houzez_form_type()) {

	echo '<div class="property-form-wrap">';
		echo $return_array['agent_data'];
		
		if(!empty(houzez_option('contact_form_agent_above_image'))) {
			echo do_shortcode(houzez_option('contact_form_agent_above_image'));
		}
	echo '</div>';

} else { ?>

<div class="property-form-tabs-wrap">
	<div class="property-form-tabs">
        <ul class="nav nav-tabs">
        	<li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab_tour" role="tab">
                    <span class="tab-title"><?php esc_html_e('Schedule a tour', 'houzez'); ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_agent_form" role="tab">
                    <span class="tab-title"><?php esc_html_e('Request Info', 'houzez'); ?></span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="property-form-tabs-tab-pane tab-pane fade show active" id="tab_tour" role="tabpanel">
                
                <form method="post" action="#">

				    <input type="hidden" name="schedule_contact_form_ajax"
				       value="<?php echo wp_create_nonce('schedule-contact-form-nonce'); ?>"/>
				    <input type="hidden" name="property_permalink"
				           value="<?php echo esc_url(get_permalink($post->ID)); ?>"/>
				    <input type="hidden" name="property_title"
				           value="<?php echo esc_attr(get_the_title($post->ID)); ?>"/>
				    <input type="hidden" name="action" value="houzez_schedule_send_message">

				    <input type="hidden" name="listing_id" value="<?php echo intval($post->ID)?>">
				    <input type="hidden" name="is_listing_form" value="yes">
				    <input type="hidden" name="is_schedule_form" value="yes">
				    <input type="hidden" name="agent_id" value="<?php echo intval($return_array['agent_id'])?>">
				    <input type="hidden" name="agent_type" value="<?php echo esc_attr($return_array['agent_type'])?>">

				    <input type="hidden" name="target_email" value="<?php echo antispambot($agent_email); ?>">
	                <div class="property-schedule-tour-form-wrap">
		               

		                <div class="property-schedule-tour-day-form">
		                    <div class="tour-day-form-slide-arrow next">
		                        <i class="houzez-icon icon-arrow-right-1"></i>
		                    </div>
		                    <div class="tour-day-form-slide-arrow prev">
		                        <i class="houzez-icon icon-arrow-left-1"></i>
		                    </div>
		                    <div class="property-schedule-tour-day-form-slide-wrap">
		                        <div class="property-schedule-tour-day-form-slide start">
		                            
		                            <?php
		                            $m = date("m");
		                            $de = date("d");
		                            $y = date("Y");

		                            for( $i = 0; $i <= 7; $i++ ) { 

		                                $day = date_i18n('D',mktime(0,0,0,$m,($de+$i),$y)); 
		                                $day_number = date_i18n('d',mktime(0,0,0,$m,($de+$i),$y));
		                                $month = date_i18n('M',mktime(0,0,0,$m,($de+$i),$y));
		                            ?>

		                                <div class="form-group">
		                                    <label class="control control--radio">
		                                    <input name="schedule_date" type="radio" value="<?php echo $day.' '.$day_number.' '.$month; ?>">
		                                    <span class="control__indicator">
		                                    <?php echo $day ?><br>
		                                    <span class="control__indicator_day"><?php echo $day_number ?></span><br>
		                                    <?php echo $month ?>
		                                    </span>
		                                    </label>
		                                </div>
		                    
		                            <?php
		                            }
		                            ?>
		                
		                        </div>
		                    </div>
		                </div>

		                <div class="property-schedule-tour-form-title"><?php echo houzez_option('spl_con_tour_type', 'Tour Type'); ?></div>
		                
		                <div class="property-schedule-tour-type-form d-flex justify-content-between">
		                    <div class="form-group">
		                        <label class="control control--radio">
		                        <input name="schedule_tour_type" type="radio" checked value="<?php echo houzez_option('spl_con_in_person', 'In Person'); ?>">
		                        <span class="control__indicator"><?php echo houzez_option('spl_con_in_person', 'In Person'); ?></span>
		                        </label>
		                    </div>
		                    <!-- form-group -->
		                    <div class="form-group">
		                        <label class="control control--radio">
		                        <input name="schedule_tour_type" type="radio" value="<?php echo houzez_option('spl_con_video_chat', 'Video Chat'); ?>">
		                        <span class="control__indicator"><?php echo houzez_option('spl_con_video_chat', 'Video Chat'); ?></span>
		                        </label>
		                    </div>
		                    <!-- form-group -->
		                </div>
		                <div class="form-group">
		                    <select name="schedule_time" class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('spl_con_time', 'Choose a time'); ?>" data-live-search="false">
		                        <?php 
		                        $time_slots = explode(',', $schedule_time_slots); 
		                        foreach ($time_slots as $time) {
		                            echo '<option value="'.trim($time).'">'.esc_attr($time).'</option>';
		                        }
		                        ?> 
		                    </select>
		                    <!-- selectpicker -->
		                </div>
		                <div class="form-group">
		                    <input class="form-control" name="name" placeholder="<?php echo houzez_option('spl_con_name', 'Name'); ?>" type="text">
		                </div>

		                <div class="form-group">
		                    <input class="form-control" name="phone" placeholder="<?php echo houzez_option('spl_con_phone', 'Phone'); ?>" type="text">
		                </div>

		                <div class="form-group">
		                    <input class="form-control" name="email" placeholder="<?php echo houzez_option('spl_con_email', 'Email'); ?>" type="email">
		                </div>

		                <div class="form-group">
		                    <textarea class="form-control" name="message" rows="3" placeholder="<?php echo houzez_option('spl_con_message_plac', 'Message'); ?>"></textarea>
		                </div>
		                
		                <?php if( houzez_option('gdpr_and_terms_checkbox', 1) ) { ?>
		                <div class="form-group form-group-terms">
		                    <label class="control control--checkbox">
		                        <input type="checkbox" name="privacy_policy"><?php echo houzez_option('spl_sub_agree', 'By submitting this form I agree to'); ?> <a target="_blank" href="<?php echo esc_url(get_permalink($terms_page_id)); ?>"><?php echo houzez_option('spl_term', 'Terms of Use'); ?></a>
		                        <span class="control__indicator"></span>
		                    </label>
		                </div><!-- form-group -->
		                <?php } ?>
		                <div class="form_messages"></div>
		                <button class="schedule_contact_form btn btn-secondary btn-full-width">
		                    <?php get_template_part('template-parts/loader'); ?>
		                    <?php echo houzez_option('spl_btn_tour_sch', 'Submit a Tour Request'); ?> 
		                </button>
		            </div>
		        </form>

            </div>
            <div class="property-tabs-module-tab-pane tab-pane fade" id="tab_agent_form" role="tabpanel">
                
                <div class="property-form-wrap">
                	<div class="property-form clearfix">
						<form method="post" action="#">
							
							<?php echo $return_array['agent_data']; ?>

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
						
							<?php if ( $return_array['is_single_agent'] == true ) : ?>
					            <input type="hidden" name="target_email" value="<?php echo antispambot($agent_email); ?>">
					        <?php endif; ?>
					        <input type="hidden" name="property_agent_contact_security" value="<?php echo wp_create_nonce('property_agent_contact_nonce'); ?>"/>
					        <input type="hidden" name="property_permalink" value="<?php echo esc_url(get_permalink($post->ID)); ?>"/>
					        <input type="hidden" name="property_title" value="<?php echo esc_attr(get_the_title($post->ID)); ?>"/>
					        <input type="hidden" name="property_id" value="<?php echo esc_attr($property_id); ?>"/>
					        <input type="hidden" name="action" value="houzez_property_agent_contact">
					        <input type="hidden" name="listing_id" value="<?php echo intval($post->ID)?>">
					        <input type="hidden" name="is_listing_form" value="yes">
					        <input type="hidden" name="agent_id" value="<?php echo intval($return_array['agent_id'])?>">
					        <input type="hidden" name="agent_type" value="<?php echo esc_attr($return_array['agent_type'])?>">

					        <?php get_template_part('template-parts/google', 'reCaptcha'); ?>
					        <div class="form_messages"></div>
							<button type="button" class="houzez_agent_property_form btn btn-secondary <?php echo esc_attr($send_btn_class); ?>">
								<?php get_template_part('template-parts/loader'); ?>
								<?php echo houzez_option('spl_btn_send', 'Send Email'); ?>
								
							</button>
							
							<?php if ( $return_array['is_single_agent'] == true && !empty($agent_number) && $agent_mobile_num && !wp_is_mobile() ) : ?>
							<a href="tel:<?php echo esc_attr($agent_mobile_call); ?>" class="btn btn-secondary-outlined btn-half-width">
								<!-- <button type="button" class="btn"> -->
									<span class="hide-on-click"><?php echo houzez_option('spl_btn_call', 'Call'); ?></span>
									<span class="show-on-click"><?php echo esc_attr($agent_number); ?></span>
								<!-- </button> -->
							</a>
							<?php endif; ?>

							<?php if( $return_array['is_single_agent'] == true && !empty($agent_whatsapp_call) && $agent_whatsapp_num ) { ?>
							<a target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $agent_whatsapp_call ); ?>&text=<?php echo houzez_option('spl_con_interested', "Hello, I am interested in").' ['.get_the_title().'] '.get_permalink(); ?> " class="btn btn-secondary-outlined <?php echo esc_attr($whatsappBtnClass); ?>"><i class="houzez-icon icon-messaging-whatsapp mr-1"></i> <?php esc_html_e('WhatsApp', 'houzez'); ?></a>
							<?php } ?>

							<?php if( $return_array['is_single_agent'] == true && houzez_option('agent_direct_messages', 0) ) { ?>
							<button type="button" <?php echo $dataModel; ?> class="<?php echo esc_attr($action_class).' '.esc_attr($login_class); ?> btn btn-secondary-outlined <?php echo esc_attr($messageBtnClass); ?>">
								<?php get_template_part('template-parts/loader'); ?>
								<?php echo houzez_option('spl_btn_message', 'Send Message'); ?>		
							</button>
							<?php } ?>
						</form>
					</div><!-- property-form -->
                </div>

            </div>
        </div>
    </div><!-- property-form-tabs -->
</div><!-- property-form-tabs-wrap -->
<?php } 
}?>