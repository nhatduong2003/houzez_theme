<?php
/**
 * Template Name: Template all Agents v3
 * User: waqasriaz
 * Date: 07/02/2023
 * Time: 01:11 PM
 */
get_header();
global $houzez_local, $paged;
$page_content_position = houzez_get_listing_data('listing_page_content_area');

$args = array(
    'post_type' => 'houzez_agent',
    'post_status' => 'publish',
    'meta_query' => array(
        'relation' => 'OR',
            array(
             'key' => 'fave_agent_visible',
             'compare' => 'NOT EXISTS', // works!
             'value' => '' // This is ignored, but is necessary...
            ),
            array(
             'key' => 'fave_agent_visible',
             'value' => 1,
             'type' => 'NUMERIC',
             'compare' => '!=',
            )
    )
);

$is_search = false;
if( isset( $_GET['agent-search'] ) ) {
    $args = apply_filters( 'houzez_agents_search_filter', $args );
    $is_search = true;
} else {
    $args = apply_filters( 'houzez_get_agents', $args );
}

$agents_query = new WP_Query( $args );
$records_found = $agents_query->found_posts;
?>

<section class="listing-wrap agents-template-wrap">
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

                <div class="agents-grid-view agents-grid-view-4cols">
                    <?php
                    if ( $agents_query->have_posts() ) :
                    while ( $agents_query->have_posts() ) : $agents_query->the_post();

                        get_template_part('template-parts/realtors/agent/agent-grid-v2');

                    endwhile;
                    
                    else:
                        get_template_part('template-parts/realtors/agent/none');
                    endif;
                    ?>
                </div><!-- listing-view -->
                <?php houzez_pagination( $agents_query->max_num_pages ); wp_reset_query(); ?>
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

<?php get_footer(); ?>
