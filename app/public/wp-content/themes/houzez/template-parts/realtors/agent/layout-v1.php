<?php
global $houzez_local, $paged;
$sticky_sidebar = houzez_option('sticky_sidebar');
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
            <?php if( ! $is_search ) { ?>
            <div class="d-flex align-items-center">
                <?php get_template_part('template-parts/page/page-title'); ?> 
            </div><!-- d-flex -->  
            <?php } ?>
        </div><!-- page-title-wrap -->

        <div class="row">
            <div class="col-lg-8 col-md-12 bt-content-wrap right-bt-content-wrap">

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

                <div class="agents-list-view">
                    <?php
                    if ( $agents_query->have_posts() ) :
                    while ( $agents_query->have_posts() ) : $agents_query->the_post();

                        get_template_part('template-parts/realtors/agent/list');

                    endwhile;
                    
                    else:
                        get_template_part('template-parts/realtors/agent/none');
                    endif;
                    ?>
                </div>
                
                <?php houzez_pagination( $agents_query->max_num_pages ); wp_reset_query(); ?>

            </div><!-- bt-content-wrap -->
            <div class="col-lg-4 col-md-12 bt-sidebar-wrap left-bt-sidebar-wrap <?php if( $sticky_sidebar['agent_sidebar'] != 0 ){ echo 'houzez_sticky'; }?>">
                <?php get_sidebar('houzez_agents'); ?>
            </div><!-- bt-sidebar-wrap -->
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