<?php
global $post;

if(houzez_form_type()) {

    if(!empty(houzez_option('schedule_tour_shortcode'))) {
        echo do_shortcode(houzez_option('schedule_tour_shortcode'));
    }

} else {

$return_array = houzez20_property_contact_form();
if(empty($return_array)) {
    return;
}
$houzez_agent_display = get_post_meta( get_the_ID(), 'fave_agent_display_option', true );
$prop_agent_display = get_post_meta( get_the_ID(), 'fave_agents', true );
$schedule_time_slots = houzez_option('schedule_time_slots');
$is_single_agent = true;
$terms_page_id = houzez_option('terms_condition');
$terms_page_id = apply_filters( 'wpml_object_id', $terms_page_id, 'page', true );
$property_id = houzez_get_listing_data('property_id');

if( $prop_agent_display != '-1' && $houzez_agent_display == 'agent_info' ) {

    $prop_agent_ids = get_post_meta( get_the_ID(), 'fave_agents' );
    $prop_agent_ids = array_filter( $prop_agent_ids, function($hz){
        return ( $hz > 0 );
    });

    $prop_agent_ids = array_unique( $prop_agent_ids );

    $agents_count = count( $prop_agent_ids );

    if ( $agents_count > 1 ) :
        $is_single_agent = false;
    endif;

    foreach ( $prop_agent_ids as $agent ) :

        if ( 0 < intval( $agent ) ) :

            $prop_agent_id = intval( $agent );
            $prop_agent_email = get_post_meta( $prop_agent_id, 'fave_agent_email', true );

        endif;

    endforeach;

} elseif( $houzez_agent_display == 'agency_info' ) {

    $prop_agent_id = get_post_meta( get_the_ID(), 'fave_property_agency', true );
    $prop_agent_email = get_post_meta( $prop_agent_id, 'fave_agency_email', true );

} else {

    $prop_agent_email = get_the_author_meta( 'email' );
}

$featured_image = houzez_get_image_url('full');
$featured_image_url = $featured_image[0];

$agent_email = is_email($prop_agent_email);
?>
<form method="post" action="#">
    <input type="hidden" name="schedule_contact_form_ajax"
       value="<?php echo wp_create_nonce('schedule-contact-form-nonce'); ?>"/>
    <input type="hidden" name="property_permalink"
           value="<?php echo esc_url(get_permalink($post->ID)); ?>"/>
    <input type="hidden" name="property_title"
           value="<?php echo esc_attr(get_the_title($post->ID)); ?>"/>
    <input type="hidden" name="action" value="houzez_schedule_send_message">

    <input type="hidden" name="listing_id" value="<?php echo intval($post->ID)?>">
    <input type="hidden" name="property_id" value="<?php echo esc_attr($property_id); ?>"/>
    <input type="hidden" name="is_listing_form" value="yes">
    <input type="hidden" name="is_schedule_form" value="yes">
    <input type="hidden" name="redirect_to" value="<?php echo esc_url(houzez_option('schedule_tour_redirect')); ?>">
    <input type="hidden" name="agent_id" value="<?php echo intval($return_array['agent_id'])?>">
    <input type="hidden" name="agent_type" value="<?php echo esc_attr($return_array['agent_type'])?>">

    <input type="hidden" name="target_email" value="<?php echo antispambot($agent_email); ?>">

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="property-schedule-tour-image" style="background-image: url(<?php echo $featured_image_url; ?>);">
            </div>
        </div><!-- col-md-6 col-sm-12 -->
        <div class="col-md-6 col-sm-12">
            <div class="property-schedule-tour-form-wrap">
                <h2 class="sch-title"><?php echo houzez_option('sps_schedule_tour', 'Schedule a Tour'); ?></h2>
                
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
                <button class="schedule_contact_form btn btn-secondary btn-full-width">
                    <?php get_template_part('template-parts/loader'); ?>
                    <?php echo houzez_option('spl_btn_tour_sch', 'Submit a Tour Request'); ?> 
                </button>
            </div>
            <!-- property-schedule-tour-wrap -->
        </div>
    </div>
    <div class="form_messages mt-4"></div>
</form>
<?php } ?>