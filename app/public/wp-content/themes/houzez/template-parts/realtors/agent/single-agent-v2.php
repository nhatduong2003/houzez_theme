<?php
get_header();
global $post, $houzez_local, $paged, $agent_listing_ids;

$listing_view = houzez_option('agent_listings_layout');
$agent_company_logo = get_post_meta( get_the_ID(), 'fave_agent_logo', true );

$agent_number = get_post_meta( get_the_ID(), 'fave_agent_mobile', true );
$agent_number_call = str_replace(array('(',')',' ','-'),'', $agent_number);
if( empty($agent_number) ) {
    $agent_number = get_post_meta( get_the_ID(), 'fave_agent_office_num', true );
    $agent_number_call = str_replace(array('(',')',' ','-'),'', $agent_number);
}
$agent_position = get_post_meta( get_the_ID(), 'fave_agent_position', true );
$agent_company = get_post_meta( get_the_ID(), 'fave_agent_company', true );
$agent_agency_id = get_post_meta( get_the_ID(), 'fave_agent_agencies', true );

$href = "";
if( !empty($agent_agency_id) ) {
    $href = ' href="'.esc_url(get_permalink($agent_agency_id)).'"';
    $agent_company = get_the_title( $agent_agency_id );
}

$item_layout = $view_class = $cols_in_row = '';
$card_deck = 'card-deck';

if($listing_view == 'list-view-v1') {
    $wrap_class = 'listing-v1';
    $item_layout = 'v1';
    $view_class = 'list-view';

} elseif($listing_view == 'grid-view-v1') {
    $wrap_class = 'listing-v1';
    $item_layout = 'v1';
    $view_class = 'grid-view';

} elseif($listing_view == 'list-view-v2') {
    $wrap_class = 'listing-v2';
    $item_layout = 'v2';
    $view_class = 'list-view';

} elseif($listing_view == 'grid-view-v2') {
    $wrap_class = 'listing-v2';
    $item_layout = 'v2';
    $view_class = 'grid-view';

} elseif($listing_view == 'grid-view-v3') {
    $wrap_class = 'listing-v3';
    $item_layout = 'v3';
    $view_class = 'grid-view';

} elseif($listing_view == 'grid-view-v4') {
    $wrap_class = 'listing-v4';
    $item_layout = 'v4';
    $view_class = 'grid-view';

} elseif($listing_view == 'list-view-v5') {
    $wrap_class = 'listing-v5';
    $item_layout = 'v5';
    $view_class = 'list-view';

} elseif($listing_view == 'grid-view-v5') {
    $wrap_class = 'listing-v5';
    $item_layout = 'v5';
    $view_class = 'grid-view';

} elseif($listing_view == 'grid-view-v6') {
    $wrap_class = 'listing-v6';
    $item_layout = 'v6';
    $view_class = 'grid-view';

} elseif($listing_view == 'grid-view-v7') {
    $wrap_class = 'listing-v7';
    $item_layout = 'v7';
    $view_class = 'grid-view';

} elseif($listing_view == 'list-view-v7') {
    $wrap_class = 'listing-v7';
    $item_layout = 'list-v7';
    $view_class = 'list-view';
    $card_deck = '';
} else {
    $wrap_class = 'listing-v1';
    $item_layout = 'v1';
    $view_class = 'grid-view';
}

if(isset($_GET['tab']) || $paged > 0) {

    if(isset($_GET['tab']) && $_GET['tab'] == 'reviews') {
        $active_reviews_tab = 'active';
        $active_reviews_content = 'show active';
        $active_listings_tab = '';
        $active_listings_content = '';
    }
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $('html, body').animate({
                scrollTop: $(".agent-nav-wrap").offset().top
            }, 'slow');
        });
    </script>
    <?php
}

$the_query = Houzez_Query::loop_agent_properties();
$agent_total_listings = $the_query->found_posts; 
$agent_listing_ids = Houzez_Query::get_agent_properties_ids_by_agent_id(get_the_ID());

