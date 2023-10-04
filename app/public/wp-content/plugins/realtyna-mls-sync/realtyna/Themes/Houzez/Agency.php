<?php

namespace Realtyna\Sync\Themes\Houzez;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Handle Houzez Agency Post Type
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Agency {

    /** @var string custom post type name */
    protected static $postType = 'houzez_agency';

    /** @var array array of display agent options values */
    protected static $agentsDisplayOptions = array(
        'author_info'  => 'Author Information',
        'agent_info'   => 'Agent Information',
        'agency_info'  => 'Agency Information',
        'none'         => 'Hide Information Box'
    );

    /**
     * Get PostType String for Agency
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
     * Insert New Agency if not exists
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string|int Agency title or Agency ID 
     * 
     * @return int|bool Agency ID or False on fails
     */
    public static function insert( $value ){

        $agency = trim( $value );

        if ( is_numeric( $agency ) )
            $post = get_post( $agency );
        else
            $post = get_page_by_title( $agency , 'OBJECT', self::$postType );

        if ( !empty( $post ) || !is_wp_error( $post ) ) {

            return $post->ID ;

        } else {

            $post_arr = array(
                'post_content' => '',
                'post_name'    => esc_html( $agency ),
                'post_title'   => esc_html( $agency ),
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
     * Get Specefic Agency
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param int|null Agent ID
     * 
     * @return array|bool agencies post type as array or false n fails
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
        
        $agencies = new \WP_Query($args);
        
        return ( $agencies->found_posts > 0 ) ? $agencies->posts : false ;

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