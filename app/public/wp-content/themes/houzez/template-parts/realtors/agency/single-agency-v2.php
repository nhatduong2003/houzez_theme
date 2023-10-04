<?php
get_header();

global $post, $houzez_local, $properties_ids;

$is_sticky = '';
$sticky_sidebar = houzez_option('sticky_sidebar');
if( $sticky_sidebar['agency_sidebar'] != 0 ) { 
    $is_sticky = 'houzez_sticky'; 
}

$properties_ids_array = array();
$listing_view = houzez_option('agency_listings_layout');
$agency_phone = get_post_meta( get_the_ID(), 'fave_agency_phone', true );
$agency_phone_call = str_replace(array('(',')',' ','-'),'', $agency_phone);

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

$active_reviews_tab = $active_agents_tab = '';
$active_reviews_content = $active_agents_content = '';
if( houzez_option( 'agency_listings', 0 ) != 1 && houzez_option( 'agency_agents', 0 ) != 1 && houzez_option( 'agency_review', 0 ) != 0 ) {
    $active_reviews_tab = 'active';
    $active_reviews_content = 'show active';

} elseif( houzez_option( 'agency_listings', 0 ) == 0 && houzez_option( 'agency_agents', 0 ) == 1 ) {
    $active_agents_tab = 'active';
    $active_agents_content = 'show active';

} else {
    $active_listings_tab = 'active';
    $active_listings_content = 'show active';
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


global $paged;
if ( is_front_page()  ) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
}

$agency_id = get_the_ID();

$tax_query = array();
$meta_query = array();

if ( isset( $_GET['tab'] ) && !empty($_GET['tab']) && $_GET['tab'] != "reviews") {
    $tax_query[] = array(
        'taxonomy' => 'property_status',
        'field' => 'slug',
        'terms' => $_GET['tab']
    );
}

$args = array(
    'post_type' => 'property',
    'posts_per_page' => houzez_option('num_of_agency_listings', 9),
    'paged' => $paged,
    'post_status' => 'publish',
);

$agents_array = array();
$agency_agents_ids = Houzez_Query::loop_agency_agents_ids(get_the_ID());


if( !empty($agency_agents_ids) ) {
    $agents_array = array(
        'key' => 'fave_agents',
        'value' => $agency_agents_ids,
        'compare' => 'IN',
    );
}

$args['meta_query'] = array(
    'relation' => 'OR',
    $agents_array,
    array(
        'relation' => 'AND',
        array(
            'key'     => 'fave_property_agency',
            'value'   => $agency_id,
            'compare' => '='
        ),
        array(
            'key'     => 'fave_agent_display_option',
            'value'   => 'agency_info',
            'compare' => '='
        )
    ),
);


$tax_count = count($tax_query);
if($tax_count > 0 ) {
    $args['tax_query'] = $tax_query;
}


$args = houzez_prop_sort($args);

$agency_qry = new WP_Query( $args );
$agency_total_listing = $agency_qry->found_posts;


$agents_query = Houzez_Query::loop_agency_agents(get_the_ID());

