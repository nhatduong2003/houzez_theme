<?php
get_header();
global $paged, $wp_query;
$sticky_sidebar = houzez_option('sticky_sidebar');

if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} elseif ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}

$number_of_agencies = houzez_option('num_of_agencies');
if(!$number_of_agencies){
    $number_of_agencies = 9;
}

$args = array(
    'post_type' => 'houzez_agency',
    'posts_per_page' => $number_of_agencies,
    'paged' => $paged,
    'post_status' => 'publish',
    'meta_query' => array(
        'relation' => 'OR',
            array(
             'key' => 'fave_agency_visible',
             'compare' => 'NOT EXISTS', // works!
             'value' => '' // This is ignored, but is necessary...
            ),
            array(
             'key' => 'fave_agency_visible',
             'value' => 1,
             'type' => 'NUMERIC',
             'compare' => '!=',
            )
    )
);

/* Keyword Based Search */
if( isset ( $_GET['agency_name'] ) ) {
    $keyword = trim( $_GET['agency_name'] );
    $keyword = sanitize_text_field($keyword);
    if ( ! empty( $keyword ) ) {
        $args['s'] = $keyword;
    }
}

query_posts( $args );

$agencies_layout = houzez_option('agencies-template-layout', 'v1');

if( isset( $_GET['agencies-layout'] ) && $_GET['agencies-layout'] != "" ) {
    $agencies_layout = esc_html($_GET['agencies-layout']);
}
?>

<section class="listing-wrap">
    <div class="container">
        
        <div class="page-title-wrap">
            <?php get_template_part('template-parts/page/breadcrumb'); ?> 
            
        </div><!-- page-title-wrap -->

        <div class="row">
            
            <?php if( $agencies_layout == 'v2' ) { ?>

                <div class="col-lg-12 col-md-12">
                    
                    <div class="agencies-grid-view agencies-grid-view-4cols">
                        <?php
                        if ( have_posts() ) :
                        while ( have_posts() ) : the_post();

                                get_template_part('template-parts/realtors/agency/agency-grid');

                        endwhile;
                        
                        else:
                            get_template_part('template-parts/realtors/agency/none');
                        endif;
                        ?>
                    </div><!-- listing-view -->
                    <?php houzez_pagination( $wp_query->max_num_pages ); wp_reset_query(); ?>
                </div><!-- bt-content-wrap -->

            <?php } else { ?>
                <div class="col-lg-8 col-md-12 bt-content-wrap right-bt-content-wrap">

                    <div class="agents-list-view">
                        <?php
                        if ( have_posts() ) :
                        while ( have_posts() ) : the_post();

                                get_template_part('template-parts/realtors/agency/list');

                        endwhile;
                        
                        else:
                            get_template_part('template-parts/realtors/agency/none');
                        endif;
                        ?>
                    </div><!-- listing-view -->
                    
                    <?php houzez_pagination( $wp_query->max_num_pages ); wp_reset_query(); ?>

                </div><!-- bt-content-wrap -->
                <div class="col-lg-4 col-md-12 bt-sidebar-wrap left-bt-sidebar-wrap <?php if( $sticky_sidebar['agency_sidebar'] != 0 ){ echo 'houzez_sticky'; }?>">
                    <?php get_sidebar('agencies'); ?>
                </div><!-- bt-sidebar-wrap -->
            <?php } ?>
        </div><!-- row -->
    </div><!-- container -->
</section><!-- listing-wrap -->

<?php get_footer(); ?>