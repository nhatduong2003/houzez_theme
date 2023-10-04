<?php
add_filter('houzez_get_agents', 'houzez_get_agents_callback');
if( !function_exists('houzez_get_agents_callback') ) {
    function houzez_get_agents_callback( $query_args ) {
        global $paged;

        $page_id = get_the_ID();
        $tax_query = array();
        $meta_query = array();

        $paged = 1;
        if ( get_query_var( 'paged' ) ) {
            $paged = get_query_var( 'paged' );
        } elseif ( get_query_var( 'page' ) ) { // if is static front page
            $paged = get_query_var( 'page' );
        }

        $query_args['paged'] = $paged;

        $number_of_agents = houzez_option('num_of_agents');

        if(!$number_of_agents){
            $query_args[ 'posts_per_page' ]  = 9;
        } else {
            $query_args[ 'posts_per_page' ] = $number_of_agents;
        }


        $agent_cats = get_post_meta( $page_id, 'fave_agent_category', false );
        $agent_cities = get_post_meta( $page_id, 'fave_agent_city', false );
        $order = get_post_meta( $page_id, 'fave_agent_order', true );
        $orderby = get_post_meta( $page_id, 'fave_agent_orderby', true );


        if ( ! empty( $agent_cats ) && is_array( $agent_cats ) ) {
            $tax_query[] = array(
                'taxonomy' => 'agent_category',
                'field' => 'slug',
                'terms' => $agent_cats
            );
        }

        if ( ! empty( $agent_cities ) && is_array( $agent_cities ) ) {
            $tax_query[] = array(
                'taxonomy' => 'agent_city',
                'field' => 'slug',
                'terms' => $agent_cities
            );
        }

        $tax_count = count( $tax_query );

        if( $tax_count > 1 ) {
            $tax_query['relation'] = 'AND';
        }
        if( $tax_count > 0 ){
            $query_args['tax_query'] = $tax_query;
        }

        if( !empty( $orderby ) && $orderby != 'none' ) {
            $query_args['orderby'] = $orderby;
        }
        if( !empty( $order ) ) {
            $query_args['order'] = $order;
        }

        return $query_args;
    }
}

add_filter('houzez_agents_search_filter', 'houzez_agents_search_filter_callback');
if( !function_exists('houzez_agents_search_filter_callback') ) {
    function houzez_agents_search_filter_callback( $query_args ) {
        global $paged;

        $tax_query = array();
        $meta_query = array();

        $paged = 1;
        if ( get_query_var( 'paged' ) ) {
            $paged = get_query_var( 'paged' );
        } elseif ( get_query_var( 'page' ) ) { // if is static front page
            $paged = get_query_var( 'page' );
        }

        $query_args['paged'] = $paged;

        $number_of_agents = houzez_option('num_of_agents');

        if(!$number_of_agents){
            $query_args[ 'posts_per_page' ]  = 9;
        } else {
            $query_args[ 'posts_per_page' ] = $number_of_agents;
        }

        if(isset($_GET['city']) && $_GET['city'] != "") {
            $tax_query[] = array(
                'taxonomy' => 'agent_city',
                'field' => 'slug',
                'terms' => $_GET['city']
            );
        }
        if(isset($_GET['category']) && $_GET['category'] != "") {
            $tax_query[] = array(
                'taxonomy' => 'agent_category',
                'field' => 'slug',
                'terms' => $_GET['category']
            );
        }

        $tax_count = count( $tax_query );

        if( $tax_count > 1 ) {
            $tax_query['relation'] = 'AND';
        }
        if( $tax_count > 0 ){
            $query_args['tax_query'] = $tax_query;
        }

        return $query_args;
    }
}

// Function to run when any action happens for "houzez_agency" custom post type
function houzez_update_cron_result( $post_id, $post, $update ) {
    if ( 'houzez_agency' === $post->post_type ) {
        // Retrieve updated post IDs for "houzez_agency" custom post type
        $args = array(
            'post_type' => 'houzez_agency',
            'fields' => 'ids',
        );
        $query = new WP_Query( $args );
        $post_ids = $query->posts;

        // Save updated post IDs to options table
        update_option( 'houzez_agency_post_ids', $post_ids );
    }
}
//add_action( 'save_post', 'houzez_update_cron_result', 10, 3 );