$agent_wrap = 'agents-list-view';
$agent_layout = 'list';
$agents_template = houzez_option('agents-template-layout', 'v1');
if( $agents_template == 'v2' ) { 
    $agent_layout = 'agent-grid';
    $agent_wrap = 'agents-grid-view agents-grid-view-3cols';
} elseif( $agents_template == 'v3' ) {
    $agent_wrap = 'agents-grid-view agents-grid-view-3cols';
    $agent_layout = 'agent-grid-v2';
}
?>
<section class="content-wrap">

    <div class="agent-profile-wrap">
        <div class="container">
            <div class="agent-profile-top-wrap">
                <div class="agency-image">
                    <?php get_template_part('template-parts/realtors/agency/image'); ?>
                </div><!-- agent-image -->
                <div class="agent-profile-header">
                    <h1><?php the_title(); ?> </h1>
                    <?php 
                    if( houzez_option( 'agency_review', 0 ) != 0 ) {
                        get_template_part('template-parts/realtors/rating'); 
                    }?> 
                    <div class="agent-profile-address">
                        <?php get_template_part('template-parts/realtors/agency/address'); ?> 
                    </div><!-- agent-profile-address -->
                    <div class="agent-profile-cta">
                        <ul class="list-inline">
                            <?php if( houzez_option('agency_form_agency_page', 1) ) { ?>
                            <li class="list-inline-item"><a href="#" data-toggle="modal" data-target="#realtor-form"><i class="houzez-icon icon-messages-bubble mr-1"></i> <?php esc_html_e('Ask a question', 'houzez'); ?></a></li>
                            <?php } ?>
                            <?php if(!empty($agency_phone)) { ?>
                            <li class="list-inline-item">
                                <a href="tel:<?php echo esc_attr($agency_phone_call); ?>"><i class="houzez-icon icon-phone mr-1"></i> <?php echo esc_attr($agency_phone); ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div><!-- agent-profile-header -->
            </div><!-- agent-profile-top-wrap -->
        </div><!-- container -->
    </div><!-- agent-profile-wrap -->

    <div class="agent-nav-wrap">
        <?php if( houzez_option( 'agency_listings', 0 ) != 0 || houzez_option( 'agency_review', 0 ) != 0 || houzez_option( 'agency_agents', 0 ) != 0 ) { ?>
        <div class="container">
            <ul class="nav">
                <?php if( houzez_option( 'agency_listings', 0 ) != 0 ) { ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo esc_attr($active_listings_tab); ?>" href="#tab-properties" data-toggle="pill" role="tab"><?php esc_html_e('Listings', 'houzez'); ?> (<?php echo esc_attr($agency_total_listing); ?>)</a>
                </li>
                <?php } ?>

                <?php if( houzez_option( 'agency_agents', 0 ) != 0 ) { ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo esc_attr($active_agents_tab); ?>" href="#tab-agents" data-toggle="pill" role="tab"><?php esc_html_e('Agents', 'houzez'); ?> (<?php echo esc_attr($agents_query->found_posts); ?>)</a>
                </li>
                <?php } ?>

                <?php if( houzez_option( 'agency_review', 0 ) != 0 ) { ?>
                <li class="nav-item">
                    <a class="nav-link hz-review-tab <?php echo esc_attr($active_reviews_tab); ?>" href="#tab-reviews" data-toggle="pill" role="tab"><?php esc_html_e('Reviews', 'houzez'); ?> (<?php echo houzez_reviews_count('review_agency_id'); ?>)</a>
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
                    
                    <?php if( houzez_option( 'agency_listings', 0 ) != 0 ) { ?>
                    <div class="tab-pane fade <?php echo esc_attr($active_listings_content); ?>" id="tab-properties">
                        <div class="listing-tools-wrap">
                            <div class="d-flex align-items-center">
                                <div class="listing-tabs flex-grow-1">
                                    <?php get_template_part('template-parts/realtors/agency/listing-tabs'); ?> 
                                </div>
                                <?php get_template_part('template-parts/realtors/agency/listing-sort-by'); ?>   
                            </div><!-- d-flex -->
                        </div><!-- listing-tools-wrap -->
                        <div class="listing-view <?php echo esc_attr($view_class).' '.esc_attr($card_deck); ?>">
                            
                            <?php
                            if ( $agency_qry->have_posts() ) :
                                while ( $agency_qry->have_posts() ) : $agency_qry->the_post();

                                    get_template_part('template-parts/listing/item', $item_layout);

                                endwhile;
                                wp_reset_postdata();
                            else:
                                get_template_part('template-parts/listing/item', 'none');
                            endif;
                            ?> 
                            
                        </div><!-- listing-view -->
                        <?php houzez_pagination( $agency_qry->max_num_pages ); ?>
                    </div><!-- tab-pane -->
                    <?php } ?>

                    <?php if( houzez_option( 'agency_agents', 0 ) != 0 ) { ?>
                    <div class="tab-pane fade <?php echo esc_attr($active_agents_content); ?>" id="tab-agents">

                        <div class="<?php echo esc_attr($agent_wrap); ?>">
                            <?php
                            if ( $agents_query->have_posts() ) :
                                while ( $agents_query->have_posts() ) : $agents_query->the_post();

                                    get_template_part('template-parts/realtors/agent/'.$agent_layout);

                                endwhile;
                                wp_reset_postdata();
                            else:
                                get_template_part('template-parts/realtors/agent/none');
                            endif;
                            ?> 
                        </div><!-- listing-view -->
                    </div><!-- tab-pane -->
                    <?php } ?>

                    <?php if( houzez_option( 'agency_review', 0 ) != 0 ) { ?>
                    <div class="tab-pane fade <?php echo esc_attr($active_reviews_content); ?>" id="tab-reviews">
                        <?php get_template_part('template-parts/reviews/main'); ?> 
                    </div><!-- tab-pane -->
                    <?php } ?>

                </div><!-- tab-content -->
            </div><!-- bt-content-wrap -->
            <div class="col-lg-4 col-md-12 bt-sidebar-wrap">
                <aside class="sidebar-wrap">
                    <div class="agent-bio-wrap">
                        <h2><?php echo esc_html__('About', 'houzez'); ?> <?php the_title(); ?></h2>
                        <?php the_content(); ?>

                        <?php get_template_part('template-parts/realtors/agency/languages'); ?>
                    </div><!-- agent-bio-wrap --> 
                    <div class="agent-profile-content">
                        <ul class="list-unstyled">
                            <?php get_template_part('template-parts/realtors/agency/license'); ?>
                            <?php get_template_part('template-parts/realtors/agency/tax-number'); ?>
                        </ul>
                    </div><!-- agent-profile-content -->
                    <?php get_template_part('template-parts/realtors/agency/agency-contacts'); ?> 
                    
                </aside>
            </div><!-- bt-sidebar-wrap -->
        </div><!-- row -->
    </div><!-- container -->

</section><!-- content-wrap -->

<?php get_footer(); ?>