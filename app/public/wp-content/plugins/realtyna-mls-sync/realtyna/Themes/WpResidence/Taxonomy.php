<?php

namespace Realtyna\Sync\Themes\WpResidence;

/** Block direct access to file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );

/**
 * Hanlde WpResidence Taxonomy Data
 * 
 * @author Chris A <chris.a@realtyna.net>
 * 
 * @version 1.0
 */
class Taxonomy {

    /** @var string taxonomy value */
    const TAXONOMY = '';

    /**
     * Add Taxonomy to Post
     * 
     * @author Chris A <chris.a@realtyna.net>
     * 
     * @param string $value Term Title
     * @param int $postId Post ID
     * @param array $parentInfo array of Parent info
     * 
     * @return void
     */
    public function import( $value , $postId , $parentInfo = array() )
    {

        if ( taxonomy_exists( static::TAXONOMY ) && !empty( trim( $value ) ) ) {
			
			$term = term_exists( $value, static::TAXONOMY );

			if ( 0 === $term || null === $term ) {
		
				$term = wp_insert_term(
					$value,
					static::TAXONOMY,
					array(
						'slug' => strtolower( str_replace( ' ', '-', $value ) )
					)
				);
		
			}
			
			if ( !is_wp_error( $term ) ) {
				wp_set_post_terms( $postId, $term, static::TAXONOMY, true );
			}
			
		}

    }

}
?>