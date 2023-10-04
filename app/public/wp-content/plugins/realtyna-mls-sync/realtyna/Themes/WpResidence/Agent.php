<?php

namespace Realtyna\Sync\Themes\WpResidence;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Handle WpResidence Agent Post Type
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Agent {

    /** @var string custom post type name */
    protected static $postType = 'estate_agent';

    /** @var array array of display agent options values */
    protected static $agentsDisplayOptions = array(
        'author_info'  => 'Author Information',
        'agent_info'   => 'Agent Information',
        'agency_info'  => 'Agency Information',
        'none'         => 'Hide Information Box'
    );

    /**
     * Get PostType String for Agent
     * 
     * @author Chris A <chris.a@realtyna.net>
     *
     * @return string
     */
    public function getPostType()
    {
        return self::$postType;
    }

    /**
     * Insert New Agent if not exists
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string|int Agent title or Agent ID 
     * 
     * @return int|bool Agent ID or False on fails
     */
    public static function insert( $value ){

        $agent = trim( $value );

        if ( is_numeric( $agent ) )
            $post = get_post( $agent );
        else
            $post = get_page_by_title( $agent , 'OBJECT', self::$postType );

        if ( !empty( $post ) && !is_wp_error( $post ) ) {

            return $post->ID ;

        } else {

            $post_arr = array(
                'post_content' => '',
                'post_name'    => esc_html( $agent ),
                'post_title'   => esc_html( $agent ),
                'post_type'    => self::$postType,
                'post_status'  => 'publish',
                'post_excerpt' => ''
            );

            $post_id = wp_insert_post( $post_arr );

            if ( !is_wp_error( $post_id ) )

                return $post_id;

        }

        return false;

    }

    /**
     * Get Specefic Agent
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int|null Agent ID
     * 
     * @return array|bool agents post type as array or false n fails
     */
    public static function get( $id = null ){

        global $wpdb;

        $args = array(
            'post_type' => array( self::$postType ),
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => array( 'title' => 'ASC' )
            );

        if ( !empty( $id ) )
            $args['p'] = $id;
        
        $agents = new \WP_Query($args);
        
        return ( $agents->found_posts > 0 ) ? $agents->posts : false ;

    }

    /**
     * Get List of agent display Options as array
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @return array
     */
    public static function getDisplayOptions(){

        return self::$agentsDisplayOptions;

    }

}