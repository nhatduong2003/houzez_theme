<?php
global $paged, $houzez_local;
$sticky_sidebar = houzez_option('sticky_sidebar');
$page_id = get_the_ID();
if ( is_front_page() ) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
}

$page_content_position = houzez_get_listing_data('listing_page_content_area');

$number_of_agencies = houzez_option('num_of_agencies');
if(!$number_of_agencies){
    $number_of_agencies = 9;
}

$qry_args = array(
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

$order = get_post_meta( $page_id, 'fave_agency_order', true );
$orderby = get_post_meta( $page_id, 'fave_agency_orderby', true );


if( !empty( $orderby ) && $orderby != 'none' ) {
    $qry_args['orderby'] = $orderby;
}
if( !empty( $order ) ) {
    $qry_args['order'] = $order;
}

$agencies_query = new WP_Query( $qry_args );
?>

<section class="listing-wrap agencies-template-wrap">
    <div class="container">
        <div class="page-title-wrap">
            <?php get_template_part('template-parts/page/breadcrumb'); ?> 
            <div class="d-flex align-items-center">
                <?php get_template_part('template-parts/page/page-title'); ?> 
            </div><!-- d-flex -->  
        </div><!-- page-title-wrap -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <?php
                if ( $page_content_position !== '1' ) {
                    if ( have_posts() ) {
                        while ( have_posts() ) {
                            the_post();
                            ?>
                            <article <?php post_class(); ?>>
                                <?php the_content(); ?>
                            </article>
                            <?php
                        }
                    } 
                }?>
                <div class="agencies-grid-view agencies-grid-view-4cols">
                    <?php
                    if ( $agencies_query->have_posts() ) :
                    while ( $agencies_query->have_posts() ) : $agencies_query->the_post();

                            get_template_part('template-parts/realtors/agency/agency-grid');

                    endwhile;
                    
                    else:
                        get_template_part('template-parts/realtors/agency/none');
                    endif;
                    ?>
                </div><!-- listing-view -->
                <?php houzez_pagination( $agencies_query->max_num_pages ); wp_reset_query(); ?>
            </div><!-- bt-content-wrap -->
        </div><!-- row -->
    </div><!-- container -->
</section><!-- listing-wrap -->
<?php
if ('1' === $page_content_position ) {
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            ?>
            <section class="content-wrap">
                <?php the_content(); ?>
            </section>
            <?php
        }
    }
}
?>