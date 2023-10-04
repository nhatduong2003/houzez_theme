<?php
/**
 * Agents Grid v2 & v3
 */
if( !function_exists('houzez_agents_grid') ) {
    function houzez_agents_grid($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'agents_layout' => '',
            'agent_category' => '',
            'agent_city' => '',
            'posts_limit' => '',
            'offset' => '',
            'columns' => '',
            'orderby' => '',
            'order' => '',
        ), $atts));

        global $houzez_local;
        $houzez_local = houzez_get_localization();
        
        ob_start();

        if(empty($columns)) {
            $columns = 3;
        }
        
        $tax_query = array();

        $args = array(
            'post_type' => 'houzez_agent',
            'posts_per_page' => $posts_limit,
            'orderby' => $orderby,
            'order' => $order,
            'offset' => $offset,
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


        if (!empty($agent_category)) {
            $tax_query[] = array(
                'taxonomy' => 'agent_category',
                'field' => 'slug',
                'terms' => htf_traverse_comma_string($agent_category)
            );
        }


        if (!empty($agent_city)) {
            $tax_query[] = array(
                'taxonomy' => 'agent_city',
                'field' => 'slug',
                'terms' => htf_traverse_comma_string($agent_city)
            );
        }

        $tax_count = count( $tax_query );

        if( $tax_count > 1 ) {
            $tax_query['relation'] = 'AND';
        }
        if( $tax_count > 0 ){
            $args['tax_query'] = $tax_query;
        }

        $wp_qry = new WP_Query($args);

        $columns_class = 'agents-grid-view-3cols';
        if($columns == "4") {
            $columns_class = 'agents-grid-view-4cols';
        }

        $main_class = 'agent-v2-grid-module';
        if( $agents_layout == 'agent-grid-v2' ) {
            $main_class = 'agent-v3-grid-module';
        }

        ?>

        <div class="<?php echo esc_attr($main_class); ?> agents-grid-view <?php echo esc_attr($columns_class);?>">
        
            <?php 
            if ($wp_qry->have_posts()): 
                while ($wp_qry->have_posts()): $wp_qry->the_post();
        
                    get_template_part('template-parts/realtors/agent/'.$agents_layout);

                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div><!-- agent-module -->

        
        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }
}
?>