$active_reviews_tab = '';
$active_reviews_content = '';
$active_listings_content = '';
$active_listings_tab = '';
if( houzez_option( 'agent_listings', 0 ) != 1 && houzez_option( 'agent_review', 0 ) != 0 ) {
    $active_reviews_tab = 'active';
    $active_reviews_content = 'show active';

} else if ( $agent_total_listings == 0 ) {
    $active_reviews_tab = 'active';
    $active_reviews_content = 'show active';
} else {
    $active_listings_tab = 'active';
    $active_listings_content = 'show active';
}
?>
<section class="content-wrap">
    <div class="agent-profile-wrap">
        <div class="container">
            <div class="agent-profile-top-wrap">
                <div class="agent-image">
                    <?php get_template_part('template-parts/realtors/agent/image'); ?>
                </div><!-- agent-image -->
                <div class="agent-profile-header">
                    <?php if( $agent_position != '' ) { ?>
                    <div class="agent-list-position">
                        <a><?php echo esc_attr($agent_position); ?></a>
                    </div>
                    <?php } ?>
                    
                    <h1><?php the_title(); ?></h1>
                    
                    <?php 
                    if( houzez_option( 'agent_review', 0 ) != 0 ) {
                        get_template_part('template-parts/realtors/rating'); 
                    }?>

                    <?php if( $agent_company != "" ) { ?>
                    <div class="agent-list-position">
                        <a<?php echo $href; ?>><?php echo esc_attr( $agent_company ); ?></a>
                    </div><!-- agent-list-position -->
                    <?php } ?>

                    <div class="agent-profile-cta">
                        <ul class="list-inline">
                            <?php if( houzez_option('agent_form_agent_page', 1) ) { ?>
                            <li class="list-inline-item"><a href="#" data-toggle="modal" data-target="#realtor-form"><i class="houzez-icon icon-messages-bubble mr-1"></i> <?php esc_html_e( 'Ask a question', 'houzez' ); ?></a></li>
                            <?php } ?>

                            <?php if(!empty($agent_number)) { ?>
                            <li class="list-inline-item">
                                <a href="tel:<?php echo esc_attr($agent_number_call); ?>">
                                    <i class="houzez-icon icon-phone mr-1"></i> <?php echo esc_attr($agent_number); ?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div><!-- agent-profile-header -->
            </div><!-- agent-profile-top-wrap -->
        </div><!-- container -->
    </div><!-- agent-profile-wrap -->

    <div class="agent-nav-wrap">
        <?php if( houzez_option( 'agent_listings', 0 ) != 0 || houzez_option( 'agent_review', 0 ) != 0 ) { ?>
        <div class="container">
            <ul class="nav">
                <?php 
                if( houzez_option( 'agent_listings', 0 ) != 0 && $agent_total_listings > 0 ) { ?>
                <li class="nav-item">
                    <a class="nav-link  <?php echo esc_attr($active_listings_tab); ?>" href="#tab-properties" data-toggle="pill" role="tab"><?php esc_html_e('Listings', 'houzez'); ?> (<?php echo esc_attr($agent_total_listings); ?>)</a>
                </li>
                <?php } ?>

                <?php if( houzez_option( 'agent_review', 0 ) != 0 ) { ?>
                <li class="nav-item">
                    <a class="nav-link hz-review-tab <?php echo esc_attr($active_reviews_tab); ?>" href="#tab-reviews" data-toggle="pill" role="tab"><?php esc_html_e('Reviews', 'houzez'); ?> (<?php echo houzez_reviews_count('review_agent_id'); ?>)</a>
                </li>
                <?php } ?>
            </ul>
        </div><!-- container -->
        <?php } ?>
    </div><!-- agent-nav-wrap -->

    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12 bt-content-wrap">
                <div class="tab-content" id="tab-content">
                    
                    <?php if( houzez_option( 'agent_listings', 0 ) != 0 ) { ?>
                    <div class="tab-pane fade <?php echo esc_attr($active_listings_content); ?>" id="tab-properties">
                        <div class="listing-tools-wrap">
                            <div class="d-flex align-items-center">
                                <div class="listing-tabs flex-grow-1">
                                <?php get_template_part('template-parts/realtors/agent/listing-tabs'); ?>   
                                </div>
                                <?php get_template_part('template-parts/listing/listing-sort-by'); ?>    
                            </div><!-- d-flex -->
                        </div><!-- listing-tools-wrap -->
                        <div class="listing-view <?php echo esc_attr($view_class).' '.esc_attr($card_deck); ?>">
                            <?php
                            if ( $the_query->have_posts() ) :
                                while ( $the_query->have_posts() ) : $the_query->the_post();

                                    $agent_listing_ids[] = get_the_ID(); 
                                    get_template_part('template-parts/listing/item', $item_layout);

                                endwhile;
                                wp_reset_postdata();
                            else:
                                get_template_part('template-parts/listing/item', 'none');
                            endif;
                            ?> 
                        </div><!-- listing-view -->
                        <?php houzez_pagination( $the_query->max_num_pages ); ?>
                    </div><!-- tab-pane -->
                    <?php } ?>

                    <?php if( houzez_option( 'agent_review', 0 ) != 0 ) { ?>
                    <div class="tab-pane fade <?php echo esc_attr($active_reviews_content); ?>" id="tab-reviews">
                        <?php get_template_part('template-parts/reviews/main'); ?> 
                    </div><!-- tab-pane -->
                    <?php } ?>

                </div><!-- tab-content -->
            </div><!-- bt-content-wrap -->

            <div class="col-lg-4 col-md-12 bt-sidebar-wrap">
                <aside class="sidebar-wrap">
                    
                    <?php if( houzez_option('agent_bio', 0) != 0 ) { ?>
                    <div class="agent-bio-wrap">
                        <h2><?php echo esc_html__('About', 'houzez'); ?> <?php the_title(); ?></h2>
                        
                        <?php the_content(); ?>

                        <?php get_template_part('template-parts/realtors/agent/languages'); ?> 
                    </div><!-- agent-bio-wrap --> 
                    <?php } ?>

                    <div class="agent-profile-content">
                        <ul class="list-unstyled">
                            <?php get_template_part('template-parts/realtors/agent/license'); ?>

                            <?php get_template_part('template-parts/realtors/agent/tax-number'); ?>

                            <?php get_template_part('template-parts/realtors/agent/service-area'); ?>

                            <?php get_template_part('template-parts/realtors/agent/specialties'); ?>
                        </ul>
                    </div><!-- agent-profile-content -->
                    <?php get_template_part('template-parts/realtors/agent/agent-contacts') ;?>
                    
                    <?php if( !empty($agent_listing_ids) && houzez_option('agent_stats', 0) != 0 ) { ?>
                    <div class="agent-stats-wrap">
                        <?php get_template_part('template-parts/realtors/agent/stats-property-types'); ?>  
                        <?php get_template_part('template-parts/realtors/agent/stats-property-status'); ?>
                        <?php get_template_part('template-parts/realtors/agent/stats-property-cities'); ?>
                    </div><!-- agent-stats-wrap -->
                    <?php } ?>
                </aside>
            </div><!-- bt-sidebar-wrap -->
        </div><!-- row -->
    </div><!-- container -->

</section><!-- content-wrap -->


<?php get_footer(); ?